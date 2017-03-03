<?php

namespace Defineweb\Bundle\GoogleOauth2Bundle\Manager;

use Defineweb\Bundle\GoogleOauth2Bundle\Model\AccessTokenInterface;
use Defineweb\Bundle\GoogleOauth2Bundle\Model\RefreshTokenInterface;
use Defineweb\Bundle\GoogleOauth2Bundle\Provider\TokenProviderInterface;
use Defineweb\Bundle\GoogleOauth2Bundle\Provider\UrlProviderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
class TokenManager implements TokenManagerInterface
{
    /**
     * @var TokenProviderInterface
     */
    protected $tokenProvider;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Google
     */
    protected $googleProvider;

    /**
     * @var string
     */
    protected $urlProvider;

    /**
     * @var AccessTokenInterface
     */
    protected $accessToken;

    /**
     * @var RefreshTokenInterface
     */
    protected $refreshToken;

    public function __construct(
        ObjectManager $objectManager,
        Google $googleProvider,
        UrlProviderInterface $urlProvider
    ) {
        $this->objectManager = $objectManager;
        $this->googleProvider = $googleProvider;
        $this->urlProvider = $urlProvider;
    }

    /**
     * @return \Defineweb\Bundle\GoogleOauth2Bundle\Model\AccessTokenInterface
     */
    public function getAccessToken()
    {
        if ($this->accessToken) {
            $token = $this->accessToken;
        } else {
            $token = $this->tokenProvider->getAccessToken();
            $this->accessToken = $token;
        }

        if (!$token || $token->hasExpired()) {
            $token = $this->createAccessToken();
        }

        return $token;
    }

    /**
     * @return RefreshTokenInterface
     */
    public function getRefreshToken()
    {
        if ($this->refreshToken) {
            $token = $this->refreshToken;
        } else {
            $token = $this->tokenProvider->getRefreshToken();
        }

        if (!$token) {
            throw new TokenNotFoundException('There is no Refresh Token provided by the token provider');
        }

        $this->refreshToken = $token;

        return $token;
    }

    /**
     * @return \Defineweb\Bundle\GoogleOauth2Bundle\Model\AccessTokenInterface
     */
    public function createAccessToken()
    {
        $refreshToken = $this->getRefreshToken();

        /** @var AccessToken $token */
        $_accessToken = $this->googleProvider->getAccessToken('refresh_token', [
            'refresh_token' => $refreshToken->getToken()
        ]);

        if (!$_accessToken) {
            // TODO Check if the response is not valid because of the refresh token
            $this->objectManager->remove($refreshToken);
        }

        $accessToken = $this->tokenProvider->createAccessToken();
        $accessToken->setExpiresAt($_accessToken->getExpires());
        $accessToken->setToken($_accessToken->getToken());

        $this->objectManager->persist($accessToken);
        $this->objectManager->flush();

        $this->accessToken = $accessToken;

        return $accessToken;
    }

    /**
     * @param string $code
     */
    public function exchangeCodeForToken($code)
    {
        $token = $this->googleProvider->getAccessToken('authorization_code', [
            'code' => $code,
            'redirect_uri' => $this->urlProvider->getRedirectUrl(),
        ]);

        if ($token->getRefreshToken()) {
            $refreshToken = $this->tokenProvider->createRefreshToken();
            $refreshToken->setToken($token->getRefreshToken());
            $this->objectManager->persist($refreshToken);
        }

        if ($token->getToken()) {
            $accessToken = $this->tokenProvider->createAccessToken();
            $accessToken->setToken($token->getToken());
            $accessToken->setExpiresAt($token->getExpires());
            $this->objectManager->persist($accessToken);
        }

        $this->objectManager->flush();
    }

    /**
     * @param TokenProviderInterface $tokenProvider
     */
    public function setTokenProvider(TokenProviderInterface $tokenProvider)
    {
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * @return UrlProviderInterface|string
     */
    public function getUrlProvider()
    {
        return $this->urlProvider;
    }
}
