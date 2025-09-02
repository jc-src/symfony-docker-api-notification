<?php
declare(strict_types=1);

namespace App\Dto\Notification;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ContactDto implements DtoInterface
{

    #[Assert\NotBlank, Assert\Email]
    private ?string $email = null;

    #[Assert\NotBlank, Assert\Length(min: 2, max: 255)]
    private ?string $name = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
