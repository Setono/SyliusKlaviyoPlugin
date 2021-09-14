<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO;

use Setono\SyliusKlaviyoPlugin\DTO\Item;
use Setono\SyliusKlaviyoPlugin\DTO\PlacedOrderProperties;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\DTO\PlacedOrderProperties
 */
final class PlacedOrderPropertiesTest extends DTOTestCase
{
    protected function getDTO(): PlacedOrderProperties
    {
        $properties = new PlacedOrderProperties();
        $properties->eventId = '1234';
        $properties->value = 29.98;
        $properties->orderId = '1234';
        $properties->categories = ['Fiction', 'Classics', 'Children'];
        $properties->itemNames = ['Winnie the Pooh', 'A Tale of Two Cities'];
        $properties->brands = ['Kids Books', 'Harcourt Classics'];
        $properties->discountCode = 'Free Shipping';
        $properties->discountValue = 5;

        $item = new Item();
        $item->productName = 'Winnie the Pooh';
        $properties->items[] = $item;

        $properties->billingAddress->firstName = 'John';
        $properties->shippingAddress->firstName = 'Mary';

        return $properties;
    }

    protected function getExpectedData(): array
    {
        return [
            '$event_id' => '1234',
            '$value' => 29.98,
            'OrderId' => '1234',
            'Categories' => ['Fiction', 'Classics', 'Children'],
            'ItemNames' => ['Winnie the Pooh', 'A Tale of Two Cities'],
            'Brands' => ['Kids Books', 'Harcourt Classics'],
            'DiscountCode' => 'Free Shipping',
            'DiscountValue' => 5.0,
            'Items' => [
                ['ProductName' => 'Winnie the Pooh', 'Quantity' => 1, 'Categories' => []]
            ],
            'BillingAddress' => [
                'FirstName' => 'John'
            ],
            'ShippingAddress' => [
                'FirstName' => 'Mary'
            ],
        ];
    }
}
