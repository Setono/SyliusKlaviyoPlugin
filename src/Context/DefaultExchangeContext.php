<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

final class DefaultExchangeContext implements ExchangeContextInterface
{
    public function getExchange(): ?string
    {
        return null;
    }
}
