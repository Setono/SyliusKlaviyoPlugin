<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Psr\Container\ContainerInterface;

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
                if (!is_a($hydratableObject, $parameterType->getName(), true)) {
                    continue;
                }

                $this->{$method->getName()}($hydratableObject);
            }
        }
    }
}
