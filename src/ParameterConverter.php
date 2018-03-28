<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

class ParameterConverter
{
    public function convert(array $parameters): \stdClass
    {
        $properties = new \stdClass();
        $required = [];

        foreach ($parameters as $parameter) {
            $properties->{$parameter->name} = (object)[
                'type' => $parameter->type,
            ];
            if (property_exists($parameter, 'format')) {
                $properties->{$parameter->name}->format = $parameter->format;
            }

            if (property_exists($parameter, 'required') && $parameter->required) {
                $required[] = $parameter->name;
            }

            if (property_exists($parameter, 'enum')) {
                $properties->{$parameter->name}->enum = $parameter->enum;
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
