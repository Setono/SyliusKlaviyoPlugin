<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Strategy;

use Setono\SyliusKlaviyoPlugin\Context\EmailContextInterface;
use Setono\SyliusKlaviyoPlugin\Context\ExchangeContextInterface;

final class TrackWithEmailOrExchangeTrackingStrategy implements TrackingStrategyInterface
{
    private EmailContextInterface $emailContext;

    private ExchangeContextInterface $exchangeContext;

    public function __construct(EmailContextInterface $emailContext, ExchangeContextInterface $exchangeContext)
    {
        $this->emailContext = $emailContext;
        $this->exchangeContext = $exchangeContext;
    }

    public function track(): bool
    {
        return $this->emailContext->getEmail() !== null || $this->exchangeContext->getExchange() !== null;
    }
}
