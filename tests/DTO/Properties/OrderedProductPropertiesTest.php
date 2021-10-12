<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\OrderedProductProperties;
use Tests\Setono\SyliusKlaviyoPlugin\DTO\DTOTestCase;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\DTO\Properties\OrderedProductProperties
 */
final class OrderedProductPropertiesTest extends DTOTestCase
{
    protected function getDTO(): \Setono\SyliusKlaviyoPlugin\DTO\Properties\OrderedProductProperties
    {
        $properties = new OrderedProductProperties();
        $properties->eventId = '1234';
        $properties->value = 29.98;
        $properties->orderId = '1234';
        $properties->productId = '1111';
        $properties->sku = 'WINNIEPOOH';
        $properties->productName = 'Winnie the Pooh';
        $properties->quantity = 1;
        $properties->productUrl = 'http://www.example.com/path/to/product';
        $properties->imageUrl = 'http://www.example.com/path/to/product/image.png';
        $properties->categories = ['Fiction', 'Children'];
        $properties->productBrand = 'Kids Books';

        return $properties;
    }

    protected function getExpectedData(): array
    {
        return [
            '$event_id' => '1234',
            '$value' => 29.98,
            'OrderId' => '1234',
            'ProductID' => '1111',
            'SKU' => 'WINNIEPOOH',
            'ProductName' => 'Winnie the Pooh',
            'Quantity' => 1,
            'ProductURL' => 'http://www.example.com/path/to/product',
            'ImageURL' => 'http://www.example.com/path/to/product/image.png',
            'Categories' => ['Fiction', 'Children'],
            'ProductBrand' => 'Kids Books',
        ];
    }
}
