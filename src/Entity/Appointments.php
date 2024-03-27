<?php

namespace App\Entity;

use App\Repository\AppointmentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: AppointmentsRepository::class)]
#[HasLifecycleCallbacks]
class Appointments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $time_at = null;

    #[ORM\Column(length: 255)]
    private ?string $client_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $client_email = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $calendar_color = null;

    #[ORM\Column(length: 255)]
    private ?string $client_phone = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeAt(): ?\DateTimeInterface
    {
        return $this->time_at;
    }

    public function setTimeAt(\DateTimeInterface $time_at): static
    {
        $this->time_at = $time_at;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): static
    {
        $this->client_name = $client_name;

        return $this;
    }

    public function getClientEmail(): ?string
    {
        return $this->client_email;
    }

    public function setClientEmail(?string $client_email): static
    {
        $this->client_email = $client_email;

        return $this;
    }

    public function getClientPhone(): ?string
    {
        return $this->client_phone;
    }

    public function setClientPhone(string $client_phone): static
    {
        $this->client_phone = $client_phone;

        return $this;
    }
    public function getCalendarColor(): ?string
    {
        return $this->calendar_color;
    }

    public function setCalendarColor(string $calendar_color): static
    {
        $this->calendar_color = $calendar_color;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTimeImmutable();
        $this->setUpdatedAtValue();
    }
    #[ORM\PrePersist]
    public function setCalendarColorValue(): void
    {
        $clientName = $this->getClientName();
        $color = $this->generateColorForClient($clientName);

        $this->calendar_color = $color;
    }
    
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    private function generateColorForClient($clientName)
    {
        // Implement your logic to generate colors based on client name
        // You can use any algorithm or method you prefer to generate colors
        // For simplicity, let's assume a random color generation here
        return '#' . substr(md5($clientName), 0, 6); // Generate a hex color based on client name
    }
}
