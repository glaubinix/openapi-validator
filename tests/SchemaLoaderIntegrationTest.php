<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use Glaubinix\OpenAPI\Schema\SchemaInterface;

class SchemaLoaderIntegrationTest extends IntegrationTest
{
    /** @var SchemaLoader */
    private $schemaLoader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schemaLoader = $this->app['openapi.schema.loader'];
    }

    /**
     * @dataProvider loadDataProvider
     */
    public function testLoadSchema(string $schemaName): void
    {
        $schema = $this->schemaLoader->loadSchema('file://'. __DIR__ . '/schemas/' . $schemaName);

        $this->assertInstanceOf(SchemaInterface::class, $schema);
    }

    public function loadDataProvider(): array
    {
        return [
            ['schemastore-openapi.json'],
        ];
    }
}
