<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use Glaubinix\OpenAPI\EventListener\ParamConverterListener;
use Glaubinix\OpenAPI\SchemaConverter\CleanRequired;
use Glaubinix\OpenAPI\SchemaConverter\NotSupported;
use Glaubinix\OpenAPI\SchemaConverter\ReadWriteOnly;
use Glaubinix\OpenAPI\SchemaConverter\TypeConverter;
use Glaubinix\OpenAPI\SchemaConverter\XPatternProperties;
use Glaubinix\OpenAPI\Validator\CookieParametersValidator;
use Glaubinix\OpenAPI\Validator\HeaderParametersValidator;
use Glaubinix\OpenAPI\Validator\PathParametersValidator;
use Glaubinix\OpenAPI\Validator\QueryParametersValidator;
use Glaubinix\OpenAPI\Validator\RequestBodyValidator;
use Glaubinix\OpenAPI\Validator\ResponseContentValidator;
use JsonSchema\Validator;
use League\JsonReference\Dereferencer;
use League\JsonReference\ReferenceSerializer\InlineReferenceSerializer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class OpenAPIServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['openapi.validator.response.throwexceptions'] = false;

        $pimple['openapi.validator.response'] = function (Container $pimple) {
            return new ResponseValidator(
                $pimple['openapi.pathresolver'],
                $pimple['openapi.validator.header'],
                $pimple['openapi.validator.responsecontent'],
                $pimple['logger'],
                $pimple['openapi.validator.response.throwexceptions']
            );
        };

        $pimple['openapi.validator.request'] = function (Container $pimple) {
            return new RequestValidator(
                $pimple['openapi.pathresolver'],
                $pimple['openapi.validator.requestbody'],
                $pimple['openapi.validator.query'],
                $pimple['openapi.validator.path'],
                $pimple['openapi.validator.header'],
                $pimple['openapi.validator.cookie']
            );
        };

        $pimple['openapi.validator.cookie'] = function (Container $pimple) {
            return new CookieParametersValidator(
                $pimple['openapi.schema.converter'],
                $pimple['openapi.jsonschema.validator'],
                $pimple['openapi.parameter.converter']
            );
        };

        $pimple['openapi.validator.header'] = function (Container $pimple) {
            return new HeaderParametersValidator(
                $pimple['openapi.schema.converter'],
                $pimple['openapi.jsonschema.validator'],
                $pimple['openapi.parameter.converter']
            );
        };

        $pimple['openapi.validator.path'] = function (Container $pimple) {
            return new PathParametersValidator(
                $pimple['openapi.schema.converter'],
                $pimple['openapi.jsonschema.validator'],
                $pimple['openapi.parameter.converter']
            );
        };

        $pimple['openapi.validator.query'] = function (Container $pimple) {
            return new QueryParametersValidator(
                $pimple['openapi.schema.converter'],
                $pimple['openapi.jsonschema.validator'],
                $pimple['openapi.parameter.converter']
            );
        };

        $pimple['openapi.validator.requestbody'] = function (Container $pimple) {
            return new RequestBodyValidator(
                $pimple['openapi.schema.converter'],
                $pimple['openapi.jsonschema.validator']
            );
        };

        $pimple['openapi.validator.responsecontent'] = function (Container $pimple) {
            return new ResponseContentValidator(
                $pimple['openapi.schema.converter'],
                $pimple['openapi.jsonschema.validator']
            );
        };

        $pimple['openapi.schema.loader'] = function (Container $pimple) {
            return new SchemaLoader($pimple['openapi.jsonschema.dereferencer']);
        };

        $pimple['openapi.schema.converter'] = function () {
            return new SchemaConverter([
                new XPatternProperties(),
                new TypeConverter(),
                new ReadWriteOnly(),
                new CleanRequired(),
                new NotSupported()
            ]);
        };

        $pimple['openapi.parameter.converter'] = function () {
            return new ParameterConverter();
        };

        $pimple['openapi.jsonschema.validator'] = function () {
            return new Validator();
        };

        $pimple['openapi.pathresolver'] = function (Container $pimple) {
            return new PathResolver\SymfonyPathResolver($pimple['routes'], $pimple['openapi.pathresolver.default']);
        };

        $pimple['openapi.pathresolver.default'] = function () {
            return new PathResolver\PathResolver();
        };

        $pimple['openapi.jsonschema.dereferencer'] = function () {
            $dereferencer = new Dereferencer();
            $dereferencer->setReferenceSerializer(new InlineReferenceSerializer());

            return $dereferencer;
        };

        $pimple['openapi.listener.paramconverter'] = function (Container $pimple) {
            return new ParamConverterListener($pimple['openapi.validator.request'], $pimple['openapi.schema.loader'], $pimple['openapi.schema.file']);
        };
    }

    public function subscribe(Container $app, EventDispatcherInterface $dispatcher): void
    {
        $dispatcher->addListener(KernelEvents::CONTROLLER, [$app['openapi.listener.paramconverter'], 'onKernelController'], 128);
    }
}
