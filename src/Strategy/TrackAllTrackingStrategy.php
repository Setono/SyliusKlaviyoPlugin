<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Strategy;

final class TrackAllTrackingStrategy implements TrackingStrategyInterface
{
    public function track(): bool
    {
        return true;
    }
}
