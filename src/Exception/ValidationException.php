<?php declare(strict_types=1);

namespace Glaubinix\OpenAPI\Exception;

class ValidationException extends \InvalidArgumentException implements OpenApiException
{
    /** @var array[] */
    private $validationErrors;

    public function __construct(array $validationErrors, $message = '', $code = 0, \Throwable $previous = null)
    {
        $this->validationErrors = $validationErrors;
        foreach ($validationErrors as $error) {
            $message .= sprintf("[%s] %s\n", $error['property'], $error['message']);
        }

        parent::__construct($message, $code, $previous);
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
