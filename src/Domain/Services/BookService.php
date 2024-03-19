<?php

namespace App\Domain\Services;

use App\Domain\Exception\IsbnAlreadyExistsException;
use App\Domain\Models\Book\Book;
use App\Domain\Models\Book\BookRepositoryInterface;
use App\Domain\Models\Pagination\PaginationRequest;
use App\Domain\Models\Pagination\PaginationResponse;

class BookService implements BookServiceInterface
{

    public function __construct(
        private readonly BookRepositoryInterface $repository
    ) {
    }

    /**
     * @throws IsbnAlreadyExistsException
     */
    public function add(
        string $isbn,
        string $title,
        ?string $summary
    ): Book {
        $searchBook = $this->get($isbn);
        if ($searchBook instanceof Book) {
            throw new IsbnAlreadyExistsException("Isbn '" . strtoupper($isbn) . "' already exists.");
        }
        return $this->repository->add(Book::create($isbn, $title, $summary));
    }

    public function get(string $isbn): Book|null
    {
        return $this->repository->findOneByIsbn(strtoupper($isbn));
    }

    public function remove(Book $book): void
    {
        $this->repository->remove($book);
    }

    public function getAll(PaginationRequest $paginationContext): PaginationResponse
    {
        return $this->repository->findAllPaginated($paginationContext);
    }
}
