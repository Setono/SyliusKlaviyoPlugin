<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

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

    public function __construct()
    {
        $this->billingAddress = new Address();
        $this->shippingAddress = new Address();
    }
}
