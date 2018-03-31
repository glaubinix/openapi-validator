<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

use PHPUnit\Framework\TestCase;

class ReadWriteOnlyTest extends TestCase
{
    /** @var ReadWriteOnly */
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new ReadWriteOnly();
    }

    public function testConvertReadOnlyRemoveReadOnly(): void
    {
        $options = [
            'removeWriteOnly' => false,
            'removeReadOnly' => true,
        ];

        $schema = (object)[
            'type' => 'string',
            'readOnly' => true,
        ];

        $this->assertNull($this->converter->convert($schema, $options));
    }

    public function testConvertReadOnlyRemoveWriteOnly(): void
    {
        $options = [
            'removeWriteOnly' => true
        ];

        $schema = (object)[
            'type' => 'string',
            'writeOnly' => true,
        ];

        $this->assertNull($this->converter->convert($schema, $options));
    }
}
