<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Builder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ImplementationConfiguration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('zorro_rpc_schema_implementation');

        $isEnabled = function ($value) {
            $value = strtolower($value);
            $trueValues = array('true', '1', 'enabled', 'enable');
            $falseValues = array('false', '0', 'disable', 'disabled');

            if (in_array($value, $trueValues, true)) {
                return array('enabled' => true);
            } else if (in_array($value, $falseValues, true)) {
                return array('enabled' => false);
            }
            throw new \InvalidArgumentException('Invalid value "'.$value.'"');
        };
        $root->children()
                ->arrayNode('global')
                    ->children()
                        ->arrayNode('accessors')
                            ->children()
                                ->scalarNode('style')
                                    ->cannotBeEmpty()
                                ->end()
                                ->booleanNode('enabled')->end()
                            ->end()
                            ->beforeNormalization()
                                ->ifString()
                                ->then($isEnabled)
                            ->end()
                        ->end()
                        ->arrayNode('namespaces')
                            ->prototype('scalar')
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('classes')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')
                                ->cannotBeEmpty()
                            ->end()
                            ->arrayNode('accessors')
                                ->children()
                                    ->scalarNode('style')
                                        ->cannotBeEmpty()
                                    ->end()
                                    ->booleanNode('enabled')->end()
                                ->end()
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then($isEnabled)
                                ->end()
                            ->end()
                            ->arrayNode('properties')
                                ->prototype('array')
                                    ->cannotBeEmpty()
                                    ->children()
                                        ->scalarNode('as')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('setter')
                                             ->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('getter')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('accessors')
                                            ->validate()
                                            ->ifString()
                                                ->then($isEnabled)
                                            ->end()
                                        ->end()
                                    ->end()
                                    ->beforeNormalization()
                                    ->ifString()
                                        ->then(function ($n) {
                                            return array('as' => $n);
                                        })
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        return $builder;
    }
}
