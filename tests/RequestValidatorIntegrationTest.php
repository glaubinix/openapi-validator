<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use Glaubinix\OpenAPI\RequestAdapter\SymfonyRequestAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class RequestValidatorIntegrationTest extends IntegrationTest
{
    /** @var RequestValidator */
    private $validator;
    /** @var SchemaLoader */
    private $schemaLoader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->app['openapi.validator.request'];
        $this->schemaLoader = $this->app['openapi.schema.loader'];
    }

    public function testValidate(): void
    {
        $schema = $this->schemaLoader->loadSchema('file://'. __DIR__ . '/schemas/schemastore-openapi.json');

        $request = new Request([], [], [], [], [], [], json_encode(['version' => '3.0.0']));
        $request->attributes->set('_route', 'test');
        $request->headers->set('content-type', 'application/json');
        $request->query->set('version', '3.0.0');
        $request->headers->set('limit', 999999);
        $request->cookies->set('tracking', true);

        $this->app['routes']->add('test', new Route('/schemas'));

        $openAPIRequest = $this->validator->validate($schema, new SymfonyRequestAdapter($request));

        $this->assertInstanceOf(OpenAPIRequest::class, $openAPIRequest);
        $this->assertSame(['tracking' => true], $openAPIRequest->getCookies());
        $this->assertSame(['version' => '3.0.0'], $openAPIRequest->getQuery());
        $this->assertSame(['content-type' => 'application/json', 'limit' => 999999], $openAPIRequest->getHeaders());
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\ValidationException
     * @dataProvider invalidDataProvider
     */
    public function testValidateInvalid($version, $limit, $tracking): void
    {
        $schema = $this->schemaLoader->loadSchema('file://'. __DIR__ . '/schemas/schemastore-openapi.json');

        $request = new Request([], [], [], [], [], [], json_encode(['version' => '3.0.0']));
        $request->attributes->set('_route', 'test');
        $request->headers->set('content-type', 'application/json');
        $request->query->set('version', $version);
        $request->headers->set('limit', $limit);
        $request->cookies->set('tracking', $tracking);

        $this->app['routes']->add('test', new Route('/schemas'));

        $this->validator->validate($schema, new SymfonyRequestAdapter($request));
    }

    public function invalidDataProvider(): array
    {
        return [
            ['2.9.9', 999999, true],
            [3, 999999, true],
            ['3.0.0', -1, true],
            ['3.0.0', 999999, 'yes'],
        ];
    }

    public function testValidatePath(): void
    {
        $schema = $this->schemaLoader->loadSchema('file://'. __DIR__ . '/schemas/schemastore-openapi.json');

        $request = new Request();
        $request->attributes->set('_route', 'test');
        $request->headers->set('content-type', 'application/json');
        $request->attributes->set('id', 3);

        $this->app['routes']->add('test', new Route('/schemas/{id}'));

        $openAPIRequest = $this->validator->validate($schema, new SymfonyRequestAdapter($request));

        $this->assertInstanceOf(OpenAPIRequest::class, $openAPIRequest);
        $this->assertSame(['id' => 3], $openAPIRequest->getPath());
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\ValidationException
     */
    public function testValidatePathInvalid(): void
    {
        $schema = $this->schemaLoader->loadSchema('file://'. __DIR__ . '/schemas/schemastore-openapi.json');

        $request = new Request();
        $request->attributes->set('_route', 'test');
        $request->headers->set('content-type', 'application/json');
        $request->attributes->set('id', '54676-4545444');

        $this->app['routes']->add('test', new Route('/schemas/{id}'));

        $this->validator->validate($schema, new SymfonyRequestAdapter($request));
    }

    public function testValidateRequestBody(): void
    {
        $schema = $this->schemaLoader->loadSchema('file://'. __DIR__ . '/schemas/schemastore-openapi.json');

        $request = new Request([], [], [], [], [], [], json_encode(['version' => '3.0.0']));
        $request->attributes->set('_route', 'test');
        $request->headers->set('content-type', 'application/json');
        $request->server->set('REQUEST_METHOD', 'POST');

        $this->app['routes']->add('test', new Route('/schemas'));

        $openAPIRequest = $this->validator->validate($schema, new SymfonyRequestAdapter($request));

        $this->assertInstanceOf(OpenAPIRequest::class, $openAPIRequest);
        $this->assertSame(['version' => '3.0.0'], (array)$openAPIRequest->getContent());
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\ValidationException
     */
    public function testValidateRequestBodyInvalid(): void
    {
        $schema = $this->schemaLoader->loadSchema('file://'. __DIR__ . '/schemas/schemastore-openapi.json');

        $request = new Request([], [], [], [], [], [], json_encode(['version' => 'test']));
        $request->attributes->set('_route', 'test');
        $request->headers->set('content-type', 'application/json');
        $request->server->set('REQUEST_METHOD', 'POST');

        $this->app['routes']->add('test', new Route('/schemas'));

        $this->validator->validate($schema, new SymfonyRequestAdapter($request));
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\ValidationException
     */
    public function testValidateRequestBodyMissingBody(): void
    {
        $schema = $this->schemaLoader->loadSchema('file://'. __DIR__ . '/schemas/schemastore-openapi.json');

        $request = new Request();
        $request->attributes->set('_route', 'test');
        $request->headers->set('content-type', 'application/json');
        $request->server->set('REQUEST_METHOD', 'POST');

        $this->app['routes']->add('test', new Route('/schemas'));

        $this->validator->validate($schema, new SymfonyRequestAdapter($request));
    }
}
