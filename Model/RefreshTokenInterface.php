<?php

namespace Defineweb\Bundle\GoogleOauth2Bundle\Model;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
interface RefreshTokenInterface
{
    /**
     * @param string $token
     */
    public function setToken($token);

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param string $scope
     */
    public function setScope($scope);

    /**
     * @return string
     */
    public function getScope();
}
