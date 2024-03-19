<?php

namespace App\Application\Controller\Web;

use App\Application\Dto\Book\CreateBookRequestDto;
use App\Application\Traits\PaginationTrait;
use App\Domain\Exception\DomainException;
use App\Domain\Services\BookService;
use App\Infrastructure\Form\CreateBookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book', name: 'app_book_')]
class BookController extends AbstractController
{
    use PaginationTrait;

    public function __construct(
        private readonly BookService $bookService
    ) {
    }

    #[Route('', name: 'list', methods: [Request::METHOD_GET])]
    public function list(): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $this->bookService->getAll($this->getPaginationContext())->items
        ]);
    }

    #[Route('/add', name: 'add', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function add(Request $request)
    {
        $bookRequest = new CreateBookRequestDto();
        $form = $this->createForm(CreateBookType::class, $bookRequest)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->bookService->add(
                    $bookRequest->isbn,
                    $bookRequest->title,
                    $bookRequest->summary
                );
                $this->addFlash('success', 'Book successfully added');
                return $this->redirectToRoute('app_book_list');
            } catch (DomainException $exception) {
                $form->get($exception->getProperty())->addError(new FormError($exception->getMessage()));
            }

        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
