<?php

namespace SlickLabs\MailChimp;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use SlickLabs\MailChimp\Exception\MailChimpException;
use SlickLabs\MailChimp\Exception\ResponseException;
use SlickLabs\MailChimp\Response;

/**
 * 
 * MailChimp
 *
 * MailChimp API v3 wrapper for PHP
 *
 * @author Leo Flapper <leo.flapper@slicklabs.nl>
 * @version 1.0.0
 */
class MailChimp
{

    /**
     * The MailChimp API key.
     * @var string
     */
    private $apiKey;

    /**
     * The MailChimp API url without the endpoint.
     * @var string
     */
    private $apiUrl = 'https://%s.api.mailchimp.com/3.0';

    /**
     * The MailChimp API endpoint.
     * @var string
     */
    private $apiEndpoint = '';

    /**
     * The request headers.
     * @var array
     */
    private $headers = [];

    /**
     * Verify SSL peer.
     * @var boolean
     */
    private $verify = true;

    /**
     * The request client.
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * Valid request methods
     * @var string[]
     */
    protected $validMethods = [
        'DELETE'    => true,
        'GET'       => true,
        'PATCH'     => true,
        'POST'      => true,
        'PUT'       => true
    ];

    /**
     * Valid request methods
     * @var string[]
     */
    protected $bodyMethods = [
        'PATCH' => true,
        'POST'  => true,
        'PUT'   => true
    ];

    /**
     * Sets the MailChimp API key and request headers.
     * @param string $apiKey the MailChimp API key
     */
    public function __construct($apiKey)
    {
        $this->setApiKey($apiKey);
        $this->setHeaders($this->getDefaultHeaders());
    }

    /**
     * Returns the MailChimp API key.
     * @return string.
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets the MailChimp API key.
     * @param string $apiKey the API key.
     */
    public function setApiKey($apiKey)
    {
        if (!is_string($apiKey)) {
            throw new InvalidArgumentException(sprintf(
                '%s: expects a string argument; received "%s"',
                __METHOD__,
                (is_object($apiKey) ? get_class($apiKey) : gettype($apiKey))
            ));
        }

        if (strpos($apiKey, '-') === false) {
            throw new MailChimpException('Invalid MailChimp API key supplied.');
        }

        $this->apiKey = $apiKey;
    }

    /**
     * Returns the MailChimp API endpoint.
     * @return string
     */
    public function getApiEndpoint()
    {
        if(!$this->apiEndpoint){
            $this->setApiEndpoint();
        }

        return $this->apiEndPoint;
    }

    /**
     * Sets the MailChimp API endpoint by retrieving the datacenter
     * from the MailChimp API Key.
     */
    private function setApiEndpoint()
    {
        list(, $dataCenter) = explode('-', $this->apiKey);
        $this->apiEndPoint  = sprintf($this->apiUrl, $dataCenter);
    }

    /**
     * Returns a single or all headers.
     * @param  string $key optional header key.
     * @return mixed the header values, or a single header value.
     */
    public function getHeaders($key = '')
    {

        $result = $this->headers;
        
        if($key){
            $result = '';
            if (!is_string($key)) {
                throw new InvalidArgumentException(sprintf(
                    '%s: expects a string argument; received "%s"',
                    __METHOD__,
                    (is_object($key) ? get_class($key) : gettype($key))
                ));
            }

            if(isset($this->headers[$key])){
                $result = $this->headers[$key];
            }
        }

        return $result;
    }

    /**
     * Sets headers by the array provided.
     * @param array $headers the headers.
     */
    public function setHeaders(array $headers)
    {
        foreach($headers as $key => $value){
            $this->setHeader($key, $value);
        }
    }

