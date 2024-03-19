<?php

namespace App\Domain\Models\Pagination;

class PaginationResponse
{
    public int $page = 1;

    public int $itemsPerPage = 10;

    public int $totalItems;

    public int $totalPages;

    public array $items = [];

}
