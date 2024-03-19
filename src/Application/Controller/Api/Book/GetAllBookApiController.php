<?php

namespace App\Application\Controller\Api\Book;

use App\Application\Controller\Api\AbstractApiController;
use App\Application\Dto\Book\ReadBookResponseDto;
use App\Domain\Services\BookService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/books', name: 'api_books_get_all', methods: [Request::METHOD_GET])]
class GetAllBookApiController extends AbstractApiController
{
    public function __invoke(
        BookService $bookService
    ): JsonResponse {
        $books = $bookService->getAll();
        $result = [];
        foreach ($books as $b) {
            $result[] = ReadBookResponseDto::createFromDomain($b);
        }

        return new JsonResponse($result, Response::HTTP_OK);
    }

}
