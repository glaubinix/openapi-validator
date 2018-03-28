<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\RequestAdapter;

interface RequestAdapterInterface
{
    public function getMethod(): string;
    public function getContent(): string;
    public function getContentType(): string;
    public function getHeaders(): array ;
    public function getQueryParams(): array;
}
