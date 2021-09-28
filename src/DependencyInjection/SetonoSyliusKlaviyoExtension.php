<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusKlaviyoExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        /**
         * @psalm-suppress PossiblyNullArgument
         *
         * @var array{credentials: array{public_token: string, private_token: string}} $config
         */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_sylius_klaviyo.public_token', $config['credentials']['public_token']);
        $container->setParameter('setono_sylius_klaviyo.private_token', $config['credentials']['private_token']);

        $loader->load('services.xml');
    }
}
