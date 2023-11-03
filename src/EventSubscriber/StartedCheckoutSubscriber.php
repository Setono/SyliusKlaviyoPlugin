<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\SyliusKlaviyoPlugin\BotDetector\BotDetectorInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Factory\EventFactoryInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory\PropertiesFactoryInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\StartedCheckoutProperties;
use Setono\SyliusKlaviyoPlugin\Event\PropertiesArePopulatedEvent;
use Setono\SyliusKlaviyoPlugin\Message\Command\TrackEvent;
use Setono\SyliusKlaviyoPlugin\Strategy\TrackingStrategyInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

final class StartedCheckoutSubscriber extends AbstractEventSubscriber
{
    private CartContextInterface $cartContext;

    public function __construct(
        MessageBusInterface $commandBus,
        EventFactoryInterface $eventFactory,
        PropertiesFactoryInterface $propertiesFactory,
        EventDispatcherInterface $eventDispatcher,
        TrackingStrategyInterface $trackingStrategy,
        BotDetectorInterface $botDetector,
        CartContextInterface $cartContext
    ) {
        parent::__construct($commandBus, $eventFactory, $propertiesFactory, $eventDispatcher, $trackingStrategy, $botDetector);

        $this->cartContext = $cartContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'track',
        ];
    }

    public function track(RequestEvent $requestEvent): void
    {
        if (!$requestEvent->isMainRequest()) {
            return;
        }

        if (!$this->trackingStrategy->track() || $this->botDetector->isBot()) {
            return;
        }

        $request = $requestEvent->getRequest();
        if (!$request->attributes->has('_route') || $request->attributes->get('_route') !== 'sylius_shop_checkout_start') {
            return;
        }

        $cart = $this->cartContext->getCart();

        // here we set some defaults
        $properties = $this->propertiesFactory->create(StartedCheckoutProperties::class, $cart);

        // then we create the event that will be sent to Klaviyo
        $event = $this->eventFactory->create($properties);

        // then we allow plugin users to easily hook into the population of all of these properties
        $this->eventDispatcher->dispatch(new PropertiesArePopulatedEvent($event, [
            'cart' => $cart,
        ]));

        // and finally we're ready to send the event
        $this->commandBus->dispatch(new TrackEvent($event));
    }
}
