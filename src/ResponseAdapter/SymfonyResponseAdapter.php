<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\ResponseAdapter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SymfonyResponseAdapter implements ResponseAdapterInterface
{
    /** @var Response */
    private $response;
    /** @var Request */
    private $request;

    public function __construct(Response $response, Request $request)
    {
        $this->response = $response;
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

    public function getMediaType(): string
    {
        return $this->response->headers->get('Content-Type');
    }

    public function getHeaders(): array
    {
        return $this->response->headers->all();
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getContent(): string
    {
        return $this->response->getContent();
    }

    public function getRouteName(): ?string
    {
        return $this->request->attributes->get('_route');
    }
}
