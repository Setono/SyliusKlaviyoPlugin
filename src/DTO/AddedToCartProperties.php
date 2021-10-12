<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

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
        }
    }

    public function populateFromOrder(OrderInterface $order): void
    {
        foreach ($order->getItems() as $item) {
            $this->itemNames[] = (string) $item->getVariantName();
            $this->items[] = Item::createFromOrderItem($item);
        }
    }
}
