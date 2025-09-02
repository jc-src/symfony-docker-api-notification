<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotFoundController extends AbstractController
{
    #[Route(
        path: '/{req}',
        name: 'default_not_found_catch',
        requirements: ['req' => '.*'])
    ]
    public function notFound(Request $request, LoggerInterface $logger): Response
    {
        $types = $request->getAcceptableContentTypes();
        $ajax = ($request->isXmlHttpRequest() || in_array('application/json', $types));

        $logger->info(
            sprintf(
                'Invalid_404 %s %s ip: %s , attempt: %s',
                $request->getMethod(),
                ($ajax ? 'ajax' : 'html'),
                $request->getClientIp(),
                $request->getPathInfo()
            )
        );

        return $this->json(['code' => 404, 'message' => 'Not found'], Response::HTTP_NOT_FOUND);
    }
}
