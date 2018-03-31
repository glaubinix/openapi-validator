<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use Glaubinix\OpenAPI\PathResolver\PathResolverInterface;
use Glaubinix\OpenAPI\RequestAdapter\RequestAdapterInterface;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use Glaubinix\OpenAPI\Validator\CookieParametersValidator;
use Glaubinix\OpenAPI\Validator\HeaderParametersValidator;
use Glaubinix\OpenAPI\Validator\PathParametersValidator;
use Glaubinix\OpenAPI\Validator\QueryParametersValidator;
use Glaubinix\OpenAPI\Validator\RequestBodyValidator;

class RequestValidator
{
    /** @var PathResolverInterface */
    private $pathResolver;
    /** @var RequestBodyValidator */
    private $requestBodyValidator;
    /** @var QueryParametersValidator */
    private $queryParametersValidator;
    /** @var PathParametersValidator */
    private $pathParametersValidator;
    /** @var HeaderParametersValidator */
    private $headerParametersValidator;
    /** @var CookieParametersValidator */
    private $cookieParametersValidator;

    public function __construct(PathResolverInterface $pathResolver, RequestBodyValidator $requestBodyValidator, QueryParametersValidator $queryParametersValidator, PathParametersValidator $pathParametersValidator, HeaderParametersValidator $headerParametersValidator, CookieParametersValidator $cookieParametersValidator)
    {
        $this->pathResolver = $pathResolver;
        $this->requestBodyValidator = $requestBodyValidator;
        $this->queryParametersValidator = $queryParametersValidator;
        $this->pathParametersValidator = $pathParametersValidator;
        $this->headerParametersValidator = $headerParametersValidator;
        $this->cookieParametersValidator = $cookieParametersValidator;
    }

    public function validate(SchemaInterface $schema, RequestAdapterInterface $request): OpenAPIRequest
    {
        $path = $this->pathResolver->getOpenApiPath($request);

        return new OpenAPIRequest(
            (array)$this->queryParametersValidator->validate($schema, $request, $path),
            (array)$this->pathParametersValidator->validate($schema, $request, $path),
            (array)$this->headerParametersValidator->validate($schema, $request, $path),
            (array)$this->cookieParametersValidator->validate($schema, $request, $path),
            $this->requestBodyValidator->validate($schema, $request, $path)
        );
    }
}
