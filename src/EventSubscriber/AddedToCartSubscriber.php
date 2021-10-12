<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Factory\EventFactoryInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\AddedToCartProperties;
use Setono\SyliusKlaviyoPlugin\Event\PropertiesArePopulatedEvent;
use Setono\SyliusKlaviyoPlugin\Message\Command\TrackEvent;
use Setono\SyliusKlaviyoPlugin\Strategy\TrackingStrategyInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

final class AddedToCartSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $commandBus;

    private EventFactoryInterface $eventFactory;

    private UrlGeneratorInterface $urlGenerator;

    private CacheManager $cacheManager;

    private ProductVariantResolverInterface $productVariantResolver;

    private ChannelContextInterface $channelContext;

    private EventDispatcherInterface $eventDispatcher;

    private TrackingStrategyInterface $trackingStrategy;

    public function __construct(
        MessageBusInterface $commandBus,
        EventFactoryInterface $eventFactory,
        UrlGeneratorInterface $urlGenerator,
        CacheManager $cacheManager,
        ProductVariantResolverInterface $productVariantResolver,
        ChannelContextInterface $channelContext,
        EventDispatcherInterface $eventDispatcher,
        TrackingStrategyInterface $trackingStrategy
    ) {
        $this->commandBus = $commandBus;
        $this->eventFactory = $eventFactory;
        $this->urlGenerator = $urlGenerator;
        $this->cacheManager = $cacheManager;
        $this->productVariantResolver = $productVariantResolver;
        $this->channelContext = $channelContext;
        $this->eventDispatcher = $eventDispatcher;
        $this->trackingStrategy = $trackingStrategy;
    }

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
        $properties = new AddedToCartProperties();
        $properties->populateFromOrderItem($orderItem);
//        $properties->populateFromOrder($order);

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
