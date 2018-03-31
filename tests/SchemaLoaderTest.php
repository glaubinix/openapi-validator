<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use Glaubinix\OpenAPI\Schema\SchemaInterface;
use League\JsonReference\DereferencerInterface;
use PHPUnit\Framework\TestCase;

class SchemaLoaderTest extends TestCase
{
    /** @var SchemaLoader */
    private $loader;
    private $dereferencer;

    protected function setUp(): void
    {
        $this->dereferencer = $this->getMockBuilder(DereferencerInterface::class)->getMock();
        $this->loader = new SchemaLoader($this->dereferencer);
    }

    public function testLoadSchema(): void
    {
        $pathToSchema = '/path/to/schema/openapi.json';
        $schema = new \stdClass();
        $schema->openapi = '3.0.0';

        $this->dereferencer
            ->expects($this->once())
            ->method('dereference')
            ->with($this->equalTo($pathToSchema))
            ->will($this->returnValue($schema));

        $actual = $this->loader->loadSchema($pathToSchema);

        $this->assertInstanceOf(SchemaInterface::class, $actual);
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\UnsupportedSchemaVersionException
     */
    public function testLoadSchemaUnsupported(): void
    {
        $pathToSchema = '/path/to/schema/openapi.json';
        $schema = new \stdClass();

        $this->dereferencer
            ->expects($this->once())
            ->method('dereference')
            ->with($this->equalTo($pathToSchema))
            ->will($this->returnValue($schema));

        $this->loader->loadSchema($pathToSchema);
    }
}
