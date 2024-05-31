<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

class Subscriptions extends Base
{
    const CONSENT_SUBSCRIBED = 'SUBSCRIBED';

    public ?array $email = null;

    public ?array $sms = null;

    public function emailMarketingSubscribe(): void
    {
        $this->addEmailConsent('marketing', self::CONSENT_SUBSCRIBED);
    }

    private function addEmailConsent(string $type, string $value): void
    {
        $this->email[$type] = ['consent' => $value];
    }
}
