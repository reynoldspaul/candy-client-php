<?php

namespace GetCandy\Client;

use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Request;
use Mockery\Exception;
use Session;

class BatchRequestService
{
    protected $jobs = [];

    public function add(JobInterface $job, $reference = null)
    {
        $this->jobs[$reference] = $job;
    }

    public function getJobs()
    {
        return $this->jobs;
    }

    public function execute($force = false)
    {
        // Collect all API requests
        $requests = [];

        // Get unique requests
        foreach ($this->jobs as $job) {
            foreach ($job->getRequests() as $request) {
                if (!in_array($request, $requests)) {
                    $requests[(string) $request] = $request;
                }
            }
        }

        $client = new Client(['base_uri' => Config::get('services.ecommerce_api.baseURL')]);
        $promises = [];
        $headers = [
            'Authorization' => 'Bearer ' . $this->getToken($force),
            'Accept' => 'application/json'
        ];

        foreach ($requests as $request) {
            $options = [
                'headers' => $headers,
                'verify' => Config::get('services.ecommerce_api.verify'),
            ];

            if ($request->getData()) {
                if (strtoupper($request->getMethod()) == 'GET') {
                    $options['query'] = $request->getData();
                } else {
                    $options['form_params'] = $request->getData();
                }
            }

            $promises[(string) $request] = $client->requestAsync($request->getMethod(), $request->getEndPoint(), $options);
        }

        // Wait on all of the requests to complete. Throws a ConnectException if any of the requests fail
        try {
            $results = Promise\unwrap($promises);
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                return $this->execute(true);
            } else {
                throw($e);
            }
        }

        foreach ($promises as $index => $promise) {
            $response = $results[$index];

            $data = json_decode($response->getBody()->getContents(), true);

            foreach ($this->jobs as $job) {
                $job->addResult($index, $data);

                if ($job->canRun()) {
                    $job->run();
                }
            }
        }
    }

    public function getToken($force = false)
    {
        $dateTime = Carbon::now();
        $apiDateTime = Session::get('api_token_expiry', Carbon::now());

        // If token has not expired and has longer that 10 mins keep it
        if ($apiDateTime->copy()->subMinutes(20)->gt($dateTime) && !$force) {
            return Session::get('api_token');
        }

        // Do we need to refresh users token or get a new client token
        if (Session::get('api_refresh_token', false) && $apiDateTime->gte($dateTime)) {
            $token = $this->getRefreshToken(Session::get('api_refresh_token'));
        } else {
            $token = $this->getClientToken();
        }

        return $token->access_token;
    }

    private function getRefreshToken($refreshToken)
    {
        $dateTime = Carbon::now();

        // Forget Sessions
        $this->resetSessions();

        $params = [
            'client_id'     => Config::get('services.ecommerce_api.client_id'),
            'client_secret' => Config::get('services.ecommerce_api.client_secret'),
            'scope'         => '',
            'refresh_token' => $refreshToken,
            'grant_type'    => 'refresh_token'
        ];
        $response = $this->requestToken($params);

        // So if we don't get an access token back then try get a client token
        if (isset($response->access_token)) {
            Session::forget('logged_in');
            Session::forget('user');
            Session::forget('refresh_token');

            return $this->getClientToken();
        }

        // Set Sessions
        Session::put('api_token_expiry', $dateTime->addSeconds($response->expires_in));
        Session::put('api_token', $response->access_token);
        Session::put('api_refresh_token', $response->refresh_token);
        Session::put('logged_in', true);

        return $response;
    }

    private function getClientToken()
    {
        $dateTime = Carbon::now();

        // Forget Sessions
        $this->resetSessions();

        $params = [
            'client_id'     => Config::get('services.ecommerce_api.client_id'),
            'client_secret' => Config::get('services.ecommerce_api.client_secret'),
            'scope'         => '',
            'grant_type'    => 'client_credentials'
        ];
        $response = $this->requestToken($params);

        // Set Sessions
        Session::put('api_token_expiry', $dateTime->addSeconds($response->expires_in));
        Session::put('api_token', $response->access_token);

        return $response;
    }

    private function requestToken($params)
    {
        $client = new Client([
            'base_uri' => Config::get('services.ecommerce_api.baseURL')
        ]);

        $response = $client->post('oauth/token', [
            'form_params' => $params,
            'verify' => Config::get('services.ecommerce_api.verify'),
        ]);

        return json_decode((string) $response->getBody());
    }

    private function resetSessions()
    {
        Session::forget('api_token_expiry');
        Session::forget('api_token');
        Session::forget('api_refresh_token');
        Session::forget('logged_in');
    }

}
