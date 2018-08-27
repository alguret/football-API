<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    /** @var ConstraintViolationListInterface */
    private $errors;

    public function __construct(ConstraintViolationListInterface $errors)
    {
        parent::__construct();

        $this->errors = $errors;
    }

    public function getErrorMessages(): array
    {
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach ($this->errors as $error) {
            $messages[$error->getPropertyPath()] = $error->getMessage();
        }

        return $messages;
    }
}
