<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

class CustomerProperties
{
    public ?string $email = null;

    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?string $phoneNumber = null;

    public ?string $zip = null;

    public ?string $city = null;

    public ?string $region = null;

    public ?string $country = null;

    public ?string $image = null;

    /** @var array<array-key, string> */
    public array $consent = [];
}
