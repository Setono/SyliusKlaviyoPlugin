<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

final class CachedExchangeContext implements ExchangeContextInterface
{
    private ?string $exchange = '';

    public function __construct(private readonly ExchangeContextInterface $decorated)
    {
    }

    public function getExchange(): ?string
    {
        if ('' === $this->exchange) {
            $this->exchange = $this->decorated->getExchange();
        }

        return $this->exchange;
    }
}
