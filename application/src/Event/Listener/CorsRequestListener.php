<?php
declare(strict_types=1);

namespace App\Event\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: RequestEvent::class, method: 'onRequestEvent', priority: 50)]
#[AsEventListener(event: ResponseEvent::class, method: 'onResponseEvent', priority: 50)]
class CorsRequestListener
{
    protected const ALL_ALLOWED_METHODS = [
        Request::METHOD_GET,
        Request::METHOD_POST,
        Request::METHOD_PATCH,
        Request::METHOD_PUT,
        Request::METHOD_DELETE,
    ];

    protected const ALLOWED_HEADERS = [
        'X-Auth-Token',
        'Accept',
        'Accept-Encoding',
        'Accept-Language',
        'Connection',
        'Content-Length',
        'Content-Type',
        'Host',
        'Origin',
        'Referer',
        'User-Agent',
        'Sec-Fetch-Mode',
        'Access-Control-Request-Headers',
        'Access-Control-Request-Method'

    ];

    public function __construct(private RouterInterface $router) {}

    public function onRequestEvent(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->isMethod(Request::METHOD_OPTIONS)) {
            return;
        }

        //$origin = $request->server->get('HTTP_ORIGIN');

        $route = $this->router->match($request->getPathInfo());
        $collection = $this->router->getRouteCollection()->get($route['_route']);

        $allowedRouteMethods = $collection->getMethods() ?: self::ALL_ALLOWED_METHODS;
        $allowedRouteMethods[] = Request::METHOD_OPTIONS;

        $response = new Response(
            null,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json; charset=UTF-8',
                'Access-Control-Allow-Methods' => implode(', ', $allowedRouteMethods),
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Headers' => implode(', ', self::ALLOWED_HEADERS)
            ]
        );

        $event->setResponse($response);
    }

    public function onResponseEvent(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if (!$response->headers->has('Access-Control-Allow-Origin')) {
            $response->headers->add(['Access-Control-Allow-Origin' => '*']);
        }
    }
}
