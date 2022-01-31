<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Setono\SyliusKlaviyoPlugin\Context\ExchangeContextInterface;
use Setono\SyliusKlaviyoPlugin\Event\PropertiesArePopulatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UpdateCustomerPropertiesWithExchangeSubscriber implements EventSubscriberInterface
{
    private ExchangeContextInterface $exchangeContext;

    public function __construct(ExchangeContextInterface $exchangeContext)
    {
        $this->exchangeContext = $exchangeContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PropertiesArePopulatedEvent::class => 'update',
        ];
    }

    public function update(PropertiesArePopulatedEvent $event): void
    {
        $exchange = $this->exchangeContext->getExchange();
        if (null === $exchange) {
            return;
        }

        $event->event->customerProperties->exchangeId = $exchange;
    }
}
