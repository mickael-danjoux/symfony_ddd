<?php

namespace App\Application\Controller\Api;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

class ErrorApiController extends AbstractApiController
{
    public function __invoke(Throwable $exception, LoggerInterface $logger): JsonResponse
    {
        $previous = $exception->getPrevious();
        $data = null;

        if ($previous instanceof ValidationFailedException) {
            $errors = [];
            foreach ($previous->getViolations() as $violation) {
                $errors[] = [
                    'field' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage()
                ];
            }
            $data['errors'] = $errors;
            $code = Response::HTTP_UNPROCESSABLE_ENTITY;
        }else if ($exception instanceof ResourceNotFoundException || $exception instanceof NotFoundHttpException) {
            $code = Response::HTTP_NOT_FOUND;
            $data = 'Resource not found';
        }else{
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $data = 'Internal server error';
        }

        return new JsonResponse($data, $code);
    }
}
