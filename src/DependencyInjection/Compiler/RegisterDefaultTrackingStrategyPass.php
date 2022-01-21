<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webmozart\Assert\Assert;

final class RegisterDefaultTrackingStrategyPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('setono_sylius_klaviyo.tracking_strategy')) {
            return;
        }

        /** @var string $trackingStrategy */
        $trackingStrategy = $container->getParameter('setono_sylius_klaviyo.tracking_strategy');
        $trackingStrategyServiceId = null;

        /**
         * @var string $id
         */
        foreach ($container->findTaggedServiceIds('setono_sylius_klaviyo.tracking_strategy') as $id => $tags) {
            /** @var mixed $tag */
            foreach ($tags as $tag) {
                Assert::isArray($tag);
                Assert::keyExists($tag, 'strategy-name');

                /** @var mixed $name */
                $name = $tag['strategy-name'];
                Assert::string($name);

                if ($name === $trackingStrategy) {
                    $trackingStrategyServiceId = $id;
                }
            }
        }

        if (null === $trackingStrategyServiceId) {
            throw new \RuntimeException(sprintf('No tracking strategy defined with the name "%s"', $trackingStrategy));
        }

        $container->setAlias('setono_sylius_klaviyo.strategy.default_tracking_strategy', $trackingStrategyServiceId);
    }
}
