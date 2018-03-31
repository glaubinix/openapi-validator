<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\PathResolver;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\RequestAdapter\SymfonyRequestAdapter;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class SymfonyPathResolver implements PathResolverInterface
{
    /** @var RouteCollection */
    private $routes;

    public function __construct($router)
    {
        if ($router instanceof RouterInterface) {
            $this->routes = $router->getRouteCollection();
        } elseif ($router instanceof RouteCollection) {
            $this->routes = $router;
        }
    }

    public function getOpenApiPath(RequestAdapterInterface $request): string
    {
        if ($request instanceof SymfonyRequestAdapter) {
            return $this->routes->get($request->getRouteName())->getPath();
        }
    }
}
