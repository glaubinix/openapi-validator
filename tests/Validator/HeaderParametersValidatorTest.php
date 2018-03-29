<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

class HeaderParametersValidatorTest extends AbstractParametersValidatorTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new HeaderParametersValidator($this->converter, $this->jsonSchemaValidator, $this->parameterConverter);
        $this->requestMethod = 'getHeaderParameters';
        $this->schemaMethod = 'getHeaderParameters';
    }
}
