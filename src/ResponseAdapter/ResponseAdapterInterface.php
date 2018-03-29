<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\ResponseAdapter;

interface ResponseAdapterInterface
{
    public function getMethod(): string;
    public function getMediaType(): string;
    public function getHeaders(): array;
    public function getStatusCode(): int;
    public function getContent(): string;
}
