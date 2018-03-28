<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

class CleanRequired implements ConverterInterface
{
    public function convert(\stdClass $schema, array $options = []): ?\stdClass
    {
        if (!property_exists($schema, 'required')) {
            return $schema;
        }

        $required = [];
        foreach ($schema->required as $property) {
            if (property_exists($schema->properties, $property)) {
                $required[] = $property;
            }
        }

        $schema->required = $required;

        return $schema;
    }
}
