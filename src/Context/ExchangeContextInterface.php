<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

interface ExchangeContextInterface
{
    /**
     * Returns the exchange id of the visitor if possible, else it returns null
     *
     * @return non-empty-string|null
     */
    public function getExchange(): ?string;
}
