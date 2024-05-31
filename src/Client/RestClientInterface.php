<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Client;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface RestClientInterface
{
    /**
     * @param string $endpoint i.e. 'lists'
     */
    public function get(string $endpoint): ResponseInterface;

    /**
     * @param string $endpoint i.e. 'list/WifVt/subscribe'
     */
    public function post(string $endpoint, array $data): ResponseInterface;
}
