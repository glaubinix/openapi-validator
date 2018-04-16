<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

use PHPUnit\Framework\TestCase;

class TypeConverterTest extends TestCase
{
    /** @var TypeConverter */
    private $converter;

    protected function setUp()
    {
        $this->converter = new TypeConverter();
    }

    public function testConvertNullable(): void
    {
        $schema = (object)[
            'type' => 'string',
            'nullable' => true,
        ];

        $this->assertSame(['type' => ['string', 'null']], (array)$this->converter->convert($schema));
    }

    public function testConvertNoConversion(): void
    {
        $schema = (object)[
            'type' => 'string',
        ];

        $this->assertSame(['type' => 'string'], (array)$this->converter->convert($schema));
    }

    public function testConvertNoType(): void
    {
        $schema = (object)[];

        $this->assertSame([], (array)$this->converter->convert($schema));
    }
}
