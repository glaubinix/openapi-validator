<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\PathResolver;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\RequestAdapter\SymfonyRequestAdapter;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class SymfonyPathResolver implements PathResolverInterface
{
    /** @var RouteCollection */
    private $routes;
    /** @var PathResolverInterface */
    private $fallbackResolver;

    public function __construct($router, PathResolverInterface $fallbackResolver)
    {
        if ($router instanceof RouterInterface) {
            $this->routes = $router->getRouteCollection();
        } elseif ($router instanceof RouteCollection) {
            $this->routes = $router;
        }
        $this->fallbackResolver = $fallbackResolver;
    }

    public function getOpenApiPath(RequestAdapterInterface $request, SchemaInterface $schema): string
    {
        if ($request instanceof SymfonyRequestAdapter) {
            return $this->routes->get($request->getRouteName())->getPath();
        }

        return $this->fallbackResolver->getOpenApiPath($request, $schema);
    }
}
