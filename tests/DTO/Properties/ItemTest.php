<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\Item;
use Tests\Setono\SyliusKlaviyoPlugin\DTO\DTOTestCase;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\DTO\Properties\Item
 */
final class ItemTest extends DTOTestCase
{
    protected function getDTO(): Item
    {
        $properties = $this->propertiesFactory->create(Item::class);
        $properties->productId = '1111';
        $properties->sku = 'WINNIEPOOH';
        $properties->productName = 'Winnie the Pooh';
        $properties->itemPrice = 9.99;
        $properties->rowTotal = 9.99;
        $properties->productUrl = 'https://example.com/winnie-the-pooh';
        $properties->imageUrl = 'https://example.com/images/winnie-the-pooh.jpg';
        $properties->categories = ['Fiction', 'Children'];
        $properties->brand = 'Kids Books';

        return $properties;
    }

    protected function getExpectedData(): array
    {
        return [
            'ProductID' => '1111',
            'ProductName' => 'Winnie the Pooh',
            'SKU' => 'WINNIEPOOH',
            'Categories' => ['Fiction', 'Children'],
            'ImageURL' => 'https://example.com/images/winnie-the-pooh.jpg',
            'ProductURL' => 'https://example.com/winnie-the-pooh',
            'Brand' => 'Kids Books',
            'ItemPrice' => 9.99,
            'RowTotal' => 9.99,
            'Quantity' => 1,
        ];
    }
}
