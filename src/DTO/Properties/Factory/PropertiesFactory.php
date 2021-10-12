<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory;

use Psr\Container\ContainerInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Base;

final class PropertiesFactory implements PropertiesFactoryInterface
{
    private ContainerInterface $serviceLocator;

    public function __construct(ContainerInterface $serviceLocator)
    {
        $this->serviceLocator = $this->decorateServiceLocator($serviceLocator);
    }

    public function create(string $class, $hydratableObjects = []): Base
    {
        if (is_object($hydratableObjects)) {
            $hydratableObjects = [$hydratableObjects];
        }

        return new $class($this->serviceLocator, $hydratableObjects);
    }

    // todo should be service I guess...
    private function decorateServiceLocator(ContainerInterface $decorated): ContainerInterface
    {
        return new class($decorated, $this) implements ContainerInterface {
            private ContainerInterface $decorated;

            private PropertiesFactoryInterface $propertiesFactory;

            public function __construct(ContainerInterface $decorated, PropertiesFactoryInterface $propertiesFactory)
            {
                $this->decorated = $decorated;
                $this->propertiesFactory = $propertiesFactory;
            }

            public function get(string $id)
            {
                if ('setono_sylius_klaviyo.dto.properties.factory.properties' === $id) {
                    return $this->propertiesFactory;
                }

                return $this->decorated->get($id);
            }

            public function has(string $id): bool
            {
                return 'setono_sylius_klaviyo.dto.properties.factory.properties' === $id || $this->decorated->has($id);
            }
        };
    }
}
