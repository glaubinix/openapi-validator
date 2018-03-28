<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use PHPUnit\Framework\TestCase;

class ParameterConverterTest extends TestCase
{
    /** @var ParameterConverter */
    private $converter;

    protected function setUp()
    {
        $this->converter = new ParameterConverter();
    }

    public function testConvert()
    {
        $parameter = (object)[
            'name' => 'param',
            'type' => 'string',
        ];
        $this->assertSame(json_encode(['type' => 'object', 'required' => [], 'properties' => ['param' => ['type' => 'string']], 'additionalProperties' => true]), json_encode($this->converter->convert([$parameter])));
    }

    public function testConvertRequired()
    {
        $parameter = (object)[
            'name' => 'param',
            'type' => 'string',
            'required' => true,
        ];
        $this->assertSame(json_encode(['type' => 'object', 'required' => ['param'], 'properties' => ['param' => ['type' => 'string']], 'additionalProperties' => true]), json_encode($this->converter->convert([$parameter])));
    }

    public function testConvertNotRequired()
    {
        $parameter = (object)[
            'name' => 'param',
            'type' => 'string',
            'required' => false,
        ];
        $this->assertSame(json_encode(['type' => 'object', 'required' => [], 'properties' => ['param' => ['type' => 'string']], 'additionalProperties' => true]), json_encode($this->converter->convert([$parameter])));
    }

    public function testConvertFormat()
    {
        $parameter = (object)[
            'name' => 'param',
            'type' => 'string',
            'format' => 'password',
        ];
        $this->assertSame(json_encode(['type' => 'object', 'required' => [], 'properties' => ['param' => ['type' => 'string', 'format' => 'password']], 'additionalProperties' => true]), json_encode($this->converter->convert([$parameter])));
    }

    public function testConvertEnum()
    {
        $parameter = (object)[
            'name' => 'param',
            'type' => 'string',
            'enum' => ['one', 'two'],
        ];
        $this->assertSame(json_encode(['type' => 'object', 'required' => [], 'properties' => ['param' => ['type' => 'string', 'enum' => ['one', 'two']]], 'additionalProperties' => true]), json_encode($this->converter->convert([$parameter])));
    }

    public function testConvertEmpty()
    {
        $this->assertSame(json_encode(['type' => 'object', 'required' => [], 'properties' => (object)[], 'additionalProperties' => true]), json_encode($this->converter->convert([])));
    }
}
