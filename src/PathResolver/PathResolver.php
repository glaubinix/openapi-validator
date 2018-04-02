<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\PathResolver;

use Glaubinix\OpenAPI\Exception\UnsupportedPathException;
use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use Rize\UriTemplate;

class PathResolver implements PathResolverInterface
{
    public function getOpenApiPath(RequestAdapterInterface $request, SchemaInterface $schema): string
    {
        $uriTemplate = new UriTemplate();
        $availablePaths = $schema->getAllPaths();
        foreach ($availablePaths as $path => $methods) {
            if (!in_array($request->getMethod(), $methods, true)) {
                continue;
            }

            if ($path === $request->getPath()) {
                return $path;
            }

            $params = $uriTemplate->extract($path, $request->getPath());
            $constructedPath = str_replace(array_map(function ($param) {
                return '{' . $param . '}';
            }, array_keys($params)), array_values($params), $path);
            if ($constructedPath === $request->getPath()) {
                return $path;
            }
        }

        throw new UnsupportedPathException(sprintf('Unable to resolve path: %s', $request->getPath()));
    }
}
