<?php

namespace App\Domain\Services;

use App\Domain\Models\Book\Book;
use App\Domain\Models\Pagination\PaginationRequest;
use App\Domain\Models\Pagination\PaginationResponse;

interface BookServiceInterface
{

    public function add(
        string $isbn,
        string $title,
        ?string $summary
    ): Book;

    public function get(string $isbn): Book|null;

    public function getAll(PaginationRequest $paginationContext): PaginationResponse;

    public function remove(Book $book): void;
}
