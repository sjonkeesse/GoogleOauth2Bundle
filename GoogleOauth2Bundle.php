<?php

namespace Defineweb\GoogleOauth2Bundle;

use Defineweb\GoogleOauth2Bundle\DependencyInjection\Compiler\TokenManagerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
class GoogleOauth2Bundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TokenManagerPass());
    }
}
