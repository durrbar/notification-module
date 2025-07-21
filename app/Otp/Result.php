<?php

namespace Modules\Notification\Otp;

use InvalidArgumentException;

class Result
{
    /**
     * @var bool
     */
    private $valid;

    /**
     * @var array
     */
    private $errors;

    /**
     * @var string
     */
    private $id;

    /**
     * Result constructor.
     *
     * @param  mixed  $value  => string $id | array $errors
     */
    public function __construct($value)
    {
        if (is_string($value)) {
            $this->id = $value;
            $this->valid = true;
        } elseif (is_array($value)) {
            $this->errors = $value;
            $this->valid = false;
        } else {
            throw new InvalidArgumentException('Invalid argument: Only string or array allowed.');
        }
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
