<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

trait MoneyFormatterTrait
{
    protected static function formatAmount(?int $amount): ?float
    {
        if (null === $amount) {
            return null;
        }

        return round($amount / 100, 2);
    }
}
