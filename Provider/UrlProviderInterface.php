<?php

namespace Defineweb\GoogleOauth2Bundle\Provider;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
interface UrlProviderInterface
{
    /**
     * Returns an url to request access from Google
     * @link https://developers.google.com/identity/protocols/OAuth2WebServer#redirecting
     *
     * Options (see link)
     *       - client_id
     *       - redirect_uri
     *       - response_type
     *       - scope
     *       - access_type
     *       - state
     *       - include_granted_scopes
     *       - login_hint
     *       - prompt
     *
     * @param array $options
     *
     * @return string
     */
    public function getGoogleOAuthRequestAccessUrl($options = []);

    /**
     * @return string
     */
    public function getRedirectUrl();
}
