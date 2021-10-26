<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DependencyInjection;

use Setono\SyliusKlaviyoPlugin\Doctrine\ORM\MemberListRepository;
use Setono\SyliusKlaviyoPlugin\Form\Type\MemberListType;
use Setono\SyliusKlaviyoPlugin\Model\MemberList;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_sylius_klaviyo');
        $rootNode = $treeBuilder->getRootNode();

        /**
         * @psalm-suppress MixedMethodCall,PossiblyUndefinedMethod,PossiblyNullReference
         */
        $rootNode
            ->children()
                ->scalarNode('driver')
                    ->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)
                ->end()
                ->arrayNode('newsletter_checkbox')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('show_in_checkout')
                            ->defaultTrue()
                            ->info('Whether to show the subscribe to newsletter checkbox in checkout. Notice that you still have to add the field yourself in the template. See tests/Application/templates/bundles/SyliusShopBundle/Checkout/Address/_form.html.twig for an example')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('email_cookie_name')
                    ->cannotBeEmpty()
                    ->defaultValue('ssk_e')
                    ->info('The name of the cookie that will save the visitors email. Do NOT change this value after running in production since this will render all your existing visitors\' cookies useless')
                ->end()
                ->scalarNode('tracking_strategy')
                    ->cannotBeEmpty()
                    ->defaultValue('track_all')
                    ->info('The strategy to use when tracking. Out of the box you can choose between "track_all" which tracks all visitors and "track_with_email" which tracks visitors when they have provided their email')
                ->end()
                ->arrayNode('credentials')
                    ->isRequired()
                    ->children()
                        ->scalarNode('public_token')
                            ->info('Your public token')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('private_token')
                            ->info('Your public token')
                            ->isRequired()
                            ->cannotBeEmpty()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        /**
         * @psalm-suppress MixedMethodCall
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress PossiblyNullReference
         */
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('member_list')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(MemberListType::class)->end()
                                        ->scalarNode('model')->defaultValue(MemberList::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(MemberListRepository::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
