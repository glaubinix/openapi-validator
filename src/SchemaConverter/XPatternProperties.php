<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

class XPatternProperties implements ConverterInterface
{
    public function convert(\stdClass $schema, array $options = []): ?\stdClass
    {
        if (property_exists($schema, 'x-patternProperties') && $options['supportPatternProperties'] ?? false) {
            $schema->patternProperties = $schema->{'x-patternProperties'};
        }

        unset($schema->{'x-patternProperties'});

        return $schema;
    }
}
