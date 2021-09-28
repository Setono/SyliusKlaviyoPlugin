<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

use Sylius\Component\Core\Model\AddressInterface;

class Address
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

    public static function createFromAddress(AddressInterface $address): self
    {
        $obj = new self();
        $obj->firstName = $address->getFirstName();
        $obj->lastName = $address->getLastName();
        $obj->company = $address->getCompany();
        $obj->address1 = $address->getStreet();
        $obj->zip = $address->getPostcode();
        $obj->city = $address->getCity();
        $obj->region = $address->getProvinceName();
        $obj->regionCode = $address->getProvinceCode();
        $obj->countryCode = $address->getCountryCode();
        $obj->phone = $address->getPhoneNumber();

        return $obj;
    }
}
