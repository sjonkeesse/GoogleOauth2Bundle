<?php

namespace Defineweb\GoogleOauth2Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
class OAuthController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function statusAction()
    {
        $tokenManager = $this->get('google_oauth2.manager.token');

        try {
            $refreshToken = $tokenManager->getRefreshToken();
        } catch (TokenNotFoundException $e) {
            $refreshToken = null;
        }

        try {
            $accessToken = $tokenManager->getAccessToken();
        } catch (TokenNotFoundException $e) {
            $accessToken = null;
        }

        return $this->render('@GoogleOauth2/Token/status.html.twig', [
            'refreshToken' => $refreshToken,
            'accessToken' => $accessToken,
            'authorizeUrl' => $tokenManager->getUrlProvider()->getGoogleOAuthRequestAccessUrl()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renewAccessTokenAction()
    {
        $tokenManager = $this->get('google_oauth2.manager.token');

        try {
            $tokenManager->createAccessToken();
        } catch (TokenNotFoundException $e) {
            return $this->render('@GoogleOauth2/Token/no-refresh-token.html.twig', [
                'authorizeUrl' => $tokenManager->getUrlProvider()->getGoogleOAuthRequestAccessUrl()
            ]);
        }

        return $this->redirectToRoute('google_oauth2_status');
    }

}
