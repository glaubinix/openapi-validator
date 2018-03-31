<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Schema;

use PHPUnit\Framework\TestCase;

class OpenAPI3Test extends TestCase
{
    /** @var OpenAPI3 */
    private $schema;

    protected function setUp(): void
    {
        $this->schema = new OpenAPI3(json_decode(json_encode([
            'paths' => [
                '/' => [
                    'post' => [
                        'parameters' => [
                            [
                                'name' => 'query',
                                'in' => 'query',
                                'schema' => [
                                    'type' => 'string',
                                ],
                            ],
                            [
                                'name' => 'cookie',
                                'in' => 'cookie',
                                'schema' => [
                                    'type' => 'string',
                                ],
                            ],
                            [
                                'name' => 'path',
                                'in' => 'path',
                                'schema' => [
                                    'type' => 'string',
                                ],
                            ],
                            [
                                'name' => 'header',
                                'in' => 'header',
                                'schema' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/Request'
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/Response'
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ])));
    }

    public function testGetQueryParameters(): void
    {
        $this->assertSame(json_encode([['name' => 'query', 'in' => 'query', 'schema' => ['type' => 'string']]]), json_encode($this->schema->getQueryParameters('/', 'post')));
    }

    public function testGetHeaderParameters(): void
    {
        $this->assertSame(json_encode([['name' => 'header', 'in' => 'header', 'schema' => ['type' => 'string']]]), json_encode($this->schema->getHeaderParameters('/', 'post')));
    }

    public function testGetPathParameters(): void
    {
        $this->assertSame(json_encode([['name' => 'path', 'in' => 'path', 'schema' => ['type' => 'string']]]), json_encode($this->schema->getPathParameters('/', 'post')));
    }

    public function testGetCookieParameters(): void
    {
        $this->assertSame(json_encode([['name' => 'cookie', 'in' => 'cookie', 'schema' => ['type' => 'string']]]), json_encode($this->schema->getCookieParameters('/', 'post')));
    }

    public function testGetRequestBody(): void
    {
        $this->assertSame(['$ref' => '#/components/schemas/Request'], (array)$this->schema->getRequestBody('/', 'post', 'application/json'));
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\UnsupportedPathException
     */
    public function testGetRequestBodyUndefinedPath(): void
    {
        $this->schema->getRequestBody('/api', 'post', 'application/json');
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\UnsupportedMethodException
     */
    public function testGetRequestBodyUndefinedMethod(): void
    {
        $this->schema->getRequestBody('/', 'put', 'application/json');
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\UnsupportedMediaTypeException
     */
    public function testGetRequestBodyUndefinedMediaType(): void
    {
        $this->schema->getRequestBody('/', 'post', 'text/html');
    }

    public function testGetResponseBody(): void
    {
        $this->assertSame(['$ref' => '#/components/schemas/Response'], (array)$this->schema->getResponseBody('/', 'post', 200, 'application/json'));
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\UnsupportedPathException
     */
    public function testGetResponseBodyUndefinedPath(): void
    {
        $this->schema->getResponseBody('/api', 'post', 200, 'application/json');
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\UnsupportedMethodException
     */
    public function testGetResponseBodyUndefinedMethod(): void
    {
        $this->schema->getResponseBody('/', 'put', 200, 'application/json');
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\UnsupportedStatusCodeException
     */
    public function testGetResponseBodyUndefinedStatusCode(): void
    {
        $this->schema->getResponseBody('/', 'post', 404, 'application/json');
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\UnsupportedMediaTypeException
     */
    public function testGetResponseBodyUndefinedMediaType(): void
    {
        $this->schema->getResponseBody('/', 'post', 200, 'text/html');
    }
}
