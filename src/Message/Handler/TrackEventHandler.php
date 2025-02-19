<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Message\Handler;

use Setono\SyliusKlaviyoPlugin\Client\TrackIdentifyClientInterface;
use Setono\SyliusKlaviyoPlugin\Message\Command\TrackEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class TrackEventHandler
{
    public function __construct(private readonly TrackIdentifyClientInterface $client)
    {
    }

    public function __invoke(TrackEvent $message): void
    {
        $this->client->trackEvent($message->getEvent());
    }
}
