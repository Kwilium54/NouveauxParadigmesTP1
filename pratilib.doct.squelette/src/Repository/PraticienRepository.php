<?php

namespace toubeelib\praticien\Repository;

use Doctrine\ORM\EntityRepository;
use toubeelib\praticien\Domain\Entity\Praticien;

class PraticienRepository extends EntityRepository
{
    //recherche des praticiens par mot-clé dans la spécialité
    public function findBySpecialiteKeyword(string $keyword): array
    {
        $dql = "SELECT p FROM toubeelib\praticien\Domain\Entity\Praticien p
                JOIN p.specialite s
                WHERE s.libelle LIKE :keyword
                OR s.description LIKE :keyword";
        
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('keyword', '%' . $keyword . '%');
        
        return $query->getResult();
    }

    //recherche des praticiens par spécialité et moyen de paiement doné
    public function findBySpecialiteAndPayment(int $specialiteId, string $moyenPaiement): array
    {
        $dql = "SELECT p FROM toubeelib\praticien\Domain\Entity\Praticien p
                JOIN p.moyensPaiement mp
                WHERE p.specialiteId = :specialiteId
                AND mp.libelle = :moyenPaiement";
        
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('specialiteId', $specialiteId);
        $query->setParameter('moyenPaiement', $moyenPaiement);
        
        return $query->getResult();
    }
}
