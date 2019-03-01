<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * Custom ExceptionController that renders to json.
 */
class ExceptionController
{
    /**
     * Converts an Exception to a Response.
     *
     * @param Request                   $request
     * @param \Exception|\Throwable     $exception
     * @param DebugLoggerInterface|null $logger
     *
     * @throws \InvalidArgumentException
     *
     * @return Response
     */
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null): Response
    {
        $code = $this->getStatusCode($exception);

        // TODO Норм вывод ошибок
        return new Response(
            json_encode(
                [
                    'error' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'statusCode' => $code,
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            ),
            $code,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * Determines the status code to use for the response.
     *
     * @param $exception
     *
     * @return int
     */
    protected function getStatusCode($exception)
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return 500;
    }
}
