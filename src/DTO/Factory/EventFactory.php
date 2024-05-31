<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Factory;

use Setono\ClientId\Provider\ClientIdProviderInterface;
use Setono\SyliusKlaviyoPlugin\Context\EmailContextInterface;
use Setono\SyliusKlaviyoPlugin\Context\ExchangeContextInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Event;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory\PropertiesFactoryInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Properties;
use Setono\SyliusKlaviyoPlugin\Strategy\TrackingStrategyInterface;

final class EventFactory implements EventFactoryInterface
{
    private ClientIdProviderInterface $clientIdProvider;

    private EmailContextInterface $emailContext;

    private ExchangeContextInterface $exchangeContext;

    private TrackingStrategyInterface $trackingStrategy;

    private PropertiesFactoryInterface $propertiesFactory;

    public function __construct(
        ClientIdProviderInterface $clientIdProvider,
        EmailContextInterface $emailContext,
        ExchangeContextInterface $exchangeContext,
        TrackingStrategyInterface $trackingStrategy,
        PropertiesFactoryInterface $propertiesFactory
    ) {
        $this->clientIdProvider = $clientIdProvider;
        $this->emailContext = $emailContext;
        $this->exchangeContext = $exchangeContext;
        $this->trackingStrategy = $trackingStrategy;
        $this->propertiesFactory = $propertiesFactory;
    }

    public function create(Properties $properties, CustomerProperties $customerProperties = null): Event
    {
        if (null === $customerProperties) {
            $customerProperties = $this->propertiesFactory->create(CustomerProperties::class);
        }
        $event = new Event($properties, $customerProperties);

        // if we want to be able to track visitors without having an email or an exchange, we need to provide our own id
        if ($this->trackingStrategy->track() && null === $this->emailContext->getEmail() && null === $this->exchangeContext->getExchange()) {
            $event->customerProperties->externalId = (string) $this->clientIdProvider->getClientId();
        }
        $event->customerProperties->email = $this->emailContext->getEmail();

        return $event;
    }
}
