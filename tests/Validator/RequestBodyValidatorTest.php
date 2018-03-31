<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use Glaubinix\OpenAPI\SchemaConverter;
use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;

class RequestBodyValidatorTest extends TestCase
{
    /** @var RequestBodyValidator */
    private $validator;
    private $converter;
    private $jsonSchemaValidator;
    private $schema;
    private $request;

    protected function setUp(): void
    {
        $this->converter = $this->getMockBuilder(SchemaConverter::class)->disableOriginalConstructor()->getMock();
        $this->jsonSchemaValidator = $this->getMockBuilder(Validator::class)->disableOriginalConstructor()->getMock();
        $this->validator = new RequestBodyValidator($this->converter, $this->jsonSchemaValidator);
        $this->schema = $this->getMockBuilder(SchemaInterface::class)->getMock();
        $this->request = $this->getMockBuilder(RequestAdapterInterface::class)->getMock();
    }

    public function testValidate(): void
    {
        $path = '/';
        $content = 'value';

        $this->setupValidation($path, $content);

        $this->jsonSchemaValidator
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->jsonSchemaValidator
            ->expects($this->never())
            ->method('getErrors');

        $actual = $this->validator->validate($this->schema, $this->request, $path);

        $this->assertSame($content, $actual);
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\ValidationException
     */
    public function testValidateError(): void
    {
        $path = '/';
        $content = 'value';

        $this->setupValidation($path, $content);

        $this->jsonSchemaValidator
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->jsonSchemaValidator
            ->expects($this->once())
            ->method('getErrors')
            ->will($this->returnValue([]));

        $this->validator->validate($this->schema, $this->request, $path);
    }

    private function setupValidation(string $path, $content): void
    {
        $method = 'GET';
        $mediaType = 'application/json';

        $openApiSchema = new \stdClass();
        $jsonSchema = new \stdClass();

        $this->schema
            ->expects($this->once())
            ->method('getRequestBody')
            ->with($this->equalTo($path), $this->equalTo($method), $this->equalTo($mediaType))
            ->will($this->returnValue($openApiSchema));

        $this->converter
            ->expects($this->once())
            ->method('convert')
            ->with($this->equalTo($openApiSchema))
            ->will($this->returnValue($jsonSchema));

        $this->request
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(json_encode($content)));

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($method));

        $this->request
            ->expects($this->once())
            ->method('getMediaType')
            ->will($this->returnValue($mediaType));

        $this->jsonSchemaValidator
            ->expects($this->once())
            ->method('reset');

        $this->jsonSchemaValidator
            ->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($content), $this->equalTo($jsonSchema));
    }
}
