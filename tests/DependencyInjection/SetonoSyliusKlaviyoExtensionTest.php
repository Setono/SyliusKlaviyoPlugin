<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Setono\SyliusKlaviyoPlugin\DependencyInjection\SetonoSyliusKlaviyoExtension;

/**
 * See examples of tests and configuration options here: https://github.com/SymfonyTest/SymfonyDependencyInjectionTest
 */
final class SetonoSyliusKlaviyoExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new SetonoSyliusKlaviyoExtension(),
        ];
    }

    protected function getMinimalConfiguration(): array
    {
        return [
            'credentials' => [
                'public_token' => 'public_token',
                'private_token' => 'private_token',
            ],
        ];
    }

    /**
     * @test
     */
    public function after_loading_the_correct_parameter_has_been_set(): void
    {
        $this->load();

        $this->assertContainerBuilderHasParameter('setono_sylius_klaviyo.public_token', 'public_token');
        $this->assertContainerBuilderHasParameter('setono_sylius_klaviyo.private_token', 'private_token');
    }
}
