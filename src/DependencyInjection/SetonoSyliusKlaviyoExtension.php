<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusKlaviyoExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        /**
         * @psalm-suppress PossiblyNullArgument
         *
         * @var array{
         *     newsletter_checkbox: array{show_in_checkout: boolean},
         *     email_cookie_name: string,
         *     tracking_strategy: string,
         *     credentials: array{public_token: string, private_token: string},
         *     driver: string,
         *     resources: array<string, mixed>
         * } $config
         */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_sylius_klaviyo.email_cookie_name', $config['email_cookie_name']);
        $container->setParameter('setono_sylius_klaviyo.tracking_strategy', $config['tracking_strategy']);
        $container->setParameter('setono_sylius_klaviyo.public_token', $config['credentials']['public_token']);
        $container->setParameter('setono_sylius_klaviyo.private_token', $config['credentials']['private_token']);

        $this->registerResources('setono_sylius_klaviyo', $config['driver'], $config['resources'], $container);

        $loader->load('services.xml');

        if ($config['newsletter_checkbox']['show_in_checkout']) {
            $loader->load('services/conditional/form.xml');
        }
    }
}
