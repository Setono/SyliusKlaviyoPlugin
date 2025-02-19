<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Event;

use Setono\SyliusKlaviyoPlugin\DTO\Event;

final class PropertiesArePopulatedEvent
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        public Event $event,
        /**
         * A context related to the event. This could for example contain the order related to the 'Placed Order' event.
         * See the relevant subscribers to check what context is available
         */
        public array $context = [],
    ) {
    }
}
