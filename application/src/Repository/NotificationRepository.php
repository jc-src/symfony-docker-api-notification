<?php

namespace App\Repository;

use App\Dto\Notification\NotificationListItemDto;
use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function save(Notification $notification): int
    {
        $this->_em->persist($notification);
        $this->_em->flush();
        return $notification->getId();
    }

    /**
     * @return array<>
     */
    public function getLatest(): array
    {
        $result = $this->createQueryBuilder('n')
            ->select(sprintf(
                'NEW %s(
                    n.id,
                    n.type,
                    n.status,
                    cf.name,
                    ct.name
                )',
                NotificationListItemDto::class
                     ))
            ->orderBy('n.id', 'DESC')
            ->join('n.contactFrom', 'cf')
            ->join('n.contactTo', 'ct')
            ->getQuery()->getResult();
        return $result;
    }
}
