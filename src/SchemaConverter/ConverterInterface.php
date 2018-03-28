<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

interface ConverterInterface
{
    public function convert(\stdClass $schema, array $options = []): ?\stdClass;
}
