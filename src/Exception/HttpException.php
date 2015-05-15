<?php

/*
 * This file is part of the fXmlRpc HTTP Transport package.
 *
 * (c) Lars Strojny <lstrojny@php.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fXmlRpc\Client\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Thrown if an HTTP error occures
 *
 * @author Lars Strojny <lstrojny@php.net>
 */
final class HttpException extends \RuntimeException implements TransportException
{
    /**
     * @param ResponseInterface $response
     *
     * @return self
     */
    public static function createFromResponse(ResponseInterface $response)
    {
        return new self('An HTTP error occurred: ' . $response->getReasonPhrase(), $response->getStatusCode());
    }
}
