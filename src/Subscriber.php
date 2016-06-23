<?php

namespace SlickLabs\MailChimp;

use InvalidArgumentException;

/**
 * 
 * MailChimp Subscriber
 *
 * Subscriber related methods.
 * 
 * @author Leo Flapper <leo.flapper@slicklabs.nl>
 * @version 1.0.0
 */
final class Subscriber
{

    /**
     * Converts the subscriber email address to a hash for identifying
     * the subscriber.
     * @param   string $email   the email address.
     * @return  string          hashed version of the email address.
     */
    public static function hash($email)
    {
        if (!is_string($email)) {
            throw new InvalidArgumentException(sprintf(
                '%s: expects a string argument; received "%s"',
                __METHOD__,
                (is_object($email) ? get_class($email) : gettype($email))
            ));
        }

        return md5(strtolower($email));
    }

}