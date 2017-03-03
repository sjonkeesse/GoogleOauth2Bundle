<?php

namespace Defineweb\Bundle\GoogleOauth2Bundle\Model;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
abstract class RefreshToken implements RefreshTokenInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $scope;

    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * {@inheritdoc}
     */
    public function getScope()
    {
        return $this->scope;
    }
}
