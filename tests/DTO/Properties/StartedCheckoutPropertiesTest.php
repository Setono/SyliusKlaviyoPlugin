<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\Item;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\StartedCheckoutProperties;
use Tests\Setono\SyliusKlaviyoPlugin\DTO\DTOTestCase;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\DTO\Properties\StartedCheckoutProperties
 */
final class StartedCheckoutPropertiesTest extends DTOTestCase
{
    protected function getDTO(): StartedCheckoutProperties
    {
        /** @var StartedCheckoutProperties $properties */
        $properties = $this->propertiesFactory->create(StartedCheckoutProperties::class);
        $properties->value = 29.98;
        $properties->eventId = '1000123_1387299423';
        $properties->itemNames = ['Winnie the Pooh', 'A Tale of Two Cities'];
        $properties->checkoutUrl = 'https://www.example.com/path/to/checkout';
        $properties->categories = ['Fiction', 'Children', 'Classics'];

        /** @var Item $item */
        $item = $this->propertiesFactory->create(Item::class);
        $item->productName = 'Winnie the Pooh';
        $properties->items[] = $item;

        return $properties;
    }

    protected function getExpectedData(): array
    {
        return [
            'ItemNames' => ['Winnie the Pooh', 'A Tale of Two Cities'],
            'CheckoutURL' => 'https://www.example.com/path/to/checkout',
            'Categories' => ['Fiction', 'Children', 'Classics'],
            'Items' => [
                ['ProductName' => 'Winnie the Pooh', 'Quantity' => 1, 'Categories' => []],
            ],
            '$event_id' => '1000123_1387299423',
            '$value' => 29.98,
        ];
    }
}
