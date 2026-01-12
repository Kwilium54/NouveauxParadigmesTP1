<?php
namespace toubeelib\praticien\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;


class Specialite
{
  
    private int $id;
    private string $libelle;
    private ?string $description;

    public function getId(): int { return $this->id; }
    public function getLibelle(): string { return $this->libelle; }
    public function getDescription(): ?string { return $this->description; }
}
