<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

class TypeConverter implements ConverterInterface
{
    public function convert(\stdClass $schema, array $options = []): ?\stdClass
    {
        if (!property_exists($schema, 'type')) {
            return $schema;
        }

        if (property_exists($schema, 'nullable') && $schema->nullable === true) {
            $schema->type = [$schema->type, 'null'];
            unset($schema->nullable);
        }

        return $schema;
    }
}
