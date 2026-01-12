<?php

namespace toubeelib\praticien\Repository;

use Doctrine\ORM\EntityRepository;
use toubeelib\praticien\Domain\Entity\Specialite;

class SpecialiteRepository extends EntityRepository
{
    //recherche des spécialités par mot-clé dans le libellé ou la description
    public function findByKeyword(string $keyword): array
    {
        $dql = "SELECT s FROM toubeelib\praticien\Domain\Entity\Specialite s
                WHERE s.libelle LIKE :keyword
                OR s.description LIKE :keyword";
        
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('keyword', '%' . $keyword . '%');
        
        return $query->getResult();
    }
}
