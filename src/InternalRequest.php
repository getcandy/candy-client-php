<?php

namespace GetCandy\Client;

use Illuminate\Http\Request;

/**
 * Class InternalRequest.
 *
 * @example To same URL
 *     $internal = new InternalRequest();
 *     $internal->setBaseRequest()->make()->run()->getContent();
 *
 * @example To same URL with same variables
 *     $internal = new InternalRequest();
 *     $internal->setBaseRequest($request)->make()->run()->getContent();
 *
 * @example To another URL with same variables
 *     $internal = new InternalRequest();
 *     $internal->setBaseRequest($request)->setUrl($request->getSchemeAndHttpHost() . '/test123')->make()->run()->getContent();
 */
class InternalRequest
{
    protected $base_request = null;
    protected $new_request = null;
    protected $method = null;
    protected $url = null;
    protected $parameters = [];
    protected $cookies = [];
    protected $files = [];
    protected $server = [];
    protected $headers = [];
    protected $content = null;
    protected $type = null;
    protected $noContent = false;

    /**
     * Internal request.
     *
     * @param \Illuminate\Http\Request|null $request
     * @param string|null $url
     * @param array|null $parameters
     * @param string|null $http_method
     * @param array|null $headers
     * @param string|null $content
     * @param array|null $cookies
     * @param array|null $files
     * @param array|null $server
     */
    public function __construct(Request $request, $url = null, $parameters = null, $http_method = null, $headers = null, $content = null, $cookies = null, $files = null, $server = null)
    {
        $this->setBaseRequest($request);

        if ($url) {
            $this->setUrl($url);
        }

        if ($parameters) {
            $this->setParameters($parameters);
        }

        if ($http_method) {
            $this->setMethod($http_method);
        }

        if ($headers) {
            $this->setHeaders($headers);
        }

        if ($content) {
            $this->setContent($content);
        }

        if ($cookies) {
            $this->setCookies($cookies);
        }

        if ($files) {
            $this->setFiles($files);
        }

        if ($server) {
            $this->setServer($server);
        }
    }

    /**
     * Generate request.
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function make()
    {
        $http_method = $this->getMethod();
        $url = $this->getUrl();
        $parameters = $this->getParameters();
        $cookies = $this->getCookies();
        $files = $this->getFiles();
        $server = $this->getServer();
        $headers = $this->getHeaders();
        $content = $this->getContent();
        $noContent = $this->noContent;

        if (! $noContent && ! $content && $this->isJson()) {
            $content = json_encode($this->getParameters());
        }

        $req = Request::create($url, $http_method, $parameters, $cookies, $files, $server, $content);

        if (count($headers)) {
            $req->headers->replace($headers);
        }

        $this->new_request = $req;

        return $this;
    }

    /**
     * Process the new request through the (Laravel) app.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function run()
    {
        // Laravel is going to override our request, so we need to set it up here
        // so we can switch to it.
        $current = app()->request;
        $response = app()->getInstance()->handle($this->new_request);
        app()->instance('request', $current);

        return $response;
    }

    /**
     * Get base request used for new request.
     *
     * @return \Illuminate\Http\Request $request
     */
    public function getBaseRequest()
    {
        return $this->base_request;
    }

    /**
     * Set base request used for new request.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return $this
     */
    public function setBaseRequest($request = null)
    {
        if (! $request) {
            $request = Request::createFromGlobals();
        }
        $this->base_request = $request;

        try {
            $this->setMethod($request->method());
        } catch (\Exception $e) {
        }

        try {
            $this->setUrl($request->url());
        } catch (\Exception $e) {
        }

        try {
            $this->setHeaders($request->header());
        } catch (\Exception $e) {
        }

        try {
            $this->setCookies($request->cookie());
        } catch (\Exception $e) {
        }

        try {
            $this->setFiles($request->file());
        } catch (\Exception $e) {
        }

        try {
            $this->setServer($request->server());
        } catch (\Exception $e) {
        }

        try { // filter out file as it is set above
            $this->setParameters(array_except($request->all(), array_keys($this->files)));
        } catch (\Exception $e) {
        }

        return $this;
    }

    /**
     * Get request method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set request method.
     *
     * @param string $http_method
     * @return $this
     */
    public function setMethod($http_method)
    {
        $this->method = $http_method;

        return $this;
    }

    /**
     * Get request URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set request URL.
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get request parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set request parameters.
     *
     * @param array $data
     * @return $this
     */
    public function setParameters(array $data)
    {
        $this->parameters = $data;

        return $this;
    }

    /**
     * Get request cookies.
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Set request cookies.
     *
     * @param array $data
     * @return $this
     */
    public function setCookies(array $data)
    {
        $this->cookies = $data;

        return $this;
    }

    /**
     * Get request files.
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set request files.
     *
     * @param array $data
     * @return $this
     */
    public function setFiles(array $data)
    {
        $this->files = $data;

        return $this;
    }

    /**
     * Get request server variables.
     *
     * @return array
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set request server variables.
     *
     * @param array $data
     * @return $this
     */
    public function setServer(array $data)
    {
        $this->server = $data;

        return $this;
    }

    /**
     * Get request headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set request headers.
     *
     * @param array $data
     * @return $this
     */
    public function setHeaders(array $data)
    {
        if (isset($data['Content-Type'])) {
            $this->type = $data['Content-Type'];
        } else {
            $this->type = null;
        }

        $this->headers = $data;

        return $this;
    }

    /**
     * Set a specific header.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function putHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Get request content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set request content.
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set flag to leave request content null.
     *
     * A JSON request without content set will have a JSON of the parameters generated.
     * In this circumstance, this flag will force no content.
     *
     * @param bool $v
     * @return $this
     */
    public function noContent($v = true)
    {
        $this->noContent = $v;

        return $this;
    }

    /**
     * Get the request Content-Type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Check if JSON request.
     *
     * This is set during $this->setHeaders()
     *
     * @return bool
     */
    public function isJson()
    {
        return $this->type == 'application/json';
    }
}
