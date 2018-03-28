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

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function getContent(): string
    {
        return $this->request->getContent();
    }

    public function getContentType(): string
    {
        return $this->request->headers->get('CONTENT_TYPE');
    }

    public function getHeaders(): array
    {
        // TODO: Implement getHeaders() method.
    }

    public function getQueryParams(): array
    {
        return $this->request->query->all();
    }

    public function getRouteName(): string
    {
        return $this->request->attributes->get('_route');
    }
}
