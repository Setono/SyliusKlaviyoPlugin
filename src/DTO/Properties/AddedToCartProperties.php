<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

class AddedToCartProperties extends Properties
{
    use MoneyFormatterTrait;

    /** @psalm-readonly */
    public ?string $event = 'Added to Cart';

    public ?string $addedItemProductName = null;

    public ?string $addedItemProductId = null;

    public ?string $addedItemSku = null;

    /** @var array<array-key, string> */
    public array $addedItemCategories = [];

    public ?string $addedItemImageUrl = null;

    public ?string $addedItemUrl = null;

    public ?float $addedItemPrice = null;

    public int $addedItemQuantity = 1;

    /** @var array<array-key, string> */
    public array $itemNames = [];

    public ?string $checkoutUrl = null;

    /** @var array<array-key, Item> */
    public array $items = [];

    public function populateFromOrderItem(OrderItemInterface $orderItem): void
    {
        $variant = $orderItem->getVariant();
        $product = $orderItem->getProduct();

        // because of this bug: https://github.com/Sylius/Sylius/issues/9407 we can't just get the order from the order item
        $order = $this->getCartContext()->getCart();

        $this->addedItemProductName = $orderItem->getVariantName();
        $this->addedItemPrice = self::formatAmount($orderItem->getFullDiscountedUnitPrice());
        $this->addedItemQuantity = $orderItem->getQuantity();

        if (null !== $variant) {
            $this->addedItemProductId = (string) $variant->getId();
            $this->addedItemSku = $variant->getCode();
        }

        if (null !== $product) {
            $mainTaxon = $product->getMainTaxon();
            if (null !== $mainTaxon) {
                $this->addedItemCategories[] = (string) $mainTaxon->getName();
            }

            foreach ($product->getTaxons() as $taxon) {
                $this->addedItemCategories[] = (string) $taxon->getName();
            }

            $this->addedItemCategories = array_unique($this->addedItemCategories);

            // populate product url
            $this->addedItemUrl = $this->getUrlGenerator()->generate('sylius_shop_product_show', [
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

            $this->addedItemImageUrl = $this->getCacheManager()->getBrowserPath(
                (string) $image->getPath(),
                'sylius_shop_product_large_thumbnail'
            );
        }

        /** @var OrderItemInterface $item */
        foreach ($order->getItems() as $item) {
            $this->itemNames[] = (string) $item->getVariantName();
            $this->items[] = $this->getPropertiesFactory()->create(Item::class, $item);
        }
    }
}