    /**
     * Sets a single header.
     * @param string    $key   the header key.
     * @param mixed     $value the header value.
     */
    public function setHeader($key, $value)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(sprintf(
                '%s: expects a string argument; received "%s"',
                __METHOD__,
                (is_object($key) ? get_class($key) : gettype($key))
            ));
        }

        $this->headers[$key] = $value;
    }

    /**
     * Sets the verify SSL peer boolean.
     * @param bool $verify true to verify, false if not.
     */
    public function setVerify(bool $verify)
    {
        return $this->verify = $verify;
    }

    /**
     * Returns the verify SSL peer boolean.
     * @return bool $verify true to verify, false if not.
     */
    public function verify()
    {
        return $this->verify;
    }

    /**
     * Returns the default headers.
     * @return array the default headers.
     */
    public function getDefaultHeaders()
    {
        return [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
            'User-Agent' => 'SlickLabs/MailChimp'
        ];
    }

    /**
     * Returns the request client.
     * @return GuzzleHttp\Client the request client.
     */
    private function getClient()
    {
        if(!$this->client){
            $this->setClient();
        }
        return $this->client;
    }

    /**
     * Sets the request client.
     * @return GuzzleHttp\Client the request client.
     */
    private function setClient()
    {
        $this->client = new Client();
    }

    /**
     * Returns the valid request methods.
     * @return array the valid request methods.
     */
    private function getMethods()
    {
        return array_keys($this->validMethods);
    }

    /**
     * Checks the method provided is valid.
     * @param  string $method the request method.
     * @return boolean true if valid, false if not.
     */
    private function isValidMethod($method)
    {
        if (!is_string($method)) {
            throw new InvalidArgumentException(sprintf(
                '%s: expects a string argument; received "%s"',
                __METHOD__,
                (is_object($method) ? get_class($method) : gettype($method))
            ));
        }

        if(isset($this->validMethods[$method])){
            return true;
        }
        return false;
    }

    /**
     * Checks if the a request body is allowed for the 
     * desired method.
     * @param  string $method the request method.
     * @return boolean true if body allowed, false if not.       
     */
    private function bodyAllowed($method)
    {
        if (!is_string($method)) {
            throw new InvalidArgumentException(sprintf(
                '%s: expects a string argument; received "%s"',
                __METHOD__,
                (is_object($method) ? get_class($method) : gettype($method))
            ));
        }

        if(isset($this->bodyMethods[$method])){
            return true;
        }
        return false;
    }

    /**
     * Performs an DELETE request.
     * @param  string $uri the MailChimp API uri.
     * @param  array  $args   request values.
     * @return SlickLabs\MailChimp\ResponseInterface the MailChimp API response.
     */
    public function delete($uri, $args = [])
    {
        return $this->doRequest('DELETE', $uri, $args);
    }

    /**
     * Performs a GET request.
     * @param  string $uri the MailChimp API uri.
     * @param  array  $args   request values.
     * @return SlickLabs\MailChimp\ResponseInterface the MailChimp API response.
     */
    public function get($uri, $args = [])
    {
        return $this->doRequest('GET', $uri, $args);
    }

    /**
     * Performs a PATCH request.
     * @param  string $uri the MailChimp API uri.
     * @param  array  $args   request values.
     * @return SlickLabs\MailChimp\ResponseInterface the MailChimp API response.
     */
    public function patch($uri, $args = [])
    {
        return $this->doRequest('PATCH', $uri, $args);
    }

    /**
     * Performs a POST request.
     * @param  string $uri the MailChimp API uri.
     * @param  array  $args   request values.
     * @return SlickLabs\MailChimp\ResponseInterface the MailChimp API response.
     */
    public function post($uri, $args = [])
    {
        return $this->doRequest('POST', $uri, $args);
    }

    /**
     * Performs a PUT request.
     * @param  string $uri the MailChimp API uri.
     * @param  array  $args   request values.
     * @return SlickLabs\MailChimp\ResponseInterface the MailChimp API response.
     */
    public function put($uri, $args = [])
    {
        return $this->doRequest('PUT', $uri, $args);
    }  

    /**
     * Performs a HTTP request.
     * @param  string $method                           the desired HTTP request method.
     * @param  string $uri                              the MailChimp API uri.
     * @param  array  $args                             request values.
     * @return SlickLabs\MailChimp\ResponseInterface    the MailChimp API response.
     */
    protected function doRequest($method, $uri, $args = [])
    {

        $response = false;

        if(!$this->isValidMethod($method)){
            throw new InvalidArgumentException(
                sprintf('"%s" is not a valid HTTP method: available methods are %s.', $method, implode(', ', $this->getMethods()))
            );
        }        

        $this->setHeader('Authorization', 'apikey ' . $this->getApiKey());

        $url = $this->getApiEndpoint() . '/' . $uri;
        $defaultArgs = [
            'headers' => $this->getHeaders(),
            'timeout' => 10,
            'verify' => $this->verify()
        ];

        $args = $this->addAll($defaultArgs, $args);
        

        if($this->bodyAllowed($method)){
            if(!isset($args['body'])){
                $args['body'] = '';
            }
            $args['json'] = $args['body']; 
        }
        unset($args['body']);

        try {
            $response = $this->formatResponse($this->getClient()->request($method, $url, $args));
        } catch (ClientException $e) {
            $response = $this->formatResponse($e->getResponse());
        }

        return $response;

    }

     /**
     * Accepts a variable number of arrays where the key-value pairs of each array 
     * is added to it's sibling array.
     *
     * @return array the resulting array after combining all the given arrays.
     * @author Chris Harris <c.harris@hotmail.com>
     */
    public static function addAll()
    {
        // the resulting array.
        $array = [];    
        
        // a variable-length argument list.
        if ($args = func_get_args()) {
            // merge arguments that are of type array.
            foreach ($args as $arg) {
                if (is_array($arg)) {
                    foreach (array_keys($arg) as $key) {
                        if (isset($array[$key]) && is_array($array[$key]) && is_array($arg[$key])) {
                            // recursively copy values from multidimensional arrays.
                            $array[$key] = self::addAll($array[$key], $arg[$key]); 
                        } else {
                            // copy value to resulting array.
                            $array[$key] = $arg[$key]; 
                        }
                    }
                }
            }
        }

        return $array;
    }

    /**
     * Sets the MailChimp API response.
     * @param  ResponseInterface $response              PSR-7 response interface.
     * @return SlickLabs\MailChimp\ResponseInterface    the MailChimp API response.
     */
    protected function formatResponse(ResponseInterface $response)
    {
        $mailChimpResponse = new Response($response);

        $body = $mailChimpResponse->getBody();
        if (isset($body['status']) && $body['status'] !== 200 && isset($body['detail'])) {
            throw new ResponseException($mailChimpResponse);
        }

        return $mailChimpResponse;
    }

}