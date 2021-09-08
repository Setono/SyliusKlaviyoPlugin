<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

class ViewedProductProperties extends Properties
{
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
}
