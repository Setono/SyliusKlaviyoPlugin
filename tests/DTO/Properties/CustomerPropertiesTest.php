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
        $customerProperties = new CustomerProperties();
        $customerProperties->firstName = 'John';
        $customerProperties->lastName = 'Doe';
        $customerProperties->zip = '98612';
        $customerProperties->city = 'Portland';
        $customerProperties->region = 'Oregon';
        $customerProperties->country = 'US';
        $customerProperties->email = 'john.doe@klaviyo.com';
        $customerProperties->phoneNumber = '+1 (786) 123 1234';
        $customerProperties->image = 'https://example.com/john.doe.jpg';
        $customerProperties->consent[] = 'sms';

        return $customerProperties;
    }

    protected function getExpectedData(): array
    {
        return [
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
