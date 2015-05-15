<?php

/*
 * This file is part of the fXmlRpc HTTP Transport package.
 *
 * (c) Lars Strojny <lstrojny@php.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fXmlRpc\Client\Transport;

use fXmlRpc\Client\Exception\HttpException;
use fXmlRpc\Client\Exception\TransportError;
use fXmlRpc\Client\Transport;
use Http\Adapter\HttpAdapterException;
use Http\Adapter\HttpAdapter;

/**
 * @author Lars Strojny <lstrojny@php.net>
 */
final class HttpAdapterTransport implements Transport
{
    /**
     * @var HttpAdapter
     */
    private $httpAdapter;

    /**
     * @param HttpAdapter $httpAdapter
     */
    public function __construct(HttpAdapter $httpAdapter)
    {
        $this->httpAdapter = $httpAdapter;
    }

    /**
     * {@inheritdoc}
     */
    public function send($endpoint, $payload)
    {
        try {
            $response = $this->httpAdapter->post($endpoint, ['Content-Type' => 'text/xml; charset=UTF-8'], $payload);
        } catch (HttpAdapterException $e) {
            throw TransportError::createFromException($e);
        }

        if ($response->getStatusCode() !== 200) {
            throw HttpException::createFromResponse($response);
        }

        return (string) $response->getBody();
    }
}
