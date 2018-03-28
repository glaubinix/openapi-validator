<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\SchemaConverter;

use PHPUnit\Framework\TestCase;

class CleanRequiredTest extends TestCase
{
    /** @var CleanRequired */
    private $converter;

    protected function setUp()
    {
        $this->converter = new CleanRequired();
    }

    public function testConvertNoRequired()
    {
        $schema = (object)[
            'properties' => (object)[
                'name' => (object)[
                    'type' => 'string'
                ]
            ],
        ];

        $expected = json_encode($schema);

        $this->assertSame($expected, json_encode($this->converter->convert($schema)));
    }

    public function testConvertEmptyRequired()
    {
        $schema = (object)[
            'required' => [],
            'properties' => (object)[
                'name' => (object)[
                    'type' => 'string'
                ]
            ],
        ];

        $expected = json_encode($schema);

        $this->assertSame($expected, json_encode($this->converter->convert($schema)));
    }

    public function testConvertRequired()
    {
        $schema = (object)[
            'required' => [
                'name'
            ],
            'properties' => (object)[
                'name' => (object)[
                    'type' => 'string'
                ]
            ],
        ];

        $expected = json_encode($schema);

        $this->assertSame($expected, json_encode($this->converter->convert($schema)));
    }

    public function testConvertRequiredCleanup()
    {
        $schema = (object)[
            'required' => [
                'unavailable'
            ],
            'properties' => (object)[
                'name' => (object)[
                    'type' => 'string'
                ]
            ],
        ];

        $expected = json_encode([
            'required' => [],
            'properties' => [
                'name' => [
                    'type' => 'string',
                ]
            ],
        ]);

        $this->assertSame($expected, json_encode($this->converter->convert($schema)));
    }
}
