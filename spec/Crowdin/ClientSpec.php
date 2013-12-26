<?php

namespace spec\Crowdin;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Akeneo', 'my_key');
    }

    function it_has_a_project_identifier()
    {
        $this->getProjectIdentifier()->shouldReturn('Akeneo');
    }

    function it_has_a_project_api_key()
    {
        $this->getProjectApiKey()->shouldReturn('my_key');
    }

    function it_has_a_http_client()
    {
        $this->getHttpClient()->shouldBeAnInstanceOf('Guzzle\Http\Client');
    }

    function it_allow_defined_api_method()
    {
        $this->api('download')->shouldReturnAnInstanceOf('Crowdin\Api\Download');
    }

    function it_should_not_allow_undefined_api_method()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringApi('unknow');
    }
}
