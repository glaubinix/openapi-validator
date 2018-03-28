<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Schema;

use Glaubinix\OpenAPI\Exception;

class OpenAPI3 implements SchemaInterface
{
    /** @var \stdClass */
    private $schema;

    public function __construct(object $schema)
    {
        $this->schema = $schema;
    }

    public function getQueryParameters(string $path, string  $method): array
    {
        return $this->getRequestParameters($path, $method, 'query');
    }

    public function getHeaderParameters(string $path, string  $method): array
    {
        return $this->getRequestParameters($path, $method, 'header');
    }

    public function getPathParameters(string $path, string  $method): array
    {
        return $this->getRequestParameters($path, $method, 'path');
    }

    public function getCookieParameters(string $path, string  $method): array
    {
        return $this->getRequestParameters($path, $method, 'cookie');
    }

    private function getRequestParameters(string $path, string $method, string $location): array
    {
        if (!property_exists($this->schema->paths, $path)) {
            throw new Exception\UnsupportedPathException(sprintf('Path %s not supported', $path));
        }

        if (!property_exists($this->schema->paths->{$path}, $method)) {
            throw new Exception\UnsupportedMethodException(sprintf('Method %s not supported for path %s', $method, $path));
        }

        if (!property_exists($this->schema->paths->{$path}->{$method}, 'parameters') && !is_array($this->schema->paths->{$path}->{$method}->parameters)) {
            return [];
        }

        return array_values(array_filter($this->schema->paths->{$path}->{$method}->parameters, function (\stdClass $parameter) use ($location) {
            return $parameter->in === $location;
        }));
    }

    public function getRequestBody(string $path, string $method, string $mediaType): \stdClass
    {
        if (!property_exists($this->schema->paths, $path)) {
            throw new Exception\UnsupportedPathException(sprintf('Path %s not supported', $path));
        }

        if (!property_exists($this->schema->paths->{$path}, $method)) {
            throw new Exception\UnsupportedMethodException(sprintf('Method %s not supported for path %s', $method, $path));
        }

        if (!property_exists($this->schema->paths->{$path}->{$method}->requestBody->content, $mediaType)) {
            throw new Exception\UnsupportedMediaTypeException(sprintf('Media type %s not supported for path %s and method %s', $mediaType, $path, $method));
        }

        return $this->schema->paths->{$path}->{$method}->requestBody->content->{$mediaType}->schema;
    }

    public function getResponseBody(string $path, string $method, int $statusCode, string $mediaType): \stdClass
    {
        if (!property_exists($this->schema->paths, $path)) {
            throw new Exception\UnsupportedPathException(sprintf('Path %s not supported', $path));
        }

        if (!property_exists($this->schema->paths->{$path}, $method)) {
            throw new Exception\UnsupportedMethodException(sprintf('Method %s not supported for path %s', $method, $path));
        }

        if (!property_exists($this->schema->paths->{$path}->{$method}->responses, (string)$statusCode)) {
            throw new Exception\UnsupportedStatusCodeException(sprintf('Status code %s not supported for path %s and method %s', $mediaType, $path, $method));
        }

        if (!property_exists($this->schema->paths->{$path}->{$method}->responses->{$statusCode}->content, $mediaType)) {
            throw new Exception\UnsupportedMediaTypeException(sprintf('Media type %s not supported for path %s and method %s and status code %s', $mediaType, $path, $method, $statusCode));
        }

        return $this->schema->paths->{$path}->{$method}->responses->{$statusCode}->content->{$mediaType}->schema;
    }
}