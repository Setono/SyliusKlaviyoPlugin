<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties;
use Tests\Setono\SyliusKlaviyoPlugin\DTO\DTOTestCase;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties
 */
final class CustomerPropertiesTest extends DTOTestCase
{
    protected function getDTO(): CustomerProperties
    {
        $properties = $this->propertiesFactory->create(CustomerProperties::class);
        $properties->exchangeId = 'exchange';
        $properties->firstName = 'John';
        $properties->lastName = 'Doe';
        $properties->zip = '98612';
        $properties->city = 'Portland';
        $properties->region = 'Oregon';
        $properties->country = 'US';
        $properties->email = 'john.doe@klaviyo.com';
        $properties->phoneNumber = '+1 (786) 123 1234';
        $properties->image = 'https://example.com/john.doe.jpg';
        $properties->consent[] = 'sms';

        return $properties;
    }

    protected function getExpectedData(): array
    {
        return [
            '$exchange_id' => 'exchange',
            '$email' => 'john.doe@klaviyo.com',
            '$first_name' => 'John',
            '$last_name' => 'Doe',
            '$phone_number' => '+1 (786) 123 1234',
            '$zip' => '98612',
            '$city' => 'Portland',
            '$region' => 'Oregon',
            '$country' => 'US',
            '$image' => 'https://example.com/john.doe.jpg',
            '$consent' => ['sms'],
        ];
    }
}
