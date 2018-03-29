<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\RequestAdapter;

interface RequestAdapterInterface
{
    public function getMethod(): string;
    public function getContent(): string;
    public function getMediaType(): string;
    public function getHeaderParameters(): array;
    public function getQueryParameters(): array;
    public function getPathParameters(): array;
    public function getCookieParameters(): array;
}
