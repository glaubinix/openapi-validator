<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;

class CookieParametersValidator extends ParametersValidator
{
    public function validate(SchemaInterface $schema, RequestAdapterInterface $request, string $path)
    {
        return $this->doValidate((object) $request->getCookieParameters(), $schema->getCookieParameters($path, $request->getMethod()));
    }
}
