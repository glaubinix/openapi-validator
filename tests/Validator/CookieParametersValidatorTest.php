<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

class CookieParametersValidatorTest extends AbstractParametersValidatorTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new CookieParametersValidator($this->converter, $this->jsonSchemaValidator, $this->parameterConverter);
        $this->requestMethod = 'getCookieParameters';
        $this->schemaMethod = 'getCookieParameters';
    }
}
