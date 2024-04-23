<?php

namespace App\Providers;

use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use SocialiteProviders\FusionAuth\Provider as BaseProvider;
use SocialiteProviders\Manager\OAuth2\User;

class FusionAuthProvider extends BaseProvider
{
    public const IDENTIFIER = 'FUSIONAUTH';

    /**
     * {@inheritdoc}
     */
    protected $scopes = [
        'email',
        'openid',
        'profile',
    ];

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * Get the base URL.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function getFusionAuthUrl()
    {
        $baseUrl = $this->getConfig('base_url');

        if ($baseUrl === null) {
            throw new InvalidArgumentException('Missing base_url');
        }

        return rtrim($baseUrl).'/oauth2';
    }

    /**
     * {@inheritdoc}
     */
    public static function additionalConfigKeys()
    {
        return [
            'base_url',
            'tenant_id',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getFusionAuthUrl().'/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->getFusionAuthUrl().'/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getFusionAuthUrl().'/userinfo', [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user): User
    {
        $fullName = $user['name'] ?? ($user['given_name'] . ' ' . $user['family_name'] ?? '');
        return (new User())->setRaw($user)->map([
            'id' => $user['sub'],
            'nickname' => $user['preferred_username'] ?? null,
            'name' => $fullName,
            'email' => $user['email'],
            'avatar' => $user['picture'] ?? null,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    protected function getCodeFields($state = null)
    {
        $tenantId = $this->getConfig('tenant_id');

        if ($tenantId === null) {
            throw new InvalidArgumentException('Missing tenant_id');
        }

        return array_merge(parent::getCodeFields($state), [
            'tenantId' => $tenantId,
        ]);
    }
}
