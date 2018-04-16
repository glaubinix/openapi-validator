<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\PathResolver;

use Glaubinix\OpenAPI\Schema\SchemaInterface;

interface PathResolverInterface
{
    public function getOpenApiPath(PathResolvableInterface $pathResolvable, SchemaInterface $schema): string;
}
