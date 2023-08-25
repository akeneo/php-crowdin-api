<?php

namespace spec\Akeneo\Crowdin;

use Akeneo\Crowdin\Api\Download;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
        $this->getHttpClient()->shouldBeAnInstanceOf(HttpClientInterface::class);
    }

    public function it_allow_defined_api_method()
    {
        $this->api('download')->shouldReturnAnInstanceOf(Download::class);
    }

    public function it_should_not_allow_undefined_api_method()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->duringApi('unknow');
    }
}
