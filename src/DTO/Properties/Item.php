<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\OrderItemInterface;

// todo the product url and image url is not set inside this class
final class Item
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

    public static function createFromOrderItem(OrderItemInterface $orderItem): self
    {
        $obj = new self();

        $variant = $orderItem->getVariant();
        if (null !== $variant) {
            $obj->productId = (string) $variant->getId();
            $obj->sku = $variant->getCode();
            $obj->productName = $variant->getName();
        }

        $product = $orderItem->getProduct();
        if (null !== $product) {
            foreach ($product->getTaxons() as $taxon) {
                $obj->categories[] = (string) $taxon->getName();
            }
        }

        $obj->quantity = $orderItem->getQuantity();
        $obj->itemPrice = self::formatAmount($orderItem->getUnitPrice());
        $obj->rowTotal = $orderItem->getTotal();

        return $obj;
    }
}
