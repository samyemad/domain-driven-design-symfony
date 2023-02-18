<?php

declare(strict_types=1);

namespace App\Shared\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        if (method_exists($event->getThrowable(), 'getCode') && 0 != $event->getThrowable()->getCode()) {
            $code = $event->getThrowable()->getCode();
        }
        $response['code'] = $code;
        $response['message'] = $event->getThrowable()->getMessage();
        $event->setResponse(new JsonResponse($response, $code));
    }
}
