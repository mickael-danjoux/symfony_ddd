<?php

namespace App\Domain\Models\Book;

class Book
{
    /**
     * ISBN – International Standard Book Number
     * @var string
     */
    private string $isbn;

    private string $title;

    private ?string $summary = null;

    /**
     * @param string $isbn
     */
    public function __construct(string $isbn)
    {
        $this->isbn = strtoupper($isbn);
    }

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
