<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Client;

use Setono\SyliusKlaviyoPlugin\DTO\Event;

interface TrackIdentifyClientInterface
{
    public function trackEvent(Event $event): void;
}
