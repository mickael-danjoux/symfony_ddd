<?php

namespace App\Application\Dto\Book;

use Symfony\Component\Validator\Constraints as Assert;

class CreateBookRequestDto
{
    /**
     * ISBN – International Standard Book Number
     * @var string
     */
    #[Assert\NotBlank]
    public string $isbn;

    #[Assert\NotBlank]
    public string $title;

    public ?string $summary = null;

}
