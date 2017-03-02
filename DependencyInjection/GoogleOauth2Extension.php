<?php

namespace Defineweb\GoogleOauth2Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class GoogleOauth2Extension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('google_oauth2.app_id', $config['app_id']);
        $container->setParameter('google_oauth2.app_secret', $config['app_secret']);
        $container->setParameter('google_oauth2.hosted_domain', $config['hosted_domain']);
        $container->setParameter('google_oauth2.redirect_uri', $config['redirect_uri']);
        $container->setParameter('google_oauth2.access_type', $config['access_type']);
        $container->setParameter('google_oauth2.scope', $config['scope']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
