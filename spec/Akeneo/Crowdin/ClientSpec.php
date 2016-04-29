<?php

namespace spec\Akeneo\Crowdin;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('Akeneo', 'my_key');
    }

    public function it_has_a_project_identifier()
    {
        $this->getProjectIdentifier()->shouldReturn('Akeneo');
    }

    public function it_has_a_project_api_key()
    {
        $this->getProjectApiKey()->shouldReturn('my_key');
    }

    public function it_has_a_http_client()
    {
        $this->getHttpClient()->shouldBeAnInstanceOf('GuzzleHttp\Client');
    }

    public function it_allow_defined_api_method()
    {
        $this->api('download')->shouldReturnAnInstanceOf('Akeneo\Crowdin\Api\Download');
    }

    public function it_should_not_allow_undefined_api_method()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringApi('unknow');
    }
}
