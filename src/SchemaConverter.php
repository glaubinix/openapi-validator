<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use Glaubinix\OpenAPI\SchemaConverter\ConverterInterface;

/**
 * Convert OpenAPI v3 to JSON schema
 */
class SchemaConverter
{
    private const STRUCTURE_PROPERTIES = [
        'allOf',
        'anyOf',
        'oneOf',
        'not',
        'items',
        'additionalProperties',
    ];

    /** @var ConverterInterface[] */
    private $converters;

    public function __construct(array $converters = [])
    {
        $this->converters = $converters;
    }

    public function convert(\stdClass $schema, array $options = []): ?\stdClass
    {
        $options = array_merge([
            'removeReadOnly' => false,
            'removeWriteOnly' => false,
            'supportPatternProperties' => false,
            'dateToDateTime' => false,
        ], $options);

        foreach (self::STRUCTURE_PROPERTIES as $property) {
            if (property_exists($schema, $property)) {
                if (is_array($schema->{$property})) {
                    foreach ($schema->{$property} as $arrayKey => $arrayValue) {
                        $schema->{$property}[$arrayKey] = $this->convert($arrayValue, $options);
                    }
                } elseif (is_object($schema->{$property})) {
                    $schema->{$property} = $this->convert($schema->{$property}, $options);
                }
            }
        }

        if (property_exists($schema, 'properties')) {
            foreach ($schema->properties as $propertyIdentifier => $property) {
                $schema->properties->$propertyIdentifier = $this->convert($property, $options);
                if (!$schema->properties->$propertyIdentifier) {
                    unset($schema->properties->$propertyIdentifier);
                }
            }
        }

        foreach ($this->converters as $converter) {
            $schema = $converter->convert($schema, $options);
        }

        return $schema;
    }
}
