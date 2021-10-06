<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Client;

interface RestClientInterface
{
    /**
     * @param string $endpoint i.e. 'lists'
     */
    public function get(string $endpoint): array;

    /**
     * @param string $endpoint i.e. 'list/WifVt/subscribe'
     */
    public function post(string $endpoint, array $data): array;
}
