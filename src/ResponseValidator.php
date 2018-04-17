<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use Glaubinix\OpenAPI\Exception\OpenApiException;
use Glaubinix\OpenAPI\PathResolver\PathResolverInterface;
use Glaubinix\OpenAPI\ResponseAdapter\ResponseAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use Glaubinix\OpenAPI\Validator\HeaderParametersValidator;
use Glaubinix\OpenAPI\Validator\ResponseContentValidator;
use Psr\Log\LoggerInterface;

class ResponseValidator
{
    /** @var PathResolverInterface */
    private $pathResolver;
    /** @var HeaderParametersValidator */
    private $headerParametersValidator;
    /** @var ResponseContentValidator */
    private $responseContentValidator;
    /** @var LoggerInterface */
    private $logger;
    private $throwExceptions = false;

    public function __construct(PathResolverInterface $pathResolver, HeaderParametersValidator $headerParametersValidator, ResponseContentValidator $responseContentValidator, LoggerInterface $logger, bool $throwExceptions = false)
    {
        $this->pathResolver = $pathResolver;
        $this->headerParametersValidator = $headerParametersValidator;
        $this->responseContentValidator = $responseContentValidator;
        $this->logger = $logger;
        $this->throwExceptions = $throwExceptions;
    }

    public function validate(SchemaInterface $schema, ResponseAdapterInterface $response)
    {
        $path = $this->pathResolver->getOpenApiPath($response, $schema);

        try {
            $this->headerParametersValidator->validate($schema, $response, $path);
            $this->responseContentValidator->validate($schema, $response, $path);
        } catch (OpenApiException $e) {
            $this->logger->error('Invalid API response returned', [
                'exception' => $e,
            ]);

            if ($this->throwExceptions) {
                throw $e;
            }
        }
    }
}
