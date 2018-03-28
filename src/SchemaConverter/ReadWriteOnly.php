<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

class ReadWriteOnly implements ConverterInterface
{
    public function convert(\stdClass $schema, array $options = []): ?\stdClass
    {
        if (property_exists($schema, 'writeOnly') && $schema->writeOnly && $options['removeWriteOnly'] ?? false) {
            return null;
        }

        if (property_exists($schema, 'readOnly') && $schema->readOnly && $options['removeReadOnly'] ?? false) {
            return null;
        }

        return $schema;
    }
}
