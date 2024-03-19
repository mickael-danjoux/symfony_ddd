<?php

namespace App\Domain\Models\Book;

use App\Domain\Models\Pagination\PaginationRequest;
use App\Domain\Models\Pagination\PaginationResponse;

interface BookRepositoryInterface
{
    public function findOneByIsbn(string $isbn): Book|null;

    public function add(Book $bookDomain): Book;

    public function remove(Book $bookDomain): void;

    public function findAllPaginated(PaginationRequest $paginationContext): PaginationResponse;

}
