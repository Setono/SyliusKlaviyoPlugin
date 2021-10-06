<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Factory;

use Setono\ClientId\Provider\ClientIdProviderInterface;
use Setono\SyliusKlaviyoPlugin\Context\EmailContextInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Event;
use Setono\SyliusKlaviyoPlugin\DTO\Properties;
use Setono\SyliusKlaviyoPlugin\Strategy\TrackingStrategyInterface;

final class EventFactory implements EventFactoryInterface
{
    private ClientIdProviderInterface $clientIdProvider;

    private EmailContextInterface $emailContext;

    private TrackingStrategyInterface $trackingStrategy;

    public function __construct(ClientIdProviderInterface $clientIdProvider, EmailContextInterface $emailContext, TrackingStrategyInterface $trackingStrategy)
    {
        $this->clientIdProvider = $clientIdProvider;
        $this->emailContext = $emailContext;
        $this->trackingStrategy = $trackingStrategy;
    }

    public function create(Properties $properties): Event
    {
        $event = new Event($properties);

        // if we want to be able to track visitors without having an email, we need to provide our own id
        if ($this->trackingStrategy->track() && null === $this->emailContext->getEmail()) {
            $event->customerProperties->id = (string) $this->clientIdProvider->getClientId();
        }
        $event->customerProperties->email = $this->emailContext->getEmail();

        return $event;
    }
}
