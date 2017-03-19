<?php

namespace Defineweb\Bundle\GoogleOauth2Bundle\Model;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
abstract class AccessToken implements AccessTokenInterface
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
     * @var int
     */
    protected $expiresAt;

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
    public function setExpiresAt($timestamp)
    {
        $this->expiresAt = $timestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpiresIn()
    {
        return $this->expiresAt - time();
    }

    /**
     * {@inheritdoc}
     */
    public function hasExpired()
    {
        if ($this->expiresAt) {
            return time() > $this->expiresAt - 10;
        }

        return false;
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
