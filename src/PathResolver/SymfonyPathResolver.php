<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\PathResolver;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\RequestAdapter\SymfonyRequestAdapter;
use Symfony\Component\Routing\Router;

class SymfonyPathResolver implements PathResolverInterface
{
    /** @var Router */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getOpenApiPath(RequestAdapterInterface $request): string
    {
        if ($request instanceof SymfonyRequestAdapter) {
            $routeCollection = $this->router->getRouteCollection();

            return $routeCollection->get($request->getRouteName())->getPath();
        }
    }
}
