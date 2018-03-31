<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

class ParameterConverter
{
    public function convert(array $parameters): \stdClass
    {
        $properties = new \stdClass();
        $required = [];

        foreach ($parameters as $parameter) {
            $properties->{$parameter->name} = (object)$parameter->schema;
            if (property_exists($parameter, 'required') && $parameter->required) {
                $required[] = $parameter->name;
            }
        }

        return (object)[
            'type' => 'object',
            'required' => $required,
            'properties' => $properties,
            'additionalProperties' => true,
        ];
    }
}
