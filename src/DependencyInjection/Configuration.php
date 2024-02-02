<?php



/*
 * This file is part of the SymfonyCasts ResetPasswordBundle package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeoxToPdf\NeoxToPdfBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Jesse Rushlow <jr@rushlow.dev>
 * @author Ryan Weaver   <ryan@symfonycasts.com>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('neox_to_pdf');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('directory_save')->defaultValue("/public/neoxPdf/")->end()
                ->scalarNode('directory_class')->defaultValue(null)->end()
                ->arrayNode('customs')->scalarPrototype()->end()->end()
                ->arrayNode('services')->scalarPrototype()->end()->end()
                ->end() // template
            ->end()
        ;

        return $treeBuilder;
    }
}