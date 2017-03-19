<?php

namespace Defineweb\Bundle\GoogleOauth2Bundle\Manager;

use Defineweb\Bundle\GoogleOauth2Bundle\Model\AccessTokenInterface;
use Defineweb\Bundle\GoogleOauth2Bundle\Model\RefreshTokenInterface;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
interface TokenManagerInterface
{
    /**
     * @return AccessTokenInterface
     */
    public function getAccessToken();

    /**
     * @return AccessTokenInterface
     */
    public function createAccessToken();

    /**
     * @return RefreshTokenInterface
     */
    public function getRefreshToken();

    /**
     * @return string
     */
    public function getUrlProvider();
}
