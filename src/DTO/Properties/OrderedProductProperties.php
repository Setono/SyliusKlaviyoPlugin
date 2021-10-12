<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\OrderItemInterface;

/**
 * Docs: https://help.klaviyo.com/hc/en-us/articles/115005082927#ordered-product8
 */
class OrderedProductProperties extends Properties
{
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

    public static function createFromOrderItem(OrderItemInterface $orderItem): self
    {
        $obj = new self();
        $obj->value = $orderItem->getTotal();
        $obj->quantity = $orderItem->getQuantity();

        $order = $orderItem->getOrder();
        if (null !== $order) {
            $obj->orderId = $order->getNumber();
        }

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

        return $obj;
    }
}
