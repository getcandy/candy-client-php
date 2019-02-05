<?php

namespace GetCandy\Client;

use Cache;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GetCandy\Client\Exceptions\ClientCredentialsException;
use GetCandy\Client\Responses\ApiResponse;
use GetCandy\Client\Responses\CandyHttpResponse;

class Candy
{
    protected $callChain = [];
    protected $returnJob = false;
    protected $job;
    protected $params;
    protected $url;
    protected $clientId;
    protected $clientSecret;
    protected $channel = 'webstore';
    protected $locale = 'en';
    protected $client;
    protected $token = null;
    protected $driver;

    public function __construct($internal = false)
    {
        $manager = app()->getInstance()->make(CandyClientManager::class);
        if ($internal) {
            $this->driver = $manager->with('internal');
        } else {
            $this->driver = $manager->with('guzzle');
        }
    }

    /**
     * Set the token value
     *
     * @param string $token
     * @return self
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Revoke an access token
     *
     * @param string $token
     *
     * @return string
     */
    public function revoke($token)
    {
        $this->client->delete('oauth/personal-access-tokens/' . $token, [
            'form_params' => [
                    'client_id' => $this->getClientId(),
                    'client_secret' => $this->getClientSecret()
            ],
            'verify' => config('services.ecommerce_api.verify'),
        ]);

        dd('hit');
    }

    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets up the client
     *
     * @param string $url
     * @param array $config
     * @return void
     */
    public function init($url, array $config = [])
    {
        $this->url = $url;
        $this->clientSecret = $config['client_secret'] ?? null;
        $this->clientId = $config['client_id'] ?? null;
        $this->locale = $config['locale'] ?? $this->locale;
        $this->channel = $config['channel'] ?? $this->channel;

        $this->client = new Client([
            'base_uri' => $this->getUri()
        ]);
    }

    public function getUrl($endpoint = null)
    {
        return $this->url . '/api/v1/' . $endpoint;
    }

    public function getUri()
    {
        return $this->url;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function __call($name, $arguments)
    {
        $this->callChain[] = ucfirst($name);

        if (!empty($arguments)) {
            $this->addParams($arguments);
        }

        if ($this->canRun()) {
            if ($this->returnJob) {
                return $this->getJob();
            } else {
                return $this->execute()->getResponse();
            }
        }

        return $this;
    }

    protected function addParams($params)
    {
        if (!is_array($params[0])) {
            $this->params['id'] = $params[0];
        } else {
            foreach ($params[0] as $key => $value) {
                $this->params[$key] = $value;
            }
        }
    }

    public function job()
    {
        $this->returnJob = true;

        return $this;
    }

    public function canRun()
    {
        // Do we have a job for this call chain?
        return class_exists($this->getClassFromChain());
    }

    public function getJob()
    {
        $jobClass = $this->getClassFromChain();
        $this->job = new $jobClass($this->params);

        $this->reset();

        return $this->job;
    }

    public function execute()
    {
        $job = $this->getJob();

        $this->driver->add($job);
        $this->driver->execute();
        $this->reset();

        return $job->getRequest();
    }

    public function batch(Closure $addJobs)
    {
        $batch = new \GetCandy\Client\InternalBatchRequestService();

        $addJobs($batch);

        $batch->execute();

        $responses = [];

        foreach ($batch->getJobs() as $key => $job) {
            $responses[$key] = $job->getRequest()->getResponse();
        }

        return $responses;
    }

    public function reset()
    {
        $this->callChain = [];
        $this->returnJob = false;
        $this->params = [];
    }

    private function getClassFromChain()
    {
        $classChain = $this->callChain;

        // See if we have it in our config, i.e a custom job.
        $customJob = implode('.', array_map('strtolower', $this->callChain));

        $customJobs = config('getcandy.client_jobs', []);

        if (!empty($customJobs[$customJob])) {
            return $customJobs[$customJob];
        }

        return "\\GetCandy\\Client\\Jobs\\" . implode("\\", array_map("ucfirst", $this->callChain));
    }

    /**
     * Gets a refresh token
     *
     * @param string $refreshToken
     *
     * @return mixed
     */
    public function getRefreshToken($refreshToken)
    {
        $params = [
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token'
        ];

        return $this->requestToken($params);
    }

    /**
     * Get an access token for a user
     *
     * @return mixed
     */
    public function getUserToken($username, $password)
    {
        return $this->requestToken([
            'username' => $username,
            'password' => $password,
            'grant_type' => 'password'
        ]);
    }

    /**
     * Gets a client token
     *
     * @return mixed
     */
    public function getClientToken()
    {
        if (!Cache::has('client_token')) {
            $params = [
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'scope' => '',
                'grant_type' => 'client_credentials'
            ];
            $response = $this->requestToken($params);

            if ($response->getStatus() == 401) {
                throw new ClientCredentialsException($response->getBody()['message']);
            }
            Cache::put('client_token', $response->getBody()->access_token, ($response->getBody()->expires_in / 60));
        }
        return Cache::get('client_token');
    }


    public function getToken($force = false)
    {
        if ($this->token) {
            return $this->token;
        }
        return $this->getClientToken();
    }

    /**
     * Request a token from the API
     *
     * @param array $params
     * @return mixed
     */
    private function requestToken($params)
    {
        try {
            $response = $this->client->post('oauth/token', [
                'form_params' => array_merge(
                    [
                        'client_id' => $this->getClientId(),
                        'client_secret' => $this->getClientSecret()
                    ],
                    $params
                ),
                'verify' => config('services.ecommerce_api.verify'),
            ]);
            $status = $response->getStatusCode();
            $contents = $response->getBody()->getContents();
            $fulfilled = true;
        } catch (ClientException $e) {
            $contents = $e->getResponse()->getBody()->getContents();
            $status = $e->getResponse()->getStatusCode();
            $fulfilled = false;
        }

        $response = new CandyHttpResponse($status);
        $response->setData(['data' => json_decode($contents, true)]);
        $response->setFulfilled($fulfilled);
        return new ApiResponse($response);
    }
}
