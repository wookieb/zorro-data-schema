<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\Builder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration schema of schema outline files
 *
 * NOTE! Please do NOT autoformat this file
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class SchemaOutlineConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('zorro_rpc_schema');
        /*******************************************
         *
         * NOTE! Please do NOT autoformat this file
         *
         *******************************************/
        $root
            ->children()
                ->arrayNode('classes')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('extend')
                            ->end()
                            ->arrayNode('properties')
                                ->prototype('array')
                                    ->cannotBeEmpty()
                                    ->children()
                                        ->scalarNode('type')
                                            ->isRequired()
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->variableNode('default')
                                        ->end()
                                        ->booleanNode('nullable')
                                            ->defaultValue(false)
                                        ->end()
                                    ->end()
                                    ->beforeNormalization()
                                    ->ifString()
                                        ->then(function ($n) {
                                            return array('type' => $n);
                                        })
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('enums')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->prototype('integer')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
