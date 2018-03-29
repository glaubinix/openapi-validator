<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

class PathParametersValidatorTest extends AbstractParametersValidatorTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new PathParametersValidator($this->converter, $this->jsonSchemaValidator, $this->parameterConverter);
        $this->requestMethod = 'getPathParameters';
        $this->schemaMethod = 'getPathParameters';
    }
}
