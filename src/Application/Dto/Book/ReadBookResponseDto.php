<?php

namespace App\Application\Dto\Book;

use App\Domain\Models\Book\Book;

class ReadBookResponseDto
{
    /**
     * ISBN – International Standard Book Number
     * @var string
     */
    public string $isbn;

    public string $title;

    public ?string $summary = null;

     public static function createFromDomain(Book $book): static
    {
        $response = new self();
        $response->isbn = $book->getIsbn();
        $response->title = $book->getTitle();
        $response->summary = $book->getSummary();
        return $response;
    }
}
