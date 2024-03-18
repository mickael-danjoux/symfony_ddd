<?php

namespace App\Application\Controller\Api;

use App\Domain\Exception\DomainException;
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

        $data = match(true) {
            $exception instanceof ValidationFailedException => [
                'errors' => array_map(
                    fn($violation) => [
                        'property' => $violation->getPropertyPath(),
                        'message' => $violation->getMessage()
                    ],
                    $exception->getViolations()
                ),
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            $exception instanceof ResourceNotFoundException || $exception instanceof NotFoundHttpException => [
                'errors' => 'Resource not found',
                'code' =>Response::HTTP_NOT_FOUND,
            ],
            $exception instanceof DomainException => [
                'errors' => [
                    'property' => $exception->getProperty(),
                    'message' => $exception->getMessage()
                ],
                'code' =>$exception->getCode(),
            ],
            default => ['errors' => 'Internal server error','code' => Response::HTTP_INTERNAL_SERVER_ERROR],
        };

        return new JsonResponse($data['errors'], $data['code']);
    }
}
