<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

use PHPUnit\Framework\TestCase;

class XPatternPropertiesTest extends TestCase
{
    /** @var XPatternProperties */
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new XPatternProperties();
    }

    public function testConvert(): void
    {
        $options = [
            'supportPatternProperties' => true,
        ];

        $schema = (object)[
            'x-patternProperties' => [
                '^[A-Z]{2}$' => (object)[
                    'type' => 'string',
                ],
            ],
        ];

        $this->assertSame(json_encode(['patternProperties' => ['^[A-Z]{2}$' => ['type' => 'string']]]), json_encode($this->converter->convert($schema, $options)));
    }

    public function testConvertNotSupported(): void
    {
        $options = [
            'supportPatternProperties' => false,
        ];

        $schema = (object)[
            'x-patternProperties' => [
                '^[A-Z]{2}$' => (object)[
                    'type' => 'string',
                ],
            ],
        ];

        $this->assertSame([], (array)$this->converter->convert($schema, $options));
    }

    public function testConvertNotAvailable(): void
    {
        $schema = (object)[];

        $this->assertSame([], (array)$this->converter->convert($schema));
    }
}
