<?php

namespace Defineweb\GoogleOauth2Bundle\Manager;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
interface TokenManagerInterface
{
    public function getAccessToken();

    public function createAccessToken();

    public function getRefreshToken();

    public function getUrlProvider();
}
