<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Strategy;

use Setono\SyliusKlaviyoPlugin\Context\EmailContextInterface;

final class TrackWithEmailTrackingStrategy implements TrackingStrategyInterface
{
    private EmailContextInterface $emailContext;

    public function __construct(EmailContextInterface $emailContext)
    {
        $this->emailContext = $emailContext;
    }

    public function track(): bool
    {
        return $this->emailContext->getEmail() !== null;
    }
}
