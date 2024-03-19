<?php

namespace App\Domain\Models\Book;

use App\Domain\Exception\InvalidIsbnException;

class Book
{
    /**
     * ISBN – International Standard Book Number
     * @var Isbn
     */
    private Isbn $isbn;

    private string $title;

    private ?string $summary = null;

    /**
     * @param string $isbn
     * @throws InvalidIsbnException
     */
    public function __construct(string $isbn)
    {
        $this->isbn = new Isbn($isbn);
    }

    /**
     * @throws InvalidIsbnException
     */
    public static function create(string $isbn, string $title, ?string $summary = null): static
    {
        return (new self($isbn))
            ->setTitle($title)
            ->setSummary($summary);

    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = ucfirst($title);
        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string|null $summary): self
    {
        $this->summary = $summary;
        return $this;
    }


}
