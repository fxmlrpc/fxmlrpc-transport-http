<?php

namespace spec\fXmlRpc\Client\Transport;

use Http\Adapter\HttpAdapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HttpAdapterTransportSpec extends ObjectBehavior
{
    function let(HttpAdapter $httpAdapter)
    {
        $this->beConstructedWith($httpAdapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('fXmlRpc\Client\Transport\HttpAdapterTransport');
    }

    function it_is_a_transport()
    {
        $this->shouldImplement('fXmlRpc\Client\Transport');
    }

    function it_sends_a_request(HttpAdapter $httpAdapter, ResponseInterface $response, StreamInterface $body)
    {
        $endpoint = 'http://bin.fxmlrpc.org';
        $payload = 'payload';

        $body->__toString()->willReturn('body');

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($body);

        $httpAdapter->post($endpoint, Argument::type('array'), $payload)->willReturn($response);

        $this->send($endpoint, $payload)->shouldReturn('body');
    }

    function it_throws_an_exception_when_http_fails(HttpAdapter $httpAdapter)
    {
        $endpoint = 'http://bin.fxmlrpc.org';
        $payload = 'payload';

        $httpAdapter->post($endpoint, Argument::type('array'), $payload)->willThrow('Http\Adapter\HttpAdapterException');

        $this->shouldThrow('fXmlRpc\Client\Exception\TransportError')->duringSend($endpoint, $payload);
    }

    function it_throws_an_exception_when_request_fails(HttpAdapter $httpAdapter, ResponseInterface $response)
    {
        $endpoint = 'http://bin.fxmlrpc.org';
        $payload = 'payload';

        $response->getStatusCode()->willReturn(500);
        $response->getReasonPhrase()->willReturn('Server Error');

        $httpAdapter->post($endpoint, Argument::type('array'), $payload)->willReturn($response);

        $this->shouldThrow('fXmlRpc\Client\Exception\HttpException')->duringSend($endpoint, $payload);
    }
}
