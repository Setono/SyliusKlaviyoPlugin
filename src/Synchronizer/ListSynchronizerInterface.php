<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Synchronizer;

use Psr\Log\LoggerAwareInterface;

interface ListSynchronizerInterface extends LoggerAwareInterface
{
    public function synchronize(): void;
}
