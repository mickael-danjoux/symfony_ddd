<?php

namespace App\Tests\Domain\Book;

use App\Domain\Exception\IsbnAlreadyExistsException;
use App\Domain\Models\Book\Book;
use App\Domain\Models\Book\BookRepositoryInterface;
use App\Domain\Services\BookService;
use App\Infrastructure\Entity\Book as BookDb;
use App\Infrastructure\Tests\Factory\BookFactory;
use PHPUnit\Framework\TestCase;
use Zenstruck\Foundry\Test\Factories;

class BookTest extends TestCase
{
    use Factories;

    protected BookService $bookService;

    public function testAddBook()
    {
        $book = $this->addBook();
        self::assertInstanceOf(Book::class, $book);
    }

    private function addBook(): Book
    {
        return $this->bookService->add(
            BookFactory::faker()->isbn10(),
            BookFactory::faker()->text(16),
            BookFactory::faker()->sentence(16)
        );
    }

    public function testGetBook()
    {
        $addedBook = $this->addBook();
        $book = $this->bookService->get($addedBook->getIsbn());
        self::assertInstanceOf(Book::class, $book);
    }

    public function testRemoveBook()
    {
        $addedBook = $this->addBook();
        $this->bookService->remove($addedBook);
        $book = $this->bookService->get($addedBook->getIsbn());
        self::assertNull($book);
    }

    public function testAddSameIsbn()
    {
        $firstBook = $this->addBook();

        try {
            $this->bookService->add(
                $firstBook->getIsbn(),
                BookFactory::faker()->text(16),
                BookFactory::faker()->sentence(16)
            );
        } catch (\Exception $e) {
            self::assertInstanceOf(IsbnAlreadyExistsException::class, $e);
        }
    }

    protected function setUp(): void
    {
        $bookRepo = new class() implements BookRepositoryInterface {
            private array $books = [];

            public function __construct()
            {
                for ($i = 1; $i <= 5; $i++) {
                    $this->books[] = BookFactory::new()->withoutPersisting()->create()->object();
                }
            }

            public function findOneByIsbn(string $isbn): Book|null
            {
                $dbBooks = array_filter($this->books, function ($object) use ($isbn) {
                    return $object->getIsbn() === $isbn;
                });
                $dbBook = reset($dbBooks);
                return $dbBook ?
                    BookDb::getDomainHydratation($dbBook) :
                    null;
            }

            public function add(Book $bookDomain): Book
            {
                $book = BookDb::createFromDomain($bookDomain);
                $this->books = [
                    ...$this->books,
                    $book
                ];
                return BookDb::getDomainHydratation($book);
            }

            public function remove(Book $bookDomain): void
            {
                $ids = array_map(fn($item) => $item->getIsbn(), $this->books);
                $index = array_search($bookDomain->getIsbn(), $ids);

                if ($index !== false) {
                    unset($this->books[$index]);
                }
            }

        };

        $this->bookService = new BookService($bookRepo);
    }

}
