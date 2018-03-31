<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

use PHPUnit\Framework\TestCase;

class NotSupportedTest extends TestCase
{
    /** @var NotSupported */
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new NotSupported();
    }

    public function testConvertDiscriminator(): void
    {
        $schema = (object)[
            'type' => 'object',
            'discriminator' => (object)[
                'propertyName' => 'name',
            ],
        ];

        $this->assertSame(json_encode(['type' => 'object']), json_encode($this->converter->convert($schema)));
    }

    public function testConvertReadOnly(): void
    {
        $schema = (object)[
            'type' => 'string',
            'readOnly' => true,
        ];

        $this->assertSame(['type' => 'string'], (array)$this->converter->convert($schema));
    }

    public function testConvertWriteOnly(): void
    {
        $schema = (object)[
            'type' => 'string',
            'writeOnly' => true,
        ];

        $this->assertSame(['type' => 'string'], (array)$this->converter->convert($schema));
    }

    public function testXml(): void
    {
        $schema = (object)[
            'type' => 'string',
            'xml' => (object)[
                'name' => 'converter',
            ],
        ];

        $this->assertSame(['type' => 'string'], (array)$this->converter->convert($schema));
    }

    public function testExternalDocs(): void
    {
        $schema = (object)[
            'type' => 'object',
            'externalDocs' => (object)[
                'url' => 'https://example.com',
            ],
        ];

        $this->assertSame(['type' => 'object'], (array)$this->converter->convert($schema));
    }

    public function testConvertExample(): void
    {
        $schema = (object)[
            'type' => 'string',
            'example' => 'test variable',
        ];

        $this->assertSame(['type' => 'string'], (array)$this->converter->convert($schema));
    }

    public function testConvertDeprecated(): void
    {
        $schema = (object)[
            'type' => 'string',
            'deprecated' => true,
        ];

        $this->assertSame(['type' => 'string'], (array)$this->converter->convert($schema));
    }
}
