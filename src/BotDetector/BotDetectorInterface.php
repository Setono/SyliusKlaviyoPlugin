<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\BotDetector;

interface BotDetectorInterface
{
    /**
     * If the $userAgent is null, this method uses the request stack to retrieve the main request
     */
    public function isBot(string $userAgent = null): bool;
}
