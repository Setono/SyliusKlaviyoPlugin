<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

class ViewedProductEvent extends Event
{
    public ?string $ProductName = null;

    public ?string $ProductID = null;

    public ?string $SKU = null;

    public array $Categories = [];

    public ?string $ImageURL = null;

    public ?string $URL = null;

    public ?string $Brand = null;

    public ?float $Price = null;

    public ?float $CompareAtPrice = null;

    public function __construct()
    {
        parent::__construct('Viewed Product');
    }
}
