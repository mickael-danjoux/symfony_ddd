<?php

namespace App\Infrastructure\Factory;

use App\Domain\Models\Pagination\PaginationRequest;
use App\Domain\Models\Pagination\PaginationResponse;
use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;

class PaginatorResponseFactory
{

    public function __construct(
        private readonly PaginatorInterface $paginator

    )
    {
    }

    public function build(
        PaginationRequest $paginationContext,
        Query $query,
        callable $itemDomainMapper
    ):  PaginationResponse
    {
        $pagination = $this->paginator->paginate(
            $query,
            $paginationContext->page,
            $paginationContext->limit
        );

        $result = [];
        foreach ($pagination->getItems() as $b) {
            $result[] = $itemDomainMapper($b);
        }
        $response = new PaginationResponse();
        $response->items = $result;
        $response->page = $pagination->getCurrentPageNumber();
        $response->totalItems = $pagination->getTotalItemCount();
        $response->totalPages = ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());

        return $response;

    }

}
