<?php

namespace Defineweb\Bundle\GoogleOauth2Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('google_oauth2');

        $rootNode
            ->children()
                ->scalarNode('app_id')->isRequired()->cannotBeEmpty()->example('123456789123-abcdefghijkl1a2pasaapo1234567abc.apps.googleusercontent.com')->end()
                ->scalarNode('app_secret')->isRequired()->cannotBeEmpty()->example('ABC12a1bc5x_pIEqDLSKS3T2')->end()
                ->scalarNode('hosted_domain')->defaultNull()->end()
                ->scalarNode('redirect_uri')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('access_type')->defaultValue('offline')->end()
                ->arrayNode('scope')
                    ->treatNullLike([])
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('classes')
                    ->children()
                        ->scalarNode('access_token')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('refresh_token')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
