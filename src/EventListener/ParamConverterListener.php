<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\EventListener;

use Glaubinix\OpenAPI\OpenAPIRequest;
use Glaubinix\OpenAPI\RequestAdapter\SymfonyRequestAdapter;
use Glaubinix\OpenAPI\RequestValidator;
use Glaubinix\OpenAPI\SchemaLoader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ParamConverterListener
{
    /** @var RequestValidator */
    private $requestValidator;
    /** @var SchemaLoader */
    private $schemaLoader;
    /** @var string */
    private $schemaFile;

    public function __construct(RequestValidator $requestValidator, SchemaLoader $schemaLoader, string $schemaFile)
    {
        $this->requestValidator = $requestValidator;
        $this->schemaLoader = $schemaLoader;
        $this->schemaFile = $schemaFile;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $request = $event->getRequest();
        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } else {
            $r = new \ReflectionFunction($controller);
        }

        foreach ($r->getParameters() as $param) {
            if (!$param->getClass() || $param->getClass()->isInstance($request)) {
                continue;
            }
            $class = $param->getClass()->getName();
            $name = $param->getName();
            if (OpenAPIRequest::class === $class) {
                $schema = $this->schemaLoader->loadSchema($this->schemaFile);
                $request->attributes->set($name, $this->requestValidator->validate($schema, new SymfonyRequestAdapter($request)));
            }
        }
    }
}
