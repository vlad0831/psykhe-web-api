<?php

namespace App\Services\Psykhe;

use Cache;
use Exception;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class PsykheService implements Contracts\PsykheService
{
    protected Guzzle $client;

    protected string $endpoint;

    protected string $username;

    protected string $password;

    protected string $token;

    public function __construct(Guzzle $guzzle, $endpoint, $username, $password)
    {
        $this->endpoint = rtrim($endpoint, '/');
        $this->username = $username;
        $this->password = $password;
        $this->client   = $guzzle;
    }

    protected function getTokenCacheKey()
    {
        return static::class.'::token/'.$this->endpoint.'/'.$this->username;
    }

    protected function getFreshToken()
    {
        $response = $this->client->request('POST', $this->endpoint.'/auth/login', [
            'json' => [
                'username' => $this->username,
                'password' => $this->password,
            ],
        ]);

        $response = json_decode($response->getBody()->getContents());

        if ($response === false) {
            throw new Exception('Failed to log in to PSYKHE API (non-JSON response)');
        }

        if (isset($response->access_token)) {
            $this->token = $response->access_token;

            return $this->token;
        }

        throw new Exception('Failed to log in to PSYKHE API (no token in response: '.$response.')');
    }

    protected function refreshTokenCache()
    {
        $token = $this->getFreshToken();
        Cache::put($this->getTokenCacheKey(), $token);

        return $token;
    }

    protected function getToken()
    {
        if (isset($this->token)) {
            return $this->token;
        }

        return $this->token = Cache::rememberForever(
            $this->getTokenCacheKey(),
            function () {
                return $this->getFreshToken();
            }
        );
    }

    public function Request($method, $url, $options = [])
    {
        if (substr($url, 0, 1) === '/') {
            $url = $this->endpoint.$url;
        }
        $orig_options = $options;

        $raw = false;

        if (isset($options['psykhe_raw']) && $options['psykhe_raw']) {
            $raw = true;
            unset($options['psykhe_raw']);
        }

        $freshToken = false;

        if (isset($options['psykhe_refresh_token']) && $options['psykhe_refresh_token']) {
            $freshToken = true;
            unset($options['psykhe_refresh_token']);
        }

        if (! isset($options['headers'])) {
            $options['headers'] = [];
        }
        $options['headers']['Authorization'] = (
            'Bearer '.
            ($freshToken ?
                $this->refreshTokenCache() :
                $this->getToken()
            )
        );

        try {
            $response = $this->client->request($method, $url, $options);

            if ($raw) {
                return $response;
            }

            if ($response === null) {
                return null;
            }
            $response = json_decode($response->getBody()->getContents());

            if ($response === false) {
                throw new Exception('Failed communication with PSYKHE API (non-JSON response)');
            }

            return $response;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                if ($freshToken) {
                    // already retried with a fresh token. This is a real error.
                    throw $e;
                }

                return $this->Request($method, $url, array_merge($orig_options, ['psykhe_refresh_token' => true]));
            }

            throw $e;
        } catch (ServerException $e) {
            throw new Exception('Failed communication with PSYKHE API (server exception): '.$e->getMessage());
        }
    }
}
