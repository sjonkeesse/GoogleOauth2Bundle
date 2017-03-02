<?php

namespace Defineweb\GoogleOauth2Bundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
class TokenManagerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('google_oauth2.manager.token');

        $taggedServices = $container->findTaggedServiceIds('google_oauth2.token_provider');

        if (1 !== count($taggedServices)) {
            throw new \Exception('No service found with tag "google_oauth2.token_provider"');
        }

        $definition->addMethodCall('setTokenProvider', [
            new Reference(array_keys($taggedServices)[0])
        ]);
    }
