<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\AddressInterface;

class Address extends Base
{
    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?string $company = null;

    public ?string $address1 = null;

    public ?string $address2 = null;

    public ?string $zip = null;

    public ?string $city = null;

    public ?string $region = null;

    public ?string $regionCode = null;

    public ?string $country = null;

    public ?string $countryCode = null;

    public ?string $phone = null;

    public function populateFromAddress(AddressInterface $address): void
    {
        $this->firstName = $address->getFirstName();
        $this->lastName = $address->getLastName();
        $this->company = $address->getCompany();
        $this->address1 = $address->getStreet();
        $this->zip = $address->getPostcode();
        $this->city = $address->getCity();
        $this->region = $address->getProvinceName();
        $this->regionCode = $address->getProvinceCode();
        $this->countryCode = $address->getCountryCode();
        $this->phone = $address->getPhoneNumber();
    }
}
