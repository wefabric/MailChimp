<?php

namespace SlickLabs\MailChimp;

/**
 * 
 * MailChimp API Response
 *
 * @author Leo Flapper <leo.flapper@slicklabs.nl>
 * @version 1.0.0
 */
class Response implements ResponseInterface
{

    /**
     * Te PSR-7 response object.
     * @return Psr\Http\Message\ResponseInterface
     */
    private $response;

    /**
     * The response body.
     * @var array
     */
    private $body;

    /**
     * The HTTP response status code.
     * @var integer
     */
    private $statusCode;

    /**
     * Sets the response data.
     * @param Psr\Http\Message\ResponseInterface $response the response object.
     */
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        $this->response = $response;

        $body = (string)$response->getBody();
        $this->body = \GuzzleHttp\json_decode($body, true);

        $this->statusCode = $response->getStatusCode();
    }

    /**
     * {@inheritdoc }
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * {@inheritdoc }
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc }
     */
    public function getBody()
    {
        return $this->body;
    }

}