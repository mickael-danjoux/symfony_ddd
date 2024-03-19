<?php

namespace App\Application\Controller\Api\Book;

use App\Application\Dto\Book\ReadBookResponseDto;
use App\Domain\Services\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/books/{isbn}', name: 'api_books_get_one', methods: [Request::METHOD_GET])]
class GetOneBookApiController extends AbstractController
{
    public function __invoke(
        string $isbn,
        BookService $bookService
    ): JsonResponse {
        $book = $bookService->get($isbn);
        if(!$book){
            throw new NotFoundHttpException();
        }
        return new JsonResponse(ReadBookResponseDto::createFromDomain($book), Response::HTTP_OK);
    }

}
