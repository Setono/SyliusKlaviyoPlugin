<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\SyliusKlaviyoPlugin\BotDetector\BotDetectorInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Factory\EventFactoryInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory\PropertiesFactoryInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\OrderedProductProperties;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\PlacedOrderProperties;
use Setono\SyliusKlaviyoPlugin\Event\PropertiesArePopulatedEvent;
use Setono\SyliusKlaviyoPlugin\Message\Command\TrackEvent;
use Setono\SyliusKlaviyoPlugin\Strategy\TrackingStrategyInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * This class will populate the properties for both the 'Placed Order' event and the 'Ordered Product' events
 * Documentation for this is found here: https://help.klaviyo.com/hc/en-us/articles/115005082927#placed-order7
 */
final class PlacedOrderSubscriber extends AbstractEventSubscriber
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        MessageBusInterface $commandBus,
        EventFactoryInterface $eventFactory,
        PropertiesFactoryInterface $propertiesFactory,
        EventDispatcherInterface $eventDispatcher,
        TrackingStrategyInterface $trackingStrategy,
        BotDetectorInterface $botDetector,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($commandBus, $eventFactory, $propertiesFactory, $eventDispatcher, $trackingStrategy, $botDetector);
        $this->orderRepository = $orderRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'track',
        ];
    }

    public function track(RequestEvent $requestEvent): void
    {
        if (!$this->trackingStrategy->track() || $this->botDetector->isBot()) {
            return;
        }

        $order = $this->resolveOrder($requestEvent);
        if (null === $order) {
            return;
        }

        $this->handlePlacedOrderEvent($order);

        foreach ($order->getItems() as $item) {
            $this->handleOrderedProduct($item);
        }
    }

    private function handlePlacedOrderEvent(OrderInterface $order): void
    {
        // here we set some defaults
        $properties = $this->propertiesFactory->create(PlacedOrderProperties::class, $order);

        // then we create the event that will be sent to Klaviyo
        $event = $this->eventFactory->create($properties);

        // then we allow plugin users to easily hook into the population of all of these properties
        $this->eventDispatcher->dispatch(new PropertiesArePopulatedEvent($event, [
            'order' => $order,
        ]));

        // and finally we're ready to send the event
        $this->commandBus->dispatch(new TrackEvent($event));
    }

    private function handleOrderedProduct(OrderItemInterface $orderItem): void
    {
        // here we set some defaults
        $properties = $this->propertiesFactory->create(OrderedProductProperties::class, $orderItem);

        // then we create the event that will be sent to Klaviyo
        $event = $this->eventFactory->create($properties);

        // then we allow plugin users to easily hook into the population of all of these properties
        $this->eventDispatcher->dispatch(new PropertiesArePopulatedEvent($event, [
            'orderItem' => $orderItem,
        ]));

        // and finally we're ready to send the event
        $this->commandBus->dispatch(new TrackEvent($event));
    }

    /**
     * This method will return an OrderInterface if
     * - We are on the 'thank you' page
     * - A session exists with the order id
     * - The order can be found in the order repository
     */
    private function resolveOrder(RequestEvent $requestEvent): ?OrderInterface
    {
        $request = $requestEvent->getRequest();

        if (!$requestEvent->isMasterRequest()) {
            return null;
        }

        if (!$request->attributes->has('_route')) {
            return null;
        }

        $route = $request->attributes->get('_route');
        if ('sylius_shop_order_thank_you' !== $route) {
            return null;
        }

        /** @var mixed $orderId */
        $orderId = $request->getSession()->get('sylius_order_id');

        if (!is_scalar($orderId)) {
            return null;
        }

        $order = $this->orderRepository->find($orderId);
        if (!$order instanceof OrderInterface) {
            return null;
        }

        return $order;
    }
}
