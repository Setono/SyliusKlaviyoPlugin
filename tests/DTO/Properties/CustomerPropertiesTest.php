<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Location;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Subscriptions;
use Tests\Setono\SyliusKlaviyoPlugin\DTO\DTOTestCase;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties
 */
final class CustomerPropertiesTest extends DTOTestCase
{
    protected function getDTO(): CustomerProperties
    {
        $properties = $this->propertiesFactory->create(CustomerProperties::class);
        $properties->firstName = 'John';
        $properties->lastName = 'Doe';
        $properties->location = $this->propertiesFactory->create(Location::class);
        $properties->location->zip = '98612';
        $properties->location->city = 'Portland';
        $properties->location->region = 'Oregon';
        $properties->location->country = 'US';
        $properties->email = 'john.doe@klaviyo.com';
        $properties->phoneNumber = '+1 (786) 123 1234';
        $properties->image = 'https://example.com/john.doe.jpg';
        $properties->subscriptions = $this->propertiesFactory->create(Subscriptions::class);
        $properties->subscriptions->smsMarketingSubscribe();

        return $properties;
    }

    protected function getExpectedData(): array
    {
        return [
            'type' => 'profile',
            'attributes' => [
                'phone_number' => '+1 (786) 123 1234',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'image' => 'https://example.com/john.doe.jpg',
                'location' => [
                  'city' => 'Portland',
                  'country' => 'US',
                  'region' => 'Oregon',
                  'zip' => '98612',
                ],
                'email' => 'john.doe@klaviyo.com',
            ],
        ];
    }
}
