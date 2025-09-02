<?php
declare(strict_types=1);

namespace App\Manager;

use App\Dto\Notification\ContactDto;
use App\Dto\Notification\EmailNotificationDto;
use App\Dto\Notification\NotificationInterface;
use App\Entity\Contact;
use App\Entity\Notification;
use App\Model\EnumNotificationStatus;
use App\Repository\ContactRepository;
use App\Repository\NotificationRepository;

class NotificationManager
{
    public function __construct(
        private NotificationRepository $notificationRepository,
        private ContactRepository $contactRepository,
    ) {
    }

    public function send(NotificationInterface $dto): int
    {
        // Using "Tagged Services" we could manage a proper loading of service needed

        $contactFrom = $this->findOrCreateContact($dto->getFrom());
        $contactTo = $this->findOrCreateContact($dto->getTo());

        // All Notifications have the following properties
        $notification = (new Notification())
            ->setType($dto->getType())
            ->setStatus(EnumNotificationStatus::pending)
            ->setContactTo($contactTo)
            ->setContactFrom($contactFrom)
            ->setMessage($dto->getBody());

        // But emails, have also subject, it should be loaded via tagged.
        if ($dto instanceof EmailNotificationDto) {
            $notification->setSubject($dto->getSubject());
        }
        $id = $this->notificationRepository->save($notification);

        return $id;
    }

    public function getAllMessages(): array
    {
        // Pagination?
        return $this->notificationRepository->getLatest();
    }

    public function sendMessage(int $id): bool
    {
        $notification = $this->notificationRepository
            ->findOneBy(['id' => $id, 'status' => EnumNotificationStatus::pending]);
        if (!$notification) {
            return false;
        }
        $notification->setStatus(EnumNotificationStatus::sent);
        $notification->setUpdatedAt(new \DateTimeImmutable());
        $this->notificationRepository->save($notification);

        return true;
    }

    private function findOrCreateContact(ContactDto $dto): Contact
    {
        $contact = $this->contactRepository->findOneBy(['email' => $dto->getEmail()]);
        if ($contact) {
            return $contact;
        }
        return (new Contact())
            ->setEmail($dto->getEmail())
            ->setName($dto->getName());
    }
}
