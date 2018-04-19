<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\PathResolver;

use Glaubinix\OpenAPI\Exception\UnsupportedPathException;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use Rize\UriTemplate;

class PathResolver implements PathResolverInterface
{
    public function getOpenApiPath(PathResolvableInterface $pathResolvable, SchemaInterface $schema): string
    {
        $resolvablePath = parse_url($pathResolvable->getPath(), PHP_URL_PATH);
        $uriTemplate = new UriTemplate();
        $availablePaths = $schema->getAllPaths();
        foreach ($availablePaths as $path => $methods) {
            if (!in_array(strtolower($pathResolvable->getMethod()), $methods, true)) {
                continue;
            }

            if ($path === $resolvablePath) {
                return $path;
            }

            $params = $uriTemplate->extract($path, $resolvablePath);
            $constructedPath = str_replace(array_map(function ($param) {
                return '{' . $param . '}';
            }, array_keys($params)), array_values($params), $path);
            if ($constructedPath === $resolvablePath) {
                return $path;
            }
        }

        throw new UnsupportedPathException(sprintf('Unable to resolve path: %s', $resolvablePath));
    }
}
