<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

use Glaubinix\OpenAPI\Exception\ValidationException;
use Glaubinix\OpenAPI\ResponseAdapter\ResponseAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use Glaubinix\OpenAPI\SchemaConverter;
use JsonSchema\Validator;

class ResponseContentValidator
{
    /** @var SchemaConverter */
    private $converter;
    /** @var Validator */
    private $validator;

    public function __construct(SchemaConverter $converter, Validator $validator)
    {
        $this->converter = $converter;
        $this->validator = $validator;
    }

    public function validate(SchemaInterface $schema, ResponseAdapterInterface $response, string $path)
    {
        $openApiSchema = $schema->getResponseBody($path, $response->getMethod(), $response->getStatusCode(), $response->getMediaType());
        $jsonSchema = $this->converter->convert($openApiSchema);

        $content = $response->getContent();
        $value = $content ? json_decode($content) : $content;
        if (json_last_error()) {
            throw new JsonException(json_last_error_msg());
        }

        $this->validator->reset();
        $this->validator->validate($value, $jsonSchema);
        if (!$this->validator->isValid()) {
            throw new ValidationException($this->validator->getErrors());
        }

        return $value;
    }
}
