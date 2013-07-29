<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


/**
 * Configuration for
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class SchemaConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('zorro_rpc_schema');

        $root
            ->children()
                ->arrayNode('objects')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('type')
                                    ->isRequired()
                                ->end()
                                ->variableNode('default')
                                ->end()
                                ->booleanNode('nullable')
                                    ->defaultValue(false)
                                ->end()
                            ->end()
                            ->beforeNormalization()
                            ->ifString()
                                ->then(function ($name) {
                                    return array('type' => $name);
                                })
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }

}