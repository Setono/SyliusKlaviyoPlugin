<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

use Webmozart\Assert\Assert;

final class Event
{
    /**
     * This property is automatically populated when you send the event to Klaviyo
     */
    public ?string $token = null;

    public string $event;

    public CustomerProperties $customerProperties;

    public Properties $properties;

    /**
     * The time the event happened (UNIX timestamp)
     */
    public int $timestamp;

    public function __construct(Properties $properties, CustomerProperties $customerProperties = null)
    {
        Assert::notNull($properties->event, 'You need to associate your properties with an event name, i.e. "Viewed Product"');

        $this->event = $properties->event;
        $this->customerProperties = $customerProperties ?? new CustomerProperties();
        $this->properties = $properties;
        $this->timestamp = time();
    }
}
