<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\ImagesAwareInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

class ViewedProductProperties extends Properties
{
    use MoneyFormatterTrait;

    /** @psalm-readonly */
    public ?string $event = 'Viewed Product';

    public ?string $productName = null;

    public ?string $productId = null;

    public ?string $sku = null;

    /** @var array<array-key, string> */
    public array $categories = [];

    public ?string $imageUrl = null;

    public ?string $url = null;

    public ?string $brand = null;

    public ?float $price = null;

    public ?float $compareAtPrice = null;

    public function populateFromProduct(ProductInterface $product): void
    {
        $this->productId = (string) $product->getId();
        $this->sku = $product->getCode();
        $this->productName = $product->getName();
    }

    public function populateUrl(UrlGeneratorInterface $urlGenerator, ProductInterface $product): void
    {
        $this->url = $urlGenerator->generate('sylius_shop_product_show', [
            'slug' => $product->getSlug(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function populateImageUrl(
        ImagesAwareInterface $imagesAware,
        CacheManager $cacheManager,
        string $filter = 'sylius_shop_product_large_thumbnail',
        string $imageType = null
    ): void {
        if (null === $imageType) {
            $images = $imagesAware->getImages();
        } else {
            $images = $imagesAware->getImagesByType($imageType);
        }

        if ($images->count() === 0) {
            return;
        }

        /** @var ImageInterface|mixed $image */
        $image = $images->first();
        Assert::isInstanceOf($image, ImageInterface::class);

        $this->imageUrl = $cacheManager->getBrowserPath((string) $image->getPath(), $filter);
    }

    public function populatePrices(ProductVariantInterface $productVariant, ChannelInterface $channel): void
    {
        $channelPricing = $productVariant->getChannelPricingForChannel($channel);
        if (null === $channelPricing) {
            return;
        }

        $this->price = self::formatAmount($channelPricing->getPrice());
        $this->compareAtPrice = self::formatAmount($channelPricing->getOriginalPrice());
    }
}
