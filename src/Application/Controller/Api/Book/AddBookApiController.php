<?php

namespace App\Application\Controller\Api\Book;

use App\Application\Controller\Api\AbstractApiController;
use App\Application\Dto\Book\CreateBookRequestDto;
use App\Application\Dto\Book\ReadBookResponseDto;
use App\Domain\Services\BookService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/books', name: 'api_books_post', methods: [Request::METHOD_POST])]
class AddBookApiController extends AbstractApiController
{
    public function __invoke(
        #[MapRequestPayload] CreateBookRequestDto $addBookRequest,
        BookService $bookService,
        SerializerInterface $serializer
    ): JsonResponse {
        $book = $bookService->add(
            $addBookRequest->isbn,
            $addBookRequest->title,
            $addBookRequest->summary
        );
        return new JsonResponse(ReadBookResponseDto::createFromDomain($book), Response::HTTP_CREATED);
    }
}
