<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\AddedToCartProperties;
use Tests\Setono\SyliusKlaviyoPlugin\DTO\DTOTestCase;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\DTO\Properties\AddedToCartProperties
 */
final class AddedToCartPropertiesTest extends DTOTestCase
{
    protected function getDTO(): AddedToCartProperties
    {
        $properties = new AddedToCartProperties();
        $properties->addedItemProductName = 'A Tale of Two Cities';
        $properties->addedItemProductId = '1112';
        $properties->addedItemSku = 'TALEOFTWO';
        $properties->addedItemCategories = ['Fiction', 'Classics'];
        $properties->addedItemImageUrl = 'https://example.com/images/tale-of-two.jpg';
        $properties->addedItemPrice = 19.99;
        $properties->itemNames = ['Winnie the Pooh', 'A Tale of Two Cities'];
        $properties->checkoutUrl = 'https://example.com/checkout';

        return $properties;
    }

    protected function getExpectedData(): array
    {
        return [
            'AddedItemProductName' => 'A Tale of Two Cities',
            'AddedItemProductID' => '1112',
            'AddedItemSKU' => 'TALEOFTWO',
            'AddedItemCategories' => ['Fiction', 'Classics'],
            'AddedItemImageURL' => 'https://example.com/images/tale-of-two.jpg',
            'AddedItemPrice' => 19.99,
            'AddedItemQuantity' => 1,
            'ItemNames' => ['Winnie the Pooh', 'A Tale of Two Cities'],
            'CheckoutURL' => 'https://example.com/checkout',
            'Items' => [],
        ];
    }
}
