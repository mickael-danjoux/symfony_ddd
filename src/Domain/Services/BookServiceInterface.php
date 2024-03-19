<?php

namespace App\Domain\Services;

use App\Domain\Models\Book\Book;

interface BookServiceInterface
{

    public function add(
        string $isbn,
        string $title,
        ?string $summary
    ): Book;

    public function get(string $isbn): Book|null;

    /**
     * @return Book[]
     */
    public function getAll(): array;

    public function remove(Book $book): void;
}
