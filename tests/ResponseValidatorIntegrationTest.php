<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use Glaubinix\OpenAPI\ResponseAdapter\SymfonyResponseAdapter;
use Silex\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResponseValidatorIntegrationTest extends IntegrationTest
{
    /** @var ResponseValidator */
    private $validator;
    /** @var SchemaLoader */
    private $schemaLoader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->app['openapi.validator.response'];
        $this->schemaLoader = $this->app['openapi.schema.loader'];
    }

    public function testValidate(): void
    {
        $schema = $this->schemaLoader->loadSchema('file://'. __DIR__ . '/schemas/schemastore-openapi.json');

        $request = new Request([], [], [], [], [], [], json_encode(['version' => '3.0.0']));
        $request->attributes->set('_route', 'test');
        $request->headers->set('content-type', 'application/json');

        $response = new JsonResponse(['id' => 1, 'version' => '3.0.0']);

        $this->app['routes']->add('test', new Route('/schemas/{id}'));

        $this->validator->validate($schema, new SymfonyResponseAdapter($response, $request));

        $this->assertTrue(true);
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\ValidationException
     */
    public function testValidateInvalidResponse(): void
    {
        $schema = $this->schemaLoader->loadSchema('file://'. __DIR__ . '/schemas/schemastore-openapi.json');

        $request = new Request([], [], [], [], [], [], json_encode(['version' => '3.0.0']));
        $request->attributes->set('_route', 'test');
        $request->headers->set('content-type', 'application/json');

        $response = new JsonResponse();

        $this->app['routes']->add('test', new Route('/schemas/{id}'));

        $this->validator->validate($schema, new SymfonyResponseAdapter($response, $request));
    }
}
