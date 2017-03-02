<?php

namespace Defineweb\GoogleOauth2Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
class RedirectController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function callbackAction(Request $request)
    {
        if (!$request->get('code')) {
            throw new \Exception('No code parameter');
        }

        $manager = $this->get('google_oauth2.manager.token');

        $manager->exchangeCodeForToken($request->get('code'));

        return $this->redirectToRoute('google_oauth_status');
    }
}
