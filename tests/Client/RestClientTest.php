<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\Client;

use PHPUnit\Framework\TestCase;
use Setono\SyliusKlaviyoPlugin\Client\RestClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\Client\RestClient
 */
final class RestClientTest extends TestCase
{
    private bool $live = false;

    private string $token = 'private_token';

    protected function setUp(): void
    {
        $live = (bool) getenv('KLAVIYO_LIVE');
        if (false !== $live) {
            $this->live = true;

            $token = getenv('KLAVIYO_PRIVATE_TOKEN');
            if (false !== $token) {
                $this->token = $token;
            }
        }
    }

    /**
     * @test
     */
    public function it_gets_lists(): void
    {
        $response = new MockResponse('[{"list_id":"SaCTqE","list_name":"Your newsletter"}]');
        $client = new RestClient($this->getHttpClient($response), 'https://a.klaviyo.com/api/v2', $this->token);
        $lists = $client->get('lists');

        if (!$this->live) {
            self::assertCount(1, $lists);
        }

        foreach ($lists as $list) {
            self::assertIsArray($list);
            self::assertArrayHasKey('list_id', $list);
            self::assertIsString($list['list_id']);
            self::assertArrayHasKey('list_name', $list);
            self::assertIsString($list['list_name']);

            if (!$this->live) {
                self::assertSame('SaCTqE', $list['list_id']);
                self::assertSame('Your newsletter', $list['list_name']);
            }
        }
    }

    private function getHttpClient(MockResponse $response): HttpClientInterface
    {
        if ($this->live) {
            return HttpClient::create();
        }

        return new MockHttpClient($response);
    }
}
