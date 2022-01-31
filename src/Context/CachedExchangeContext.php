<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

final class CachedExchangeContext implements ExchangeContextInterface
{
    private ExchangeContextInterface $decorated;

    private ?string $exchange = '';

    public function __construct(ExchangeContextInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function getExchange(): ?string
    {
        if ('' === $this->exchange) {
            $this->exchange = $this->decorated->getExchange();
        }

        return $this->exchange;
    }
}
