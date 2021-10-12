<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

final class Item extends Base
{
    use MoneyFormatterTrait;

    public ?string $productId = null;

    public ?string $sku = null;

    public ?string $productName = null;

    public int $quantity = 1;

    public ?float $itemPrice = null;

    public ?float $rowTotal = null;

    public ?string $productUrl = null;

    public ?string $imageUrl = null;

    /** @var array<array-key, string> */
    public array $categories = [];

    public ?string $brand = null;

    public function populateFromOrderItem(OrderItemInterface $orderItem): void
    {
        $variant = $orderItem->getVariant();
        if (null !== $variant) {
            $this->productId = (string) $variant->getId();
            $this->sku = $variant->getCode();
            $this->productName = $variant->getName();
        }

        $product = $orderItem->getProduct();
        if (null !== $product) {
            foreach ($product->getTaxons() as $taxon) {
                $this->categories[] = (string) $taxon->getName();
            }

            // populate url
            $this->productUrl = $this->serviceLocator->get('router')->generate('sylius_shop_product_show', [
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

            $this->imageUrl = $this->serviceLocator->get('liip_imagine.cache.manager')->getBrowserPath(
                (string) $image->getPath(),
                'sylius_shop_product_large_thumbnail'
            );
        }

        $this->quantity = $orderItem->getQuantity();
        $this->itemPrice = self::formatAmount($orderItem->getUnitPrice());
        $this->rowTotal = $orderItem->getTotal();
    }
}
