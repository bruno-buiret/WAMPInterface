<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package App\DependencyInjection
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('app');
        /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('interface')
                    ->children()
                        ->enumNode('theme')
                            ->values([
                                'black', 'black-light',
                                'blue', 'blue-light',
                                'green', 'green-light',
                                'purple', 'purple-light',
                                'red', 'red-light',
                                'yellow', 'yellow-light',
                            ])
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('wampserver')
                    ->children()
                        ->arrayNode('paths')
                            ->children()
                                ->scalarNode('configuration')
                                ->end()
                                ->scalarNode('virtual_hosts')
                                ->end()
                                ->scalarNode('aliases')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}