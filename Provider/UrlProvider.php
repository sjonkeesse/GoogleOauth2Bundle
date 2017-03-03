<?php

namespace Defineweb\Bundle\GoogleOauth2Bundle\Provider;

use League\OAuth2\Client\Provider\Google;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Robin Jansen <robinjansen51@gmail.com>
 */
class UrlProvider implements UrlProviderInterface
{
    /**
     * @var Google
     */
    protected $googleProvider;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var OptionsResolver
     */
    protected $resolver;

    public function __construct(Google $googleProvider, RouterInterface $router, array $config)
    {
        $this->googleProvider = $googleProvider;
        $this->router = $router;

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver, $config);
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getGoogleOAuthRequestAccessUrl($options = [])
    {
        $options = $this->resolver->resolve($options);

        return $this->googleProvider->getAuthorizationUrl($options);
        $request->getSession()->set('oauth2state', $provider->getState());
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
//        return $this->resolver->offsetGet('redirect_uri');
        return $this->resolver->resolve()['redirect_uri'];
    }

    /**
     * @param OptionsResolver $resolver
     * @param array           $config
     */
    private function configureOptions(OptionsResolver $resolver, array $config)
    {
        $resolver->setDefaults($config);

        $resolver->setDefault('include_granted_scopes', true);
        $resolver->setDefault('response_type', 'code');
        $resolver->setDefault('access_type', 'offline');

        $resolver->setRequired(['client_id', 'redirect_uri', 'response_type', 'scope']);

//        $resolver->setAllowedValues('response_type', ['code']);
        $resolver->setAllowedValues('access_type', ['online', 'offline']);
        $resolver->setAllowedValues('include_granted_scopes', [true, false]);
//        $resolver->setAllowedValues('prompt', ['none', 'consent', 'select_account']);

        $router = $this->router;
        $resolver->setNormalizer('redirect_uri', function (Options $options, $value) use ($router) {
            if (false === filter_var($value, FILTER_VALIDATE_URL)) {
                $value = $router->generate($value, [], UrlGenerator::ABSOLUTE_URL);
            }

            return $value;
        });

        $resolver->setNormalizer('include_granted_scopes', function (Options $options, $value) {
            return ($value ? 'true' : 'false');
        });

        $resolver->setDefaults($config);
        $resolver->setDefault('include_granted_scopes', true);
    }
}
