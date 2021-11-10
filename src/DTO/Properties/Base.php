<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Psr\Container\ContainerInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory\PropertiesFactoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @psalm-consistent-constructor
 */
abstract class Base
{
    protected ContainerInterface $serviceLocator;

    /**
     * @param array<array-key, object> $hydratableObjects
     */
    public function __construct(ContainerInterface $serviceLocator, array $hydratableObjects = [])
    {
        $this->serviceLocator = $serviceLocator;

        $refl = new \ReflectionClass($this);
        foreach ($refl->getMethods() as $method) {
            if (strpos($method->getName(), 'populate') !== 0) {
                continue;
            }

            // we cannot call private methods in the context of a parent
            if ($method->isPrivate()) {
                continue;
            }

            $parameters = $method->getParameters();
            if (count($parameters) > 1) {
                continue;
            }

            /** @var \ReflectionParameter $parameter */
            $parameter = current($parameters);

            if (!$parameter->hasType()) {
                continue;
            }

            $parameterType = $parameter->getType();
            if (!$parameterType instanceof \ReflectionNamedType) {
                continue;
            }

            foreach ($hydratableObjects as $hydratableObject) {
                /** @psalm-suppress ArgumentTypeCoercion */
                if (!is_a($hydratableObject, $parameterType->getName(), true)) {
                    continue;
                }

                $this->{$method->getName()}($hydratableObject);
            }
        }
    }

    /**
     * todo create data mappers instead so that we don't have services inside DTOs
     */
    public function __serialize(): array
    {
        /** @var array<string, mixed> $res */
        $res = [];

        $refl = new \ReflectionClass($this);
        foreach ($refl->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            /** @psalm-suppress MixedAssignment */
            $res[$propertyName] = $this->{$propertyName};
        }

        return $res;
    }

    protected function getCacheManager(): CacheManager
    {
        /** @var CacheManager $service */
        $service = $this->serviceLocator->get('liip_imagine.cache.manager');

        return $service;
    }

    protected function getCartContext(): CartContextInterface
    {
        /** @var CartContextInterface $service */
        $service = $this->serviceLocator->get('sylius.context.cart');

        return $service;
    }

    protected function getChannelContext(): ChannelContextInterface
    {
        /** @var ChannelContextInterface $service */
        $service = $this->serviceLocator->get('sylius.context.channel');

        return $service;
    }

    protected function getProductVariantResolver(): ProductVariantResolverInterface
    {
        /** @var ProductVariantResolverInterface $service */
        $service = $this->serviceLocator->get('sylius.product_variant_resolver.default');

        return $service;
    }

    protected function getPropertiesFactory(): PropertiesFactoryInterface
    {
        /**
         * @psalm-suppress PrivateService
         *
         * @var PropertiesFactoryInterface $service
         */
        $service = $this->serviceLocator->get('setono_sylius_klaviyo.dto.properties.factory.properties');

        return $service;
    }

    protected function getUrlGenerator(): UrlGeneratorInterface
    {
        /** @var UrlGeneratorInterface $service */
        $service = $this->serviceLocator->get('router');

        return $service;
    }
}
