<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\PathResolver;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use PHPUnit\Framework\TestCase;

class PathResolverTest extends TestCase
{
    /** @var PathResolver */
    private $resolver;

    protected function setUp(): void
    {
        $this->resolver = new PathResolver();
    }

    public function testGetOpenApiPath(): void
    {
        $request = $this->getMockBuilder(RequestAdapterInterface::class)->getMock();
        $request
            ->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue('get'));

        $request
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue('/api/schemas/1/'));

        $schema = $this->getMockBuilder(SchemaInterface::class)->getMock();

        $schema
            ->expects($this->once())
            ->method('getAllPaths')
            ->will($this->returnValue([
                '/' => ['get', 'post'],
                '/api/schemas/{something}/action' => ['get'],
                '/api/schemas/{something}/' => ['get'],
            ]));

        $value = $this->resolver->getOpenApiPath($request, $schema);

        $this->assertSame('/api/schemas/{something}/', $value);
    }

    public function testGetOpenApiPathExactMatch(): void
    {
        $request = $this->getMockBuilder(RequestAdapterInterface::class)->getMock();
        $request
            ->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue('get'));

        $request
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue('/api/schemas/'));

        $schema = $this->getMockBuilder(SchemaInterface::class)->getMock();

        $schema
            ->expects($this->once())
            ->method('getAllPaths')
            ->will($this->returnValue([
                '/api/schemas/' => ['get'],
                '/api/schemas/{something}/action' => ['get'],
                '/api/schemas/{something}/' => ['get'],
            ]));

        $value = $this->resolver->getOpenApiPath($request, $schema);

        $this->assertSame('/api/schemas/', $value);
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\UnsupportedPathException
     */
    public function testGetOpenApiPathUndefined(): void
    {
        $request = $this->getMockBuilder(RequestAdapterInterface::class)->getMock();
        $request
            ->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue('get'));

        $request
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue('/undefined'));

        $schema = $this->getMockBuilder(SchemaInterface::class)->getMock();

        $schema
            ->expects($this->once())
            ->method('getAllPaths')
            ->will($this->returnValue([]));

        $this->resolver->getOpenApiPath($request, $schema);
    }
}
