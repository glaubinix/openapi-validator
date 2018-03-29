<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

use Glaubinix\OpenAPI\Exception\ValidationException;
use Glaubinix\OpenAPI\ParameterConverter;
use Glaubinix\OpenAPI\SchemaConverter;
use JsonSchema\Validator;

abstract class ParametersValidator
{
    /** @var SchemaConverter */
    private $converter;
    /** @var Validator */
    private $validator;
    /** @var ParameterConverter */
    private $parameterConverter;

    public function __construct(SchemaConverter $converter, Validator $validator, ParameterConverter $parameterConverter)
    {
        $this->converter = $converter;
        $this->validator = $validator;
        $this->parameterConverter = $parameterConverter;
    }

    protected function doValidate(\stdClass $value, array $parameters)
    {
        $openApiSchema = $this->parameterConverter->convert($parameters);
        $jsonSchema = $this->converter->convert($openApiSchema);

        $this->validator->reset();
        $this->validator->validate($value, $jsonSchema);
        if (!$this->validator->isValid()) {
            throw new ValidationException($this->validator->getErrors());
        }

        return $value;
    }
}
