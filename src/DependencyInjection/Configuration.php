<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_sylius_klaviyo');
        $rootNode = $treeBuilder->getRootNode();

        /**
         * @psalm-suppress MixedMethodCall
         * @psalm-suppress PossiblyUndefinedMethod
         */
        $rootNode
            ->children()
                ->scalarNode('public_token')
                    ->info('Your public token')
                    ->isRequired()
                    ->cannotBeEmpty()
        ;

        return $treeBuilder;
    }
}
