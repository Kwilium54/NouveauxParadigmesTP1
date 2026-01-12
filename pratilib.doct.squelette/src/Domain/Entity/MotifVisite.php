<?php
namespace toubeelib\praticien\Domain\Entity;

class MotifVisite
{
    private int $id;
    private string $libelle;
    private int $specialiteId;
    private ?Specialite $specialite;

    public function getId(): int { return $this->id; }
    public function getLibelle(): string { return $this->libelle; }
    public function getSpecialite(): ?Specialite { return $this->specialite; }
}
