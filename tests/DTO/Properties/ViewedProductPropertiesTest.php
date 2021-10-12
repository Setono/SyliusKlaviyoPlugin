<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\ViewedProductProperties;
use Tests\Setono\SyliusKlaviyoPlugin\DTO\DTOTestCase;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\DTO\Properties\ViewedProductProperties
 */
final class ViewedProductPropertiesTest extends DTOTestCase
{
    protected function getDTO(): ViewedProductProperties
    {
        $properties = new ViewedProductProperties();
        $properties->value = 100.50;
        $properties->eventId = '32f5ba0c-a869-42df-b5bc-56b9fd141f7d';
        $properties->productName = 'Black T-shirt';
        $properties->price = 123.95;
        $properties->compareAtPrice = 234.45;
        $properties->brand = 'Diesel';
        $properties->categories[] = 'T-shirts';
        $properties->url = 'https://example.com/t-shirts/black-t-shirt.html';
        $properties->imageUrl = 'https://example.com/images/black-t-shirt.jpg';
        $properties->sku = 'BLACK-T-SHIRT';

        return $properties;
    }

    protected function getExpectedData(): array
    {
        return [
            'ProductName' => 'Black T-shirt',
            'SKU' => 'BLACK-T-SHIRT',
            'Categories' => ['T-shirts'],
            'ImageURL' => 'https://example.com/images/black-t-shirt.jpg',
            'URL' => 'https://example.com/t-shirts/black-t-shirt.html',
            'Brand' => 'Diesel',
            'Price' => 123.95,
            'CompareAtPrice' => 234.45,
            '$event_id' => '32f5ba0c-a869-42df-b5bc-56b9fd141f7d',
            '$value' => 100.5,
        ];
    }
}
