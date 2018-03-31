<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

class OpenAPIRequest
{
    /** @var mixed[] */
    private $query;
    /** @var mixed[] */
    private $path;
    /** @var mixed[] */
    private $headers;
    /** @var mixed[] */
    private $cookies;
    /** @var mixed */
    private $content;

    public function __construct(array $query, array $path, array $headers, array $cookies, $content)
    {
        $this->query = $query;
        $this->path = $path;
        $this->headers = $headers;
        $this->cookies = $cookies;
        $this->content = $content;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getPath(): array
    {
        return $this->path;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getContent()
    {
        return $this->content;
    }
}
