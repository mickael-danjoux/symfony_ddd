<?php

namespace App\Domain\Models\Book;

interface BookRepositoryInterface
{
    public function findOneByIsbn(string $isbn): Book|null;

    public function add(Book $bookDomain): Book;

    public function remove(Book $bookDomain): void;

    /**
     * @return Book[]
     */
    public function findAll(): array;

}
