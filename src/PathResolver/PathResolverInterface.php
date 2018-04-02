<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\PathResolver;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;

interface PathResolverInterface
{
    public function getOpenApiPath(RequestAdapterInterface $request, SchemaInterface $schema): string;
}
