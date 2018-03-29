<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;

class QueryParametersValidator extends ParametersValidator
{
    public function validate(SchemaInterface $schema, RequestAdapterInterface $request, string $path)
    {
        return $this->doValidate((object) $request->getQueryParameters(), $schema->getQueryParameters($path, $request->getMethod()));
    }
}
