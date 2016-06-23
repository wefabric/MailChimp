<?php

namespace SlickLabs\MailChimp\Exception;

/**
 * MailChimp Response Exception Interface
 *
 * @author Leo Flapper <leo.flapper@slicklabs.nl>
 * @version 1.0.0
 */ 
interface ResponseExceptionInterface
{
    
    /**
     * Returns the exception title.
     * @return string $title the exception tile.
     */
    public function getTitle();

    /**
     * Returns the exception type.
     * @return string $type the exception type.
     */
    public function getType();

    /**
     * Returns the exception statuscode.
     * @return string $status the exception statuscode.
     */
    public function getStatus();

    /**
     * Returns the detailed exception message
     * @return string $detail the detailed exception message.
     */
    public function getDetail();

    /**
     * Returns the exception instance.
     * @return string the exception instance.
     */
    public function getInstance();

}