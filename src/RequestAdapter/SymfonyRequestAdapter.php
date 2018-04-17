<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\RequestAdapter;

use Symfony\Component\HttpFoundation\Request;

class SymfonyRequestAdapter implements RequestAdapterInterface
{
    /** @var Request */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getPath(): string
    {
        return $this->request->getRequestUri();
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function getContent(): string
    {
        return $this->request->getContent();
    }

    public function getMediaType(): string
    {
        return $this->request->headers->get('CONTENT_TYPE');
    }

    public function getHeaderParameters(): array
    {
        return array_map(function ($header) {
            return \count($header) ? $header[0] : $header;
        }, $this->request->headers->all());
    }

    public function getQueryParameters(): array
    {
        return $this->request->query->all();
    }

    public function getPathParameters(): array
    {
        $parameters = $this->request->attributes->all();
        unset($parameters['_route']);

        return $parameters;
    }

    public function getCookieParameters(): array
    {
        return $this->request->cookies->all();
    }

    public function getRouteName(): ?string
    {
        return $this->request->attributes->get('_route');
    }
}
