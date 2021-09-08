<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

use Setono\SyliusKlaviyoPlugin\DTO\AddedToCart\Item;

class AddedToCartProperties extends Properties
{
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
}
