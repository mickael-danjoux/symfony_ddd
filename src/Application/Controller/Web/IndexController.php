<?php

namespace App\Application\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{

    #[Route('', name: 'app_index', methods: [Request::METHOD_GET])]
    public function index(): Response
    {
        return $this->redirectToRoute('app_book_list');
    }

}
