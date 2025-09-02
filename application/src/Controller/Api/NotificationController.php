<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\Notification\EmailNotificationDto;
use App\Manager\NotificationManager;
use App\Util\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/notification',
    name: 'api_notification_',
    requirements: ['req' => '.*'])
]
class NotificationController extends AbstractController
{
    #[Route(
        path: '/send-email',
        name: 'send_email',
        methods: ['POST'])
    ]
    public function send(
        #[ValueResolver('dto')] EmailNotificationDto $dto,
        NotificationManager $manager
    ): Response
    {
        $id = $manager->send($dto);

        return $this->json(['code' => 201, 'id' => $id], Response::HTTP_CREATED);
    }

    #[Route(
        path: '/list',
        name: 'list',
        methods: ['GET'])
    ]
    public function list(
        NotificationManager $manager
    ): Response
    {
        $list = $manager->getAllMessages();

        return $this->json($list, Response::HTTP_OK);
    }

    #[Route(
        path: '/send/{id}',
        name: 'send_message',
        methods: ['GET'])
    ]
    public function sendMessage(
        NotificationManager $manager,
        int $id
    ): Response
    {
        $success = $manager->sendMessage($id);

        return $this->json(
            ['code' => 200, 'id' => $id, 'success' => $success],
            Response::HTTP_OK
        );
    }
}
