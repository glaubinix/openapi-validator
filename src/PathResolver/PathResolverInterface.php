<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\PathResolver;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;

interface PathResolverInterface
{
    public function getOpenApiPath(RequestAdapterInterface $request): string;
}
