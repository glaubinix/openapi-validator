<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

class TypeConverter implements ConverterInterface
{
    public function convert(\stdClass $schema, array $options = []): ?\stdClass
    {
        if (!property_exists($schema, 'type')) {
            return $schema;
        }

        $format = property_exists($schema, 'format') ? $schema->format : null;
        if ($schema->type === 'string' && $format === 'date' && $options['dateToDateTime'] ?? false) {
            $format = 'date-time';
        }

        switch ($schema->type) {
            case 'integer':
                $type = 'integer';
                break;
            case 'long':
                $type = 'integer';
                $format = 'int64';
                break;
            case 'float':
                $type = 'number';
                $format = 'float';
                break;
            case 'double':
                $type = 'number';
                $format = 'double';
                break;
            case 'byte':
                $type = 'string';
                $format = 'byte';
                break;
            case 'binary':
                $type = 'string';
                $format = 'binary';
                break;
            case 'date':
                $type = 'string';
                $format = 'date';
                if ($options['dateToDateTime'] ?? false) {
                    $format = 'date-time';
                }
                break;
            case 'dateTime':
                $type = 'string';
                $format = 'date-time';
                break;
            case 'password':
                $type = 'string';
                $format = 'password';
                break;
            default:
                $type = $schema->type;
        }

        $schema->type = $type;
        $schema->format = $format;

        if (!$schema->format) {
            unset($schema->format);
        }

        if (property_exists($schema, 'nullable') && $schema->nullable === true) {
            $schema->type = [$schema->type, 'null'];
            unset($schema->nullable);
        }

        return $schema;
    }
}
