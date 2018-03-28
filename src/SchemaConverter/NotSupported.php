<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

class NotSupported implements ConverterInterface
{
    private const NOT_SUPPORTED = [
        'discriminator',
        'readOnly',
        'writeOnly',
        'xml',
        'externalDocs',
        'example',
        'deprecated',
    ];

    public function convert(\stdClass $schema, array $options = []): ?\stdClass
    {
        foreach (self::NOT_SUPPORTED as $property) {
            unset($schema->{$property});
        }

        return $schema;
    }
}
