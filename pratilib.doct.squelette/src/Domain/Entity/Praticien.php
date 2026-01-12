<?php
namespace toubeelib\praticien\Domain\Entity;

class Praticien
{
    private string $id;
    private string $nom;
    private string $prenom;
    private string $ville;
    private string $email;
    private string $telephone;
    private int $specialiteId;
    private ?string $structureId;
    private ?Specialite $specialite;
    private ?Structure $structure;
    private $motifsVisite;
    private $moyensPaiement;

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getVille(): string { return $this->ville; }
    public function getEmail(): string { return $this->email; }
    public function getTelephone(): string { return $this->telephone; }
    public function getSpecialite(): ?Specialite { return $this->specialite; }
    public function getStructure(): ?Structure { return $this->structure; }
    public function getMotifsVisite() { return $this->motifsVisite; }
    public function getMoyensPaiement() { return $this->moyensPaiement; }
}
