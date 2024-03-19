<?php

namespace App\Infrastructure\Entity;

use App\Domain\Models\Book\Book as BookDomain;
use App\Infrastructure\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING)]
    private string $isbn;

    #[ORM\Column(length: 128)]
    private string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    /**
     * @param string|null $isbn
     */
    public function __construct(?string $isbn)
    {
        $this->isbn = $isbn;
    }

    static function createFromDomain(BookDomain $bookDomain ): static {
        return (new self($bookDomain->getIsbn()))
            ->setTitle($bookDomain->getTitle())
            ->setSummary($bookDomain->getSummary());
    }

    static function getDomainHydratation(self $dbBook): BookDomain
    {
        return (new BookDomain($dbBook->getIsbn()))
            ->setTitle($dbBook->getTitle())
            ->setSummary($dbBook->getSummary());

    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary = null): static
    {
        $this->summary = $summary;

        return $this;
    }
}
