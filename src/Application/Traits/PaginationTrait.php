<?php

namespace App\Application\Traits;

use App\Domain\Models\Pagination\PaginationRequest;
use Symfony\Component\HttpFoundation\Request;

trait PaginationTrait
{
    public function getPaginationContext(): PaginationRequest
    {
        $request = Request::createFromGlobals();
        $pagination = new PaginationRequest();
        $pagination->page = $request->query->getInt('page', 1);
        $pagination->limit = $request->query->getInt('limit', 10);

        return $pagination;

    }
}
