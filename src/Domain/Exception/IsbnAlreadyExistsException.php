<?php

namespace App\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

class IsbnAlreadyExistsException extends DomainException
{

    public function __construct(string $message)
    {
        parent::__construct($message, 'isbn', Response::HTTP_CONFLICT);
    }


}
