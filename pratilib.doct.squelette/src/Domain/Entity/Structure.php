<?php
namespace toubeelib\praticien\Domain\Entity;

class Structure
{
    private string $id;
    private string $nom;
    private string $adresse;
    private ?string $ville;
    private ?string $codePostal;
    private ?string $telephone;
    private $praticiens;

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getAdresse(): string { return $this->adresse; }
    public function getVille(): ?string { return $this->ville; }
    public function getCodePostal(): ?string { return $this->codePostal; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function getPraticiens() { return $this->praticiens; }
}
