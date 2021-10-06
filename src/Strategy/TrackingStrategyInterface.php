<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Strategy;

interface TrackingStrategyInterface
{
    /**
     * Returns true if we should track the user
     */
    public function track(): bool;
}
