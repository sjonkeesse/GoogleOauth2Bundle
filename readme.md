![screenshot](https://puu.sh/uqV7J/6552dbe827.png)

## Todo
`[ ]` Check invalid refresh token response for when permission is retracted

## Installation

1. Enable the bundle
2. Add configuration
3. Include routes
4. Extend AccessToken and RefreshToken with custom properties
5. Create TokenProvider

### Step 1: Enable the bundle

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Defineweb\GoogleOauth2Bundle\GoogleOauth2Bundle(),
    );
}
```

### Step 2: Configure

``` yaml
# app/config/config.yml
google_oauth2:
    app_id:          %google_app_id%
    app_secret:      %google_app_secret%
    hosted_domain:   %google_hosted_domain% 
    redirect_uri:    google_oauth2_callback  # Url or route id
    scope:
        - https://www.googleapis.com/auth/cloudprint
        # Available scopes: https://developers.google.com/identity/protocols/googlescopes
```

Create / configure an app in the Google Developer environment: [https://console.developers.google.com/apis/credentials](https://console.developers.google.com/apis/credentials)
 - Create app
 - Add a redirect / callback url (for example: https://yourdomain.com/google-oauth/callback)
 - Verify your domain [https://console.developers.google.com/apis/credentials/domainverification](https://console.developers.google.com/apis/credentials/domainverification)

``` yaml
# app/config/parameters.yml
google_app_id:          ******.apps.googleusercontent.com
google_app_secret:      *************
google_hosted_domain:   https://yourdomain.com
```

### Step 3: Include routes

Import routing using yml:
``` yaml
# app/config/routing.yml

# Optional status route
google_oauth2_status:
    path: /google-oauth/status
    defaults:
        _controller: GoogleOauth2Bundle:OAuth:status
    
google_oauth2:
    resource: "@GoogleOauth2Bundle/Resources/config/routing.xml"
    
```
Using xml:
``` xml
<!-- app/config/routing.xml -->
<!-- Optional status route -->
<route id="google_oauth_status" path="/google-oauth/status">
    <default key="_controller">GoogleOauth2Bundle:OAuth:status</default>
</route>

<import resource="@GoogleOauth2Bundle/Resources/config/routing.xml"/>
```

### Step 4: Extend AccessToken and RefreshToken with custom properties
``` php
<?php
// src/Acme/AppBundle/Entity/AccessToken.php

namespace Acme\AppBundle\Entity;

use Defineweb\GoogleOauth2Bundle\Entity\AccessToken as BaseAccessToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Your\Own\Entity\User")
     */
    protected $user;
}
```

``` php
<?php
// src/Acme/AppBundle/Entity/RefreshToken.php

namespace Acme\AppBundle\Entity;

use Defineweb\GoogleOauth2Bundle\Entity\RefreshToken as BaseRefreshToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class RefreshToken extends BaseRefreshToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Your\Own\Entity\User")
     */
    protected $user;
}
```

### Step 5: Create TokenProvider
``` php
<?php

namespace Acme\AppBundle\Provider;

use Acme\AppBundle\Entity\AccessToken;
use Acme\AppBundle\Entity\RefreshToken;
use Defineweb\GoogleOauth2Bundle\Provider\TokenProviderInterface;
use Doctrine\ORM\EntityManager;

class TokenProvider implements TokenProviderInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     * Your own user provider or some other logic
     */
    protected $userProvider;

    public function __construct(EntityManager $em, UserProvider $userProvider)
    {
        $this->entityManager = $em;
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        $result = $this->entityManager->getRepository('AppBundle:AccessToken')->findBy(
            ['user' => $this->userProvider->getUser()],
            ['expiresAt' => 'desc'],
            1
        );

        return key_exists(0, $result) ? $result[0] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken()
    {
        $result = $this->entityManager->getRepository('AppBundle:RefreshToken')->findBy(
            ['user' => $this->userProvider->getUser()],
            ['id' => 'desc'],
            1
        );

        return key_exists(0, $result) ? $result[0] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function createAccessToken()
    {
        return new AccessToken();
    }

    /**
     * {@inheritdoc}
     */
    public function createRefreshToken()
    {
        return new RefreshToken();
    }
}
```

Register this TokenProvider as a service with a `google_oauth2.token_provider` tag

``` xml
<service id="app.provider.token" class="Acme\AppBundle\Provider\TokenProvider">
    <argument type="service" id="doctrine.orm.entity_manager"/>
    <!--<argument type="service" id="your_user_provider"/>-->
    <tag name="google_oauth2.token_provider"/>
</service>
```
