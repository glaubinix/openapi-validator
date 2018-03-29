<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;

class PathParametersValidator extends ParametersValidator
{
    public function validate(SchemaInterface $schema, RequestAdapterInterface $request, string $path)
    {
        return $this->doValidate((object) $request->getPathParameters(), $schema->getPathParameters($path, $request->getMethod()));
    }
}
