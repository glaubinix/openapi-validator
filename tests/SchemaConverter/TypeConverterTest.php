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

    public function testConvertDateToDateTime()
    {
        $options = [
            'dateToDateTime' => true,
        ];

        $schema = (object)[
            'type' => 'string',
            'format' => 'date',
        ];

        $this->assertSame(['type' => 'string', 'format' => 'date-time'], (array)$this->converter->convert($schema, $options));
    }

    public function testConvertInteger()
    {
        $schema = (object)[
            'type' => 'integer',
        ];

        $this->assertSame(['type' => 'integer'], (array)$this->converter->convert($schema));
    }

    public function testConvertLong()
    {
        $schema = (object)[
            'type' => 'long',
        ];

        $this->assertSame(['type' => 'integer', 'format' => 'int64'], (array)$this->converter->convert($schema));
    }

    public function testConvertFloat()
    {
        $schema = (object)[
            'type' => 'float',
        ];

        $this->assertSame(['type' => 'number', 'format' => 'float'], (array)$this->converter->convert($schema));
    }

    public function testConvertDouble()
    {
        $schema = (object)[
            'type' => 'byte',
        ];

        $this->assertSame(['type' => 'string', 'format' => 'byte'], (array)$this->converter->convert($schema));
    }

    public function testConvertBinary()
    {
        $schema = (object)[
            'type' => 'binary',
        ];

        $this->assertSame(['type' => 'string', 'format' => 'binary'], (array)$this->converter->convert($schema));
    }

    public function testConvertDate()
    {
        $schema = (object)[
            'type' => 'date',
        ];

        $this->assertSame(['type' => 'string', 'format' => 'date'], (array)$this->converter->convert($schema));
    }

    public function testConvertDateUsingDateToDateTime()
    {
        $options = [
            'dateToDateTime' => true,
        ];

        $schema = (object)[
            'type' => 'date',
        ];

        $this->assertSame(['type' => 'string', 'format' => 'date-time'], (array)$this->converter->convert($schema, $options));
    }

    public function testConvertDateTime()
    {
        $schema = (object)[
            'type' => 'dateTime',
        ];

        $this->assertSame(['type' => 'string', 'format' => 'date-time'], (array)$this->converter->convert($schema));
    }

    public function testConvertPassword()
    {
        $schema = (object)[
            'type' => 'password',
        ];

        $this->assertSame(['type' => 'string', 'format' => 'password'], (array)$this->converter->convert($schema));
    }

    public function testConvertNullable()
    {
        $schema = (object)[
            'type' => 'string',
            'nullable' => true,
        ];

        $this->assertSame(['type' => ['string', 'null']], (array)$this->converter->convert($schema));
    }

    public function testConvertNoConversion()
    {
        $schema = (object)[
            'type' => 'string',
        ];

        $this->assertSame(['type' => 'string'], (array)$this->converter->convert($schema));
    }

    public function testConvertNoType()
    {
        $schema = (object)[
        ];

        $this->assertSame([], (array)$this->converter->convert($schema));
    }
}
