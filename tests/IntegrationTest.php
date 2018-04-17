<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Silex\Application;

abstract class IntegrationTest extends TestCase
{
    /** @var Application */
    protected $app;

    protected function setUp(): void
    {
        $this->app = new Application();
        $this->app['logger'] = new NullLogger();
        $this->app->register(new OpenAPIServiceProvider(), [
            'openapi.schema.file' => 'file://' .  __DIR__ . '/schemas/schemastore-openapi.json',
            'openapi.validator.response.throwexceptions' => true,
        ]);
        $this->app->boot();
    }
}
