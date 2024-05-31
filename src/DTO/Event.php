<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Properties;
use Webmozart\Assert\Assert;

final class Event
{
    public string $event;

    public CustomerProperties $customerProperties;

    public Properties $properties;

    /**
     * The time the event happened (UNIX timestamp)
     */
    public int $timestamp;

    public function __construct(Properties $properties, CustomerProperties $customerProperties)
    {
        Assert::notNull($properties->event, 'You need to associate your properties with an event name, i.e. "Viewed Product"');

        $now = new \DateTimeImmutable();

        $this->event = $properties->event;
        $this->customerProperties = $customerProperties;
        $this->properties = $properties;
        $this->timestamp = $now->getTimestamp();

        if (null === $this->properties->eventId) {
            // See the documentation for event id here: https://help.klaviyo.com/hc/en-us/articles/115000751052-Klaviyo-API-Reference-Guide#reserved-event-properties11
            // Instead of Klaviyo setting the event id to the timestamp, we instead set it to the timestamp including microseconds.
            // This will make it less likely to clash with other events
            $this->properties->eventId = $now->format('U.u');
        }
    }
}
