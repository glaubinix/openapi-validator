<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\ResponseAdapter;

use Glaubinix\OpenAPI\PathResolver\PathResolvableInterface;

interface ResponseAdapterInterface extends PathResolvableInterface
{
    public function getMethod(): string;
    public function getMediaType(): string;
    public function getHeaders(): array;
    public function getStatusCode(): int;
    public function getContent(): string;
}
