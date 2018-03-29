<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

class QueryParametersValidatorTest extends AbstractParametersValidatorTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new QueryParametersValidator($this->converter, $this->jsonSchemaValidator, $this->parameterConverter);
        $this->requestMethod = 'getQueryParameters';
        $this->schemaMethod = 'getQueryParameters';
    }
}
