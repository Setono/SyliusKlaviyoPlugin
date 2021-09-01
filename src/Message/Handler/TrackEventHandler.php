<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Message\Handler;

use Setono\SyliusKlaviyoPlugin\Message\Command\TrackEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class TrackEventHandler implements MessageHandlerInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke(TrackEvent $message): void
    {
    }
}
