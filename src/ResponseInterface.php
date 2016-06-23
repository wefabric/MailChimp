<?php

namespace SlickLabs\MailChimp;

/**
 * Mailchimp API Response Interface
 *
 * @author Leo Flapper <leo.flapper@slicklabs.nl>
 * @version 1.0.0
 */ 
interface ResponseInterface
{
    
    /**
     * Returns the PSR-7 response object.
     * @return integer the PSR-7 response object.
     */
    public function getResponse();

    /**
     * Returns the HTTP response status code.
     * @return integer the HTTP response status code.
     */
    public function getStatusCode();

    /**
     * Returns the response body.
     * @return array the response body.
     */
    public function getBody();

}