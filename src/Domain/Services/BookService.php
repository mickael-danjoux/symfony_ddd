<?php

namespace App\Domain\Services;

use App\Domain\Models\Book\Book;
use App\Domain\Models\Book\BookRepositoryInterface;

class BookService implements BookServiceInterface
{


    public function __construct(
        private readonly BookRepositoryInterface $repository
    ) {
    }

    public function add(
        string $isbn,
        string $title,
        ?string $summary
    ): Book {
        return $this->repository->add(Book::create($isbn, $title, $summary));
    }

    public function get(string $isbn): Book
    {
        return $this->repository->findOneByIsbn(strtoupper($isbn));
    }
}
