<?php

namespace Glaubinix\OpenAPI\PathResolver;

interface PathResolvableInterface
{
    public function getPath(): string;
    public function getMethod(): string;
}
