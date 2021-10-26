<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Psr\Container\ContainerInterface;
use Sylius\Component\Core\Model\OrderInterface;

class PlacedOrderProperties extends Properties
{
    /** @psalm-readonly */
    public ?string $event = 'Placed Order';

    public ?string $orderId = null;

    /** @var array<array-key, string> */
    public array $categories = [];

    /** @var array<array-key, string> */
    public array $itemNames = [];

    /** @var array<array-key, string> */
    public array $brands = [];

    public ?string $discountCode = null;

    public ?float $discountValue = null;

    /** @var array<array-key, Item> */
    public array $items = [];

    public Address $billingAddress;

    public Address $shippingAddress;

    public function __construct(ContainerInterface $serviceLocator, array $hydratableObjects = [])
    {
        parent::__construct($serviceLocator, $hydratableObjects);

        $this->billingAddress = new Address($this->serviceLocator);
        $this->shippingAddress = new Address($this->serviceLocator);
    }

    public function populateFromOrder(OrderInterface $order): void
    {
        $this->eventId = $order->getNumber();

        foreach ($order->getItems() as $item) {
            $this->items[] = $this->getPropertiesFactory()->create(Item::class, $item);
            $this->itemNames[] = (string) $item->getVariantName();
        }

        $billingAddress = $order->getBillingAddress();
        if (null !== $billingAddress) {
            $this->billingAddress = $this->getPropertiesFactory()->create(Address::class, $billingAddress);
        }
        $shippingAddress = $order->getShippingAddress();
        if (null !== $shippingAddress) {
            $this->billingAddress = $this->getPropertiesFactory()->create(Address::class, $shippingAddress);
        }

        $promotionCoupon = $order->getPromotionCoupon();
        if (null !== $promotionCoupon) {
            $this->discountCode = $promotionCoupon->getCode();
        }
    }
}
