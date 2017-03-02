<?php

namespace Defineweb\GoogleOauth2Bundle\Model;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
interface AccessTokenInterface
{
    /**
     * @param int $timestamp
     */
    public function setExpiresAt($timestamp);

    /**
     * @return int
     */
    public function getExpiresAt();

    /**
     * @return boolean
     */
    public function hasExpired();

    /**
     * @param string $token
     */
    public function setToken($token);

    /**
     * @param string $scope
     */
    public function setScope($scope);
}