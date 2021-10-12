<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\OrderItemInterface;

/**
 * Docs: https://help.klaviyo.com/hc/en-us/articles/115005082927#ordered-product8
 *
 * todo product url and image url not populated
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

    public function populateFromOrderItem(OrderItemInterface $orderItem): void
    {
        $this->value = $orderItem->getTotal();
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
            foreach ($product->getTaxons() as $taxon) {
                $this->categories[] = (string) $taxon->getName();
            }
        }
    }
}
