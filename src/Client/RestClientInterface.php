<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Client;

interface RestClientInterface
{
    /**
     * @param string $endpoint i.e. 'lists'
     */
    public function get(string $endpoint): array;
}
