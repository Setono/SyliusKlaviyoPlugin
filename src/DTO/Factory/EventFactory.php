<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Factory;

use Setono\ClientId\Provider\ClientIdProviderInterface;
use Setono\SyliusKlaviyoPlugin\Context\EmailContextInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Event;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory\PropertiesFactoryInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Properties;
use Setono\SyliusKlaviyoPlugin\Strategy\TrackingStrategyInterface;

final class EventFactory implements EventFactoryInterface
{
    private ClientIdProviderInterface $clientIdProvider;

    private EmailContextInterface $emailContext;

    private TrackingStrategyInterface $trackingStrategy;

    private PropertiesFactoryInterface $propertiesFactory;

    public function __construct(
        ClientIdProviderInterface $clientIdProvider,
        EmailContextInterface $emailContext,
        TrackingStrategyInterface $trackingStrategy,
        PropertiesFactoryInterface $propertiesFactory
    ) {
        $this->clientIdProvider = $clientIdProvider;
        $this->emailContext = $emailContext;
        $this->trackingStrategy = $trackingStrategy;
        $this->propertiesFactory = $propertiesFactory;
    }

    public function create(Properties $properties, CustomerProperties $customerProperties = null): Event
    {
        if (null === $customerProperties) {
            $customerProperties = $this->propertiesFactory->create(CustomerProperties::class);
        }
        $event = new Event($properties, $customerProperties);

        // if we want to be able to track visitors without having an email, we need to provide our own id
        if ($this->trackingStrategy->track() && null === $this->emailContext->getEmail()) {
            $event->customerProperties->id = (string) $this->clientIdProvider->getClientId();
        }
        $event->customerProperties->email = $this->emailContext->getEmail();

        return $event;
    }
}
