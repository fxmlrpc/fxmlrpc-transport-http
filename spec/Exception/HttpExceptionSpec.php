<?php

namespace spec\fXmlRpc\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use PhpSpec\ObjectBehavior;

class HttpExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Message', 0);
    }

    function it_is_initializable()
    {
        $this->getWrappedObject();
        $this->shouldHaveType('fXmlRpc\Client\Exception\HttpException');
    }

    function it_is_a_transport_exception()
    {
        $this->getWrappedObject();
        $this->shouldImplement('fXmlRpc\Client\Exception\TransportException');
    }

    function it_creates_an_exception_from_a_response(ResponseInterface $response)
    {
        $response->getReasonPhrase()->willReturn('Server Error');
        $response->getStatusCode()->willReturn(500);

        $e = $this->createFromResponse($response);
        $e->shouldHaveType('fXmlRpc\Client\Exception\HttpException');
        $e->getMessage()->shouldReturn('An HTTP error occurred: Server Error');
        $e->getCode()->shouldReturn(500);
    }
}
