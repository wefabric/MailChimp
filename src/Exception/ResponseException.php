<?php

namespace SlickLabs\MailChimp\Exception;

use InvalidArgumentException;
use SlickLabs\MailChimp\ResponseInterface;

/**
 * MailChim Response Exception
 *
 * @author Leo Flapper <leo.flapper@slicklabs.nl>
 * @version 1.0.0
 */ 
final class ResponseException extends MailChimpException implements ResponseExceptionInterface, ResponseInterface
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
     * The response status code.
     * @var integer
     */
    private $statusCode;

    /**
     * Exception type.
     * @var string
     */
    private $type;

    /**
     * The exception title.
     * @var string
     */
    private $title;

    /**
     * The exception statuscode.
     * @var int|string
     */
    private $status;

    /**
     * Detailed exception message.
     * @var string
     */
    private $detail;

    /**
     * The exception instance.
     * @var string
     */
    private $instance;

    /**
     * Sets the response data and exception message.
     * @param ResponseInterface  $response the MailChimp response.   
     * @param string             $message  optional exception message.
     */
    public function __construct(ResponseInterface $response, $message = '')
    {
        $this->response = $response->getResponse();
        $this->body = $response->getBody();
        $this->statusCode = $response->getStatusCode();

        if(isset($this->body['type'])){
            $this->type = $this->body['type'];
        }

        if(isset($this->body['title'])){
            $this->title = $this->body['title'];
        }

        if(isset($this->body['status'])){
            $this->status = $this->body['status'];
        }

        if(isset($this->body['detail'])){
            $this->detail = $this->body['detail'];
        }

        if(isset($this->body['instance'])){
            $this->instance = $this->body['instance'];
        }

        $message = $message ?: sprintf('%d - %s', $this->getStatus(), $this->getDetail());
        parent::__construct($message);
        
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
    public function getBody()
    {
        return $this->body;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc }
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc }
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc }
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * {@inheritdoc }
     */
    public function getInstance()
    {
        return $this->instance;
    }

}