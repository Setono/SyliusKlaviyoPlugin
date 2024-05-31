<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\AddressInterface;

class Location extends Base
{
    public ?string $address1 = null;

    public ?string $address2 = null;

    public ?string $zip = null;

    public ?string $city = null;

    public ?string $region = null;

    public ?string $country = null;

    public function populateFromAddress(AddressInterface $address): void
    {
        $this->address1 = $address->getStreet();
        $this->zip = $address->getPostcode();
        $this->city = $address->getCity();
        $this->region = $address->getProvinceCode();
        $this->country = $address->getCountryCode();
    }
}
