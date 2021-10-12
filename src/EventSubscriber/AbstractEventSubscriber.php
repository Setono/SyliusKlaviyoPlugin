<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Factory\EventFactoryInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory\PropertiesFactoryInterface;
use Setono\SyliusKlaviyoPlugin\Strategy\TrackingStrategyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractEventSubscriber implements EventSubscriberInterface
{
    protected MessageBusInterface $commandBus;

    protected EventFactoryInterface $eventFactory;

    protected PropertiesFactoryInterface $propertiesFactory;

    protected EventDispatcherInterface $eventDispatcher;

    protected TrackingStrategyInterface $trackingStrategy;

    public function __construct(
        MessageBusInterface $commandBus,
        EventFactoryInterface $eventFactory,
        PropertiesFactoryInterface $propertiesFactory,
        EventDispatcherInterface $eventDispatcher,
        TrackingStrategyInterface $trackingStrategy
    ) {
        $this->commandBus = $commandBus;
        $this->eventFactory = $eventFactory;
        $this->propertiesFactory = $propertiesFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->trackingStrategy = $trackingStrategy;
    }
}
