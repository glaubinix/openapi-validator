<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

use Glaubinix\OpenAPI\ParameterConverter;
use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use Glaubinix\OpenAPI\SchemaConverter;
use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;

abstract class AbstractParametersValidatorTest extends TestCase
{
    protected $converter;
    protected $jsonSchemaValidator;
    protected $parameterConverter;
    protected $schema;
    protected $request;

    protected $validator;
    protected $requestMethod;
    protected $schemaMethod;

    protected function setUp(): void
    {
        $this->converter = $this->getMockBuilder(SchemaConverter::class)->disableOriginalConstructor()->getMock();
        $this->jsonSchemaValidator = $this->getMockBuilder(Validator::class)->disableOriginalConstructor()->getMock();
        $this->parameterConverter = $this->getMockBuilder(ParameterConverter::class)->disableOriginalConstructor()->getMock();
        $this->schema = $this->getMockBuilder(SchemaInterface::class)->getMock();
        $this->request = $this->getMockBuilder(RequestAdapterInterface::class)->getMock();
    }

    public function testValidate(): void
    {
        $path = '/';
        $method = 'GET';
        $content = ['key' => 'value'];

        $parameters = [];

        $this->setupValidation($method, (object)$content, $parameters);

        $this->request
            ->expects($this->once())
            ->method($this->requestMethod)
            ->will($this->returnValue($content));

        $this->schema
            ->expects($this->once())
            ->method($this->schemaMethod)
            ->with($this->equalTo($path), $this->equalTo($method))
            ->will($this->returnValue($parameters));

        $this->jsonSchemaValidator
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->jsonSchemaValidator
            ->expects($this->never())
            ->method('getErrors');

        $actual = $this->validator->validate($this->schema, $this->request, $path);

        $this->assertSame($content, (array)$actual);
    }

    /**
     * @expectedException \Glaubinix\OpenAPI\Exception\ValidationException
     */
    public function testValidateErrors(): void
    {
        $path = '/';
        $method = 'GET';
        $content = ['key' => 'value'];

        $parameters = [];

        $this->setupValidation($method, (object)$content, $parameters);

        $this->request
            ->expects($this->once())
            ->method($this->requestMethod)
            ->will($this->returnValue($content));

        $this->schema
            ->expects($this->once())
            ->method($this->schemaMethod)
            ->with($this->equalTo($path), $this->equalTo($method))
            ->will($this->returnValue($parameters));

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

    protected function setupValidation($method, $content, array $parameters): void
    {
        $jsonSchema = new \stdClass();
        $openApiSchema = new \stdClass();

        $this->parameterConverter
            ->expects($this->once())
            ->method('convert')
            ->with($this->equalTo($parameters))
            ->will($this->returnValue($openApiSchema));

        $this->converter
            ->expects($this->once())
            ->method('convert')
            ->with($this->equalTo($openApiSchema))
            ->will($this->returnValue($jsonSchema));

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($method));

        $this->jsonSchemaValidator
            ->expects($this->once())
            ->method('reset');

        $this->jsonSchemaValidator
            ->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($content), $this->equalTo($jsonSchema));
    }
}
