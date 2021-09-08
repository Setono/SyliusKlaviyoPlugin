<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

final class Item
{
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
}
