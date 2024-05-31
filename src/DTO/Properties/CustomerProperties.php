<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\CustomerInterface;

class CustomerProperties extends Base
{
    public ?string $externalId = null;

    public ?string $email = null;

    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?string $phoneNumber = null;

    public ?string $organization = null;

    public ?string $title = null;

    public ?Location $location = null;

    public ?string $image = null;

    /** @var array<array-key, mixed> */
    public array $properties = [];

    public ?Subscriptions $subscriptions = null;

    public function populateFromCustomer(CustomerInterface $customer): void
    {
        /** @var AddressInterface|null $defaultAddress */
        $defaultAddress = $customer->getDefaultAddress() ?? $customer->getAddresses()->last() ?: null;

        $this->email = $customer->getEmailCanonical();
        $this->firstName = $customer->getFirstName();
        $this->lastName = $customer->getLastName();
        $this->phoneNumber = $customer->getPhoneNumber();

        if ($defaultAddress) {
            $this->location = $this->getPropertiesFactory()->create(Location::class, $defaultAddress);
        }

        if ($customer->isSubscribedToNewsletter()) {
            $this->subscriptions = $this->getPropertiesFactory()->create(Subscriptions::class);
            $this->subscriptions->emailMarketingSubscribe();
        }
    }
}
