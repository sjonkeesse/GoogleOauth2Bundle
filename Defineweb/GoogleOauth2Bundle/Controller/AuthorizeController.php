<?php

namespace Defineweb\GoogleOauth2Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
class AuthorizeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function authorizeAction(Request $request)
    {
        $provider = $this->get('league_oauth2.client.google_provider');

        $redirectUrl = $this->generateUrl(
            $this->getParameter('google_oauth2.redirect_uri'),
            [],
            UrlGenerator::ABSOLUTE_URL
        );

        $authUrl = $provider->getAuthorizationUrl([
            'scope' => $this->getParameter('google_oauth2.scope'),
            'redirect_uri' => $redirectUrl,
            'include_granted_scopes' => 'true', // Necessary for the refresh token
        ]);

        $request->getSession()->set('oauth2state', $provider->getState());

        return $this->redirect($authUrl);
    }
}
