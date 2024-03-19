<?php

namespace App\Application\Controller\Web;

use App\Domain\Services\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book', name: 'app_book_')]
class BookController extends AbstractController
{

    public function __construct(
        private readonly BookService $bookService
    ) {
    }

    #[Route('', name: 'list', methods: [Request::METHOD_GET])]
    public function list(): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $this->bookService->getAll()
        ]);
    }

}
