<?php

namespace App\Application\Controller\Api\Book;

use App\Application\Dto\Book\ReadBookResponseDto;
use App\Application\Traits\PaginationTrait;
use App\Domain\Services\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/books', name: 'api_books_get_all', methods: [Request::METHOD_GET])]
class GetAllBookApiController extends AbstractController
{
    use PaginationTrait;

    public function __invoke(
        BookService $bookService,
    ): JsonResponse {

        $pagination = $bookService->getAll($this->getPaginationContext());
        $books = [];
        foreach ($pagination->items as $b) {
            $books[] = ReadBookResponseDto::createFromDomain($b);
        }

        return new JsonResponse($books, Response::HTTP_OK);
    }

}
