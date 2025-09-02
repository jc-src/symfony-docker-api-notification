<?php
declare(strict_types=1);

namespace App\Tests\Manager;

use App\Dto\Notification\ContactDto;
use App\Dto\Notification\EmailNotificationDto;
use App\Entity\Contact;
use App\Entity\Notification;
use App\Manager\NotificationManager;
use App\Model\EnumNotificationStatus;
use App\Repository\ContactRepository;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NotificationManagerTest extends KernelTestCase
{
    private ?NotificationRepository $notificationRepository = null;
    private ?ContactRepository $contactRepository = null;

    public function setUp(): void
    {
        self::bootKernel();
        $this->notificationRepository = $this->createMock(NotificationRepository::class);
        $this->contactRepository = $this->createMock(ContactRepository::class);

    }

    public function testCreateMessage(): void
    {
        $container = static::getContainer();
        $dto = $this->createDemoDto();
        $this->contactRepository->expects(self::exactly(2))
            ->method('findOneBy')
            ->willReturnCallback(function($arr) use ($dto) {
                match ($arr['email']) {
                    $dto->getFrom()->getEmail() => $this->createContact($dto->getFrom()),
                    $dto->getTo()->getEmail() => $this->createContact($dto->getTo()),
                };
                }
            );
        ;
        $this->notificationRepository->expects(self::once())
            ->method('save')
            ->willReturn(7);

        $container->set(ContactRepository::class, $this->contactRepository);
        $container->set(NotificationRepository::class, $this->notificationRepository);

        $manager = $container->get(NotificationManager::class);
        $id = $manager->create($dto);

        $this->assertEquals(7, $id);
    }

    public function testSendMessage(): void
    {
        $notification = $this->createMock(Notification::class);
        $notification->method('getId')->willReturn(7);
        $notification->setSubject("Test subject");
        $notification->setStatus(EnumNotificationStatus::pending);

        $this->notificationRepository->expects(self::exactly(1))
            ->method('findOneBy')
            ->willReturn($notification);

        $this->notificationRepository->expects(self::once())
            ->method('save')
            ->willReturn($notification->getId());

        $container = static::getContainer();
        $container->set(NotificationRepository::class, $this->notificationRepository);
        $manager = $container->get(NotificationManager::class);

        $resultValid = $manager->sendMessage(7);
        $this->assertTrue($resultValid);
    }


    private function createDemoDto(): EmailNotificationDto
    {
        $from = new ContactDto();
        $from->setName("Mr. From");
        $from->setEmail("from@mirster.com");
        $to = new ContactDto();
        $to->setName("Ms. To");
        $to->setEmail("to@miss.com");

        $dto = new EmailNotificationDto();
        $dto->setSubject('some subject');
        $dto->setBody('some content');
        $dto->setFrom($from);
        $dto->setTo($to);

        return $dto;
    }

    private function createContact(ContactDto $dto): Contact
    {
        return (new Contact())
            ->setEmail($dto->getEmail())
            ->setName($dto->getName());
    }
}
