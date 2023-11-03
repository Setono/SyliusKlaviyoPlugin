<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\BotDetector;

use DeviceDetector\Cache\PSR6Bridge;
use DeviceDetector\Parser\Bot as BotParser;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class BotDetector implements BotDetectorInterface
{
    private RequestStack $requestStack;

    private CacheItemPoolInterface $cache;

    public function __construct(RequestStack $requestStack, CacheItemPoolInterface $cache)
    {
        $this->requestStack = $requestStack;
        $this->cache = $cache;
    }

    public function isBot(string $userAgent = null): bool
    {
        if (null === $userAgent) {
            $request = $this->requestStack->getMainRequest();
            if (null === $request) {
                return false;
            }

            $userAgent = $request->headers->get('user-agent');
            if (null === $userAgent) {
                return false;
            }
        }

        $botParser = new BotParser();
        $botParser->setUserAgent($userAgent);
        $botParser->discardDetails();
        $botParser->setCache(new PSR6Bridge($this->cache));
        $result = $botParser->parse();

        return null !== $result;
    }
}
