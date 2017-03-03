<?php

namespace Defineweb\Bundle\GoogleOauth2Bundle\Provider;

use Defineweb\Bundle\GoogleOauth2Bundle\Model\AccessTokenInterface;
use Defineweb\Bundle\GoogleOauth2Bundle\Model\RefreshTokenInterface;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
interface TokenProviderInterface
{
    /**
     * Get the Access Token from the database for current user / session
     *
     * @return AccessTokenInterface|null
     */
    public function getAccessToken();

    /**
     * Get the Refresh Token from the database for current user / session
     *
     * @return RefreshTokenInterface|null
     */
    public function getRefreshToken();

    /**
     * @return AccessTokenInterface
     */
    public function createAccessToken();

    /**
     * @return RefreshTokenInterface
     */
    public function createRefreshToken();
}
