<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Factory\EventFactoryInterface;
use Setono\SyliusKlaviyoPlugin\DTO\ViewedProductProperties;
use Setono\SyliusKlaviyoPlugin\Event\PropertiesArePopulatedEvent;
use Setono\SyliusKlaviyoPlugin\Message\Command\TrackEvent;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

final class ViewedProductSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $commandBus;

    private EventFactoryInterface $eventFactory;

    private UrlGeneratorInterface $urlGenerator;

    private CacheManager $cacheManager;

    private ProductVariantResolverInterface $productVariantResolver;

    private ChannelContextInterface $channelContext;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        MessageBusInterface $commandBus,
        EventFactoryInterface $eventFactory,
        UrlGeneratorInterface $urlGenerator,
        CacheManager $cacheManager,
        ProductVariantResolverInterface $productVariantResolver,
        ChannelContextInterface $channelContext,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->commandBus = $commandBus;
        $this->eventFactory = $eventFactory;
        $this->urlGenerator = $urlGenerator;
        $this->cacheManager = $cacheManager;
        $this->productVariantResolver = $productVariantResolver;
        $this->channelContext = $channelContext;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.show' => 'track',
        ];
    }

    public function track(ResourceControllerEvent $resourceControllerEvent): void
    {
        /** @var ProductInterface|mixed $product */
        $product = $resourceControllerEvent->getSubject();
        Assert::isInstanceOf($product, ProductInterface::class);

        /** @var ProductVariantInterface|null $productVariant */
        $productVariant = $this->productVariantResolver->getVariant($product);

        // here we set some defaults
        $properties = new ViewedProductProperties();
        $properties->populateFromProduct($product);
        $properties->populateUrl($this->urlGenerator, $product);
        $properties->populateImageUrl($product, $this->cacheManager);

        if (null !== $productVariant) {
            /** @var ChannelInterface $channel */
            $channel = $this->channelContext->getChannel();
            $properties->populatePrices($productVariant, $channel);
        }

        // then we create the event that will be sent to Klaviyo
        $event = $this->eventFactory->create($properties);

        // then we allow plugin users to easily hook into the population of all of these properties
        $this->eventDispatcher->dispatch(new PropertiesArePopulatedEvent($event, [
            'channel' => $channel ?? null,
            'product' => $product,
            'productVariant' => $productVariant,
        ]));

        // and finally we're ready to send the event
        $this->commandBus->dispatch(new TrackEvent($event));
    }
}
