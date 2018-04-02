<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Schema;

interface SchemaInterface
{
    public function getAllPaths(): array;
    public function getQueryParameters(string $path, string  $method): array;
    public function getHeaderParameters(string $path, string  $method): array;
    public function getPathParameters(string $path, string  $method): array;
    public function getCookieParameters(string $path, string  $method): array;
    public function getRequestBody(string $path, string $method, string $mediaType): object;
    public function getResponseBody(string $path, string $method, int $statusCode, string $mediaType): object;
}
