<?php

namespace App\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

class DomainException extends \Exception
{
    protected string $property = '';

    public function __construct(string $message,  ?string $property = null, ?int $responseStatus = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, 400);
        $this->property = $property ?? '';
        $this->code = $responseStatus;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

}
