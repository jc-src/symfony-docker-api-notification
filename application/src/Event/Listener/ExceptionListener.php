<?php
declare(strict_types=1);

namespace App\Event\Listener;

use App\Util\Exception\AbstractApiException;
use App\Util\Exception\SimpleException;
use App\Util\Serializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'kernel.exception', method: 'onKernelException')]
class ExceptionListener
{
    private bool $debugEnabled;
    private Serializer $serializer;
    private ?LoggerInterface $logger;
    private string $message = '';

    public function __construct(
        bool $debugEnabled,
        Serializer $serializer,
        LoggerInterface $logger = null
    ) {
        $this->debugEnabled = $debugEnabled;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse();

        if ($exception instanceof SimpleException) {
            $data = $this->serializer->serialize($exception->getSimpleError());
            $response->setData(json_decode($data, true));
            $response->setStatusCode($exception->getStatusCode());
            $event->setResponse($response);

            return;
        }

        if ($exception instanceof AbstractApiException) {
            $data = $this->serializer->serialize($exception->getResponseDTO());
            $response->setData(json_decode($data, true));
            $response->setStatusCode($exception->getStatusCode());
            $event->setResponse($response);

            return;
        }

        if ($exception instanceof NotFoundHttpException) {
            $response->setData(['message' => $this->message]);
            $response->setStatusCode($exception->getStatusCode());
            $event->setResponse($response);

            return;
        }

        if ($exception instanceof HttpException) {
            $response->setData(['message' => $this->message]);
            $response->setStatusCode($exception->getStatusCode());
            $event->setResponse($response);

            return;
        }

        $response->setData($this->getResponseData($exception));
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        $event->setResponse($response);
    }

    private function getResponseData(Throwable $exception): array
    {
        $error = [
            'message' => $this->message,
            'stacktrace' => $exception->getTraceAsString(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
        if ($this->logger) {
            $this->logger->critical(json_encode($error), ['type' => 500]);
        }
        if ($this->debugEnabled) {
            return $error;
        }
        return ['message' => 'Something unexpected happened. Please try it again.'];
    }
}
