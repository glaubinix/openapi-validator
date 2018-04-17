<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Validator;

use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\ResponseAdapter\ResponseAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;

class HeaderParametersValidator extends ParametersValidator
{
    public function validate(SchemaInterface $schema, $requestOrResponse, string $path): \stdClass
    {
        if ($requestOrResponse instanceof RequestAdapterInterface) {
            return $this->doValidate((object) $requestOrResponse->getHeaderParameters(), $schema->getHeaderParameters($path, $requestOrResponse->getMethod()));
        }

        if ($requestOrResponse instanceof ResponseAdapterInterface) {
            return $this->doValidate((object) $requestOrResponse->getHeaders(), $schema->getHeaderParameters($path, $requestOrResponse->getMethod()));
        }
    }
}
