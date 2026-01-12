<?php

namespace toubeelib\praticien\Domain\Entity;

class MoyenPaiement
{
    private int $id;
    private string $libelle;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }
}
