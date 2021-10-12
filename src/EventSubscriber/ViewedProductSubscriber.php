<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\ViewedProductProperties;
use Setono\SyliusKlaviyoPlugin\Event\PropertiesArePopulatedEvent;
use Setono\SyliusKlaviyoPlugin\Message\Command\TrackEvent;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class ViewedProductSubscriber extends AbstractEventSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.show' => 'track',
        ];
    }

    public function track(ResourceControllerEvent $resourceControllerEvent): void
    {
        if (!$this->trackingStrategy->track()) {
            return;
        }

        /** @var ProductInterface|mixed $product */
        $product = $resourceControllerEvent->getSubject();
        Assert::isInstanceOf($product, ProductInterface::class);

        // here we set some defaults
        $properties = $this->propertiesFactory->create(ViewedProductProperties::class, $product);

        // then we create the event that will be sent to Klaviyo
        $event = $this->eventFactory->create($properties);

        // then we allow plugin users to easily hook into the population of all of these properties
        $this->eventDispatcher->dispatch(new PropertiesArePopulatedEvent($event, [
            'product' => $product,
        ]));

        // and finally we're ready to send the event
        $this->commandBus->dispatch(new TrackEvent($event));
    }
}
