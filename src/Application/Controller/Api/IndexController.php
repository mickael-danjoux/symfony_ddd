<?php

namespace App\Application\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'api_index', methods: [Request::METHOD_GET])]
class IndexController extends AbstractController
{

    public function __invoke(): JsonResponse
    {
        return new JsonResponse('Ok Response');
    }

}
