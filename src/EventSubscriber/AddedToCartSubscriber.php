<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\AddedToCartProperties;
use Setono\SyliusKlaviyoPlugin\Event\PropertiesArePopulatedEvent;
use Setono\SyliusKlaviyoPlugin\Message\Command\TrackEvent;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;
use Webmozart\Assert\Assert;

final class AddedToCartSubscriber extends AbstractEventSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_add' => 'track',
        ];
    }

    public function track(ResourceControllerEvent $resourceControllerEvent): void
    {
        if (!$this->trackingStrategy->track()) {
            return;
        }

        /** @var OrderItemInterface|mixed $orderItem */
        $orderItem = $resourceControllerEvent->getSubject();
        Assert::isInstanceOf($orderItem, OrderItemInterface::class);

        // here we set some defaults
        $properties = $this->propertiesFactory->create(AddedToCartProperties::class, $orderItem);

        // then we create the event that will be sent to Klaviyo
        $event = $this->eventFactory->create($properties);

        // then we allow plugin users to easily hook into the population of all of these properties
        $this->eventDispatcher->dispatch(new PropertiesArePopulatedEvent($event, [
            'orderItem' => $orderItem,
        ]));

        // and finally we're ready to send the event
        $this->commandBus->dispatch(new TrackEvent($event));
    }
}
