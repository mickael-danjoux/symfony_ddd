<?php

namespace App\Infrastructure\Repository;

use App\Domain\Models\Book\Book as BookDomain;
use App\Domain\Models\Book\BookRepositoryInterface;
use App\Domain\Models\Pagination\PaginationRequest;
use App\Domain\Models\Pagination\PaginationResponse;
use App\Infrastructure\Entity\Book;
use App\Infrastructure\Factory\PaginatorResponseFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository implements BookRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorResponseFactory $paginatorResponseFactory
    ) {
        parent::__construct($registry, Book::class);
    }

    public function add(BookDomain $bookDomain): BookDomain
    {
        $book = Book::createFromDomain($bookDomain);
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();

        return Book::getDomainHydratation($book);
    }

    public function remove(BookDomain $bookDomain): void
    {
        $book = $this->findOneByIsbn($bookDomain->getIsbn());
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();
    }

    public function findOneByIsbn(string $isbn): BookDomain|null
    {
        $book = $this->findOneBy(['isbn' => $isbn]);
        return $book ?
            Book::getDomainHydratation($book) :
            null;
    }


    public function findAllPaginated(PaginationRequest $paginationContext): PaginationResponse
    {
        return $this->paginatorResponseFactory->build(
            $paginationContext,
            $this->createQueryBuilder('b')->getQuery(),
            fn($dbBook) => Book::getDomainHydratation($dbBook)
        );
    }
}
