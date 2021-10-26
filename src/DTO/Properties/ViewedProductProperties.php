<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ImageInterface;
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

        // populate url
        $this->url = $this->getUrlGenerator()->generate('sylius_shop_product_show', [
            'slug' => $product->getSlug(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        // populate image url
        $images = $product->getImages();

        if ($images->count() === 0) {
            return;
        }

        /** @var ImageInterface|mixed $image */
        $image = $images->first();
        Assert::isInstanceOf($image, ImageInterface::class);

        $this->imageUrl = $this->getCacheManager()
            ->getBrowserPath((string) $image->getPath(), 'sylius_shop_product_large_thumbnail')
        ;

        /**
         * Populate prices
         *
         * @var ProductVariantInterface|null $productVariant
         */
        $productVariant = $this->getProductVariantResolver()->getVariant($product);
        if (null !== $productVariant) {
            /** @var ChannelInterface $channel */
            $channel = $this->getChannelContext()->getChannel();
            $channelPricing = $productVariant->getChannelPricingForChannel($channel);
            if (null !== $channelPricing) {
                $this->price = self::formatAmount($channelPricing->getPrice());
                $this->compareAtPrice = self::formatAmount($channelPricing->getOriginalPrice());
            }
        }
    }
}
