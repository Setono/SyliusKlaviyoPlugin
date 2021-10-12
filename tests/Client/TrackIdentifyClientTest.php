<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\Client;

use PHPUnit\Framework\TestCase;
use Setono\SyliusKlaviyoPlugin\Client\TrackIdentifyClient;
use Setono\SyliusKlaviyoPlugin\DTO\Event;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\ViewedProductProperties;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\LoaderChain;
use Symfony\Component\Serializer\Mapping\Loader\XmlFileLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\Client\TrackIdentifyClient
 */
final class TrackIdentifyClientTest extends TestCase
{
    private bool $live = false;

    private string $token = 'public_token';

    protected function setUp(): void
    {
        $live = (bool) getenv('KLAVIYO_LIVE');
        if (false !== $live) {
            $this->live = true;

            $token = getenv('KLAVIYO_PUBLIC_TOKEN');
            if (false !== $token) {
                $this->token = $token;
            }
        }
    }

    /**
     * @test
     */
    public function it_tracks_event(): void
    {
        $eventProperties = new ViewedProductProperties(new Container());
        $eventProperties->eventId = 'event_id';
        $eventProperties->productName = 'Black T-shirt';
        $eventProperties->price = 123.95;
        $eventProperties->compareAtPrice = 234.45;
        $eventProperties->brand = 'Diesel';
        $eventProperties->categories[] = 'T-shirts';
        $eventProperties->url = 'https://example.com/t-shirts/black-t-shirt.html';
        $eventProperties->imageUrl = 'https://example.com/images/black-t-shirt.jpg';
        $eventProperties->sku = 'BLACK-T-SHIRT';

        $customerProperties = new CustomerProperties(new Container());
        $customerProperties->email = 'test@klaviyo.com';

        $event = new Event($eventProperties, $customerProperties);
        $event->timestamp = 1631101604;

        $response = new MockResponse('1');
        $client = new TrackIdentifyClient($this->getHttpClient($response), self::getSerializer(), 'https://a.klaviyo.com/api', $this->token);
        $client->trackEvent($event);

        if ($this->live) {
            self::assertTrue(true);
        } else {
            $requestOptions = $response->getRequestOptions();
            Assert::keyExists($requestOptions, 'headers');
            Assert::keyExists($requestOptions, 'body');
            Assert::string($requestOptions['body']);

            $requestHeaders = $requestOptions['headers'];
            Assert::isArray($requestHeaders);

            $requestBody = urldecode($requestOptions['body']);

            self::assertSame('POST', $response->getRequestMethod());
            self::assertSame('https://a.klaviyo.com/api/track', $response->getRequestUrl());
            self::assertContains('Content-Type: application/x-www-form-urlencoded', $requestHeaders);
            self::assertSame('data={"token":"public_token","event":"Viewed Product","customer_properties":{"$email":"test@klaviyo.com","$consent":[]},"properties":{"ProductName":"Black T-shirt","SKU":"BLACK-T-SHIRT","Categories":["T-shirts"],"ImageURL":"https:\/\/example.com\/images\/black-t-shirt.jpg","URL":"https:\/\/example.com\/t-shirts\/black-t-shirt.html","Brand":"Diesel","Price":123.95,"CompareAtPrice":234.45,"$event_id":"event_id"},"time":1631101604}', $requestBody);
        }
    }

    private function getHttpClient(MockResponse $response): HttpClientInterface
    {
        if ($this->live) {
            return HttpClient::create();
        }

        return new MockHttpClient($response);
    }

    private static function getSerializer(): SerializerInterface
    {
        $classMetadataFactory = new ClassMetadataFactory(new LoaderChain([
            new XmlFileLoader(__DIR__ . '/../../src/Resources/config/serialization/CustomerProperties.xml'),
            new XmlFileLoader(__DIR__ . '/../../src/Resources/config/serialization/Event.xml'),
            new XmlFileLoader(__DIR__ . '/../../src/Resources/config/serialization/Properties.xml'),
            new XmlFileLoader(__DIR__ . '/../../src/Resources/config/serialization/ViewedProductProperties.xml'),
        ]));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)];

        return new Serializer($normalizers, $encoders);
    }
}
