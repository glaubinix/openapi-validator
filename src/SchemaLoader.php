<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use Glaubinix\OpenAPI\Exception\UnsupportedSchemaVersionException;
use Glaubinix\OpenAPI\Schema\OpenAPI3;
use Glaubinix\OpenAPI\Schema\SchemaInterface;
use League\JsonReference\DereferencerInterface;

class SchemaLoader
{
    /** @var DereferencerInterface */
    private $dereferencer;

    public function __construct(DereferencerInterface $dereferencer)
    {
        $this->dereferencer = $dereferencer;
    }

    public function loadSchema(string $pathToSchema): SchemaInterface
    {
        $schema = json_decode(json_encode($this->dereferencer->dereference($pathToSchema)));

        $majorVersion = 'undefined';
        if (property_exists($schema, 'openapi')) {
            $majorVersion = (int) $schema->openapi;
        }

        switch ($majorVersion) {
            case 3:
                return new OpenAPI3($schema);
            case 2:
            case 1:
            default:
                throw new UnsupportedSchemaVersionException(sprintf('OpenApi schema version %s is not supported', $majorVersion));
        }
    }
}
