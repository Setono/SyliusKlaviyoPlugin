<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Strategy;

use Setono\SyliusKlaviyoPlugin\Context\EmailContextInterface;
use Setono\SyliusKlaviyoPlugin\Context\ExchangeContextInterface;

final class TrackWithEmailOrExchangeTrackingStrategy implements TrackingStrategyInterface
{
    public function __construct(private readonly EmailContextInterface $emailContext, private readonly ExchangeContextInterface $exchangeContext)
    {
    }

    public function track(): bool
    {
        return $this->emailContext->getEmail() !== null || $this->exchangeContext->getExchange() !== null;
    }
}
