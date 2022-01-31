<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Webmozart\Assert\Assert;

/**
 * Docs: https://help.klaviyo.com/hc/en-us/articles/115005082927#ordered-product8
 */
class OrderedProductProperties extends Properties
{
    use MoneyFormatterTrait;

    use TaxonTrait;

    /** @psalm-readonly */
    public ?string $event = 'Ordered Product';

    public ?string $orderId = null;

    public ?string $productId = null;

    public ?string $sku = null;

    public ?string $productName = null;

    public int $quantity = 1;

    public ?string $productUrl = null;

    public ?string $imageUrl = null;

    /** @var array<array-key, string> */
    public array $categories = [];

    public ?string $productBrand = null;

    public function populateFromOrderItem(OrderItemInterface $orderItem): void
    {
        $this->value = self::formatAmount($orderItem->getTotal());
        $this->quantity = $orderItem->getQuantity();

        $order = $orderItem->getOrder();
        if (null !== $order) {
            $this->orderId = $order->getNumber();
        }

        $variant = $orderItem->getVariant();
        if (null !== $variant) {
            $this->productId = (string) $variant->getId();
            $this->sku = $variant->getCode();
            $this->productName = $variant->getName();
        }

        $product = $orderItem->getProduct();
        if (null !== $product) {
            $this->categories = self::getTaxonsFromProduct($product);

            // populate url
            $this->productUrl = $this->getUrlGenerator()->generate('sylius_shop_product_show', [
                'slug' => $product->getSlug(),
            ]);

            // populate image url
            $images = $product->getImages();

            if ($images->count() !== 0) {
                /** @var ImageInterface|mixed $image */
                $image = $images->first();
                Assert::isInstanceOf($image, ImageInterface::class);

                $this->imageUrl = $this->getCacheManager()
                    ->getBrowserPath((string) $image->getPath(), 'sylius_shop_product_large_thumbnail')
                ;
            }
        }
    }
}
