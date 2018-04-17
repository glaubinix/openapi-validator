<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\PathResolver;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\RequestAdapter\SymfonyRequestAdapter;
use Glaubinix\OpenAPI\ResponseAdapter\SymfonyResponseAdapter;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class SymfonyPathResolverTest extends TestCase
{
    /** @var SymfonyPathResolver */
    private $resolver;
    private $fallbackResolver;
    private $routeCollection;

    protected function setUp(): void
    {
        $this->fallbackResolver = $this->getMockBuilder(PathResolverInterface::class)->getMock();
        $this->routeCollection = $this->getMockBuilder(RouteCollection::class)->disableOriginalConstructor()->getMock();
        $this->resolver = new SymfonyPathResolver($this->routeCollection, $this->fallbackResolver);
    }

    /**
     * @dataProvider pathProvider
     */
    public function testGetOpenApiPath(string $requestResponseClass): void
    {
        $requestResponse = $this->getMockBuilder($requestResponseClass)->disableOriginalConstructor()->getMock();
        $schema = $this->getMockBuilder(SchemaInterface::class)->getMock();

        $this->fallbackResolver
            ->expects($this->never())
            ->method('getOpenApiPath');

        $path = '/api/schemas';
        $routeName = 'route';
        $route = new Route($path);

        $requestResponse
            ->expects($this->any())
            ->method('getRouteName')
            ->will($this->returnValue($routeName));

        $this->routeCollection
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($routeName))
            ->will($this->returnValue($route));

        $actual = $this->resolver->getOpenApiPath($requestResponse, $schema);

        $this->assertSame($path, $actual);
    }

    public function pathProvider()
    {
        return [
            [SymfonyRequestAdapter::class],
            [SymfonyResponseAdapter::class],
        ];
    }

    public function testGetOpenApiPathFallback(): void
    {
        $request = $this->getMockBuilder(RequestAdapterInterface::class)->disableOriginalConstructor()->getMock();
        $schema = $this->getMockBuilder(SchemaInterface::class)->getMock();

        $path = '/api/schemas';

        $this->fallbackResolver
            ->expects($this->once())
            ->method('getOpenApiPath')
            ->with($this->equalTo($request), $this->equalTo($schema))
            ->will($this->returnValue($path));

        $this->routeCollection
            ->expects($this->never())
            ->method('get');

        $actual = $this->resolver->getOpenApiPath($request, $schema);

        $this->assertSame($path, $actual);
    }
}
