<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use PHPUnit\Framework\TestCase;
use Silex\Application;

abstract class IntegrationTest extends TestCase
{
    /** @var Application */
    protected $app;

    protected function setUp(): void
    {
        $this->app = new Application();
        $this->app->register(new OpenAPIServiceProvider(), [
            'openapi.schema.file' => 'file://' .  __DIR__ . '/schemas/schemastore-openapi.json',
        ]);
        $this->app->boot();
    }
}
