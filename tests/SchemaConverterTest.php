<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI;

use Glaubinix\OpenAPI\SchemaConverter\CleanRequired;
use Glaubinix\OpenAPI\SchemaConverter\NotSupported;
use Glaubinix\OpenAPI\SchemaConverter\ReadWriteOnly;
use Glaubinix\OpenAPI\SchemaConverter\TypeConverter;
use Glaubinix\OpenAPI\SchemaConverter\XPatternProperties;
use PHPUnit\Framework\TestCase;

class SchemaConverterTest extends TestCase
{
    /** @var SchemaConverter */
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new SchemaConverter([
            new XPatternProperties(),
            new TypeConverter(),
            new ReadWriteOnly(),
            new CleanRequired(),
            new NotSupported()
        ]);
    }

    public function testConvert(): void
    {
        $initial = new \stdClass();
        $expected = json_encode($initial);
        $schema = $this->converter->convert($initial);

        $this->assertSame($expected, json_encode($schema));
    }

    public function testConvertProperty(): void
    {
        $initial = json_decode(file_get_contents(__DIR__ . '/schemas/openapi-simple.json'));
        $schema = $this->converter->convert($initial);

        $this->assertSame(json_encode([
            'type' => 'object',
            'required' => ['name'],
            'properties' => [
                'id' => [
                    'type' => 'integer',
                    'description' => 'Test id',
                ],
                'name' => [
                    'type' => ['string', 'null'],
                    'description' => 'Test name',
                ],
            ]]), json_encode($schema));
    }
}
