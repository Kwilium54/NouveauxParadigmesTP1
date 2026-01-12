<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Infr/Persistence/bootstrap.php';
require_once __DIR__ . '/helpers.php';

use toubeelib\praticien\Domain\Entity\{Specialite, Praticien, Structure, MotifVisite};
use Doctrine\Common\Collections\{Criteria, ArrayCollection};

echo "<h1>Exercices Doctrine ORM</h1>";

// EXERCICE 1
section("Q1 : Spécialité d'identifiant 1");
if ($specialite = $entityManager->find(Specialite::class, 1)) {
    field("ID", $specialite->getId()); 
    field("Libellé", $specialite->getLibelle()); 
    field("Description", $specialite->getDescription());
}

section("Q2 : Praticien 8ae1400f-d46d-3b50-b356-269f776be532");
if ($praticien = $entityManager->find(Praticien::class, '8ae1400f-d46d-3b50-b356-269f776be532')) {
    field("Nom", $praticien->getNom() . " " . $praticien->getPrenom()); 
    field("Ville", $praticien->getVille());
    field("Email", $praticien->getEmail()); 
    field("Téléphone", $praticien->getTelephone());
}

section("Q3 : Praticien avec spécialité et structure");
if ($praticien = $entityManager->find(Praticien::class, '8ae1400f-d46d-3b50-b356-269f776be532')) {
    field("Nom", $praticien->getNom() . " " . $praticien->getPrenom());
    if ($specialite = $praticien->getSpecialite()) { 
        subsection("Spécialité"); 
        field("Libellé", $specialite->getLibelle()); 
        field("Description", $specialite->getDescription()); 
    }
    if ($structure = $praticien->getStructure()) { 
        subsection("Structure"); 
        field("Nom", $structure->getNom()); 
        field("Adresse", $structure->getAdresse()); 
        field("Ville", $structure->getVille()); 
    }
}

section("Q4 : Structure avec praticiens");
if ($structure = $entityManager->find(Structure::class, '3444bdd2-8783-3aed-9a5e-4d298d2a2d7c')) {
    field("Nom", $structure->getNom()); 
    field("Adresse", $structure->getAdresse()); 
    field("Ville", $structure->getVille());
    subsection("Praticiens"); 
    listItems($structure->getPraticiens(), fn($p) => htmlspecialchars($p->getPrenom() . " " . $p->getNom() . " - " . $p->getEmail()));
}

section("Q5 : Spécialité et motifs de visite");
if ($specialite = $entityManager->find(Specialite::class, 1)) {
    field("Libellé", $specialite->getLibelle()); 
    field("Description", $specialite->getDescription());
    subsection("Motifs"); 
    listItems($entityManager->getRepository(MotifVisite::class)->findBy(['specialiteId' => 1]), fn($m) => htmlspecialchars($m->getLibelle()));
}

section("Q6 : Praticien et motifs");
if ($praticien = $entityManager->find(Praticien::class, '8ae1400f-d46d-3b50-b356-269f776be532')) {
    field("Nom", $praticien->getNom() . " " . $praticien->getPrenom());
    subsection("Motifs"); 
    listItems($praticien->getMotifsVisite(), fn($m) => htmlspecialchars($m->getLibelle()));
}

section("Q7 : Créer praticien");
$nouveauPraticienId = '11111111-2222-3333-4444-555555555555';
if (!$praticienExistant = $entityManager->find(Praticien::class, $nouveauPraticienId)) {
    $nouveauPraticien = new Praticien(); 
    $specialitePediatrie = $entityManager->find(Specialite::class, 3);
    setProp($nouveauPraticien, 'id', $nouveauPraticienId); 
    setProp($nouveauPraticien, 'nom', 'Dupont'); 
    setProp($nouveauPraticien, 'prenom', 'Jean');
    setProp($nouveauPraticien, 'ville', 'Nancy'); 
    setProp($nouveauPraticien, 'email', 'jean.dupont@example.com');
    setProp($nouveauPraticien, 'telephone', '06 12 34 56 78'); 
    setProp($nouveauPraticien, 'specialiteId', 3); 
    setProp($nouveauPraticien, 'specialite', $specialitePediatrie);
    $entityManager->persist($nouveauPraticien); 
    $entityManager->flush();
    echo "<strong>Créé</strong><br>"; 
    field("Nom", $nouveauPraticien->getNom() . " " . $nouveauPraticien->getPrenom());
} else { 
    noResult("Existe déjà"); 
}

section("Q8 : Modifier praticien");
if ($praticienAModifier = $entityManager->find(Praticien::class, $nouveauPraticienId)) {
    subsection("Avant"); 
    field("Ville", $praticienAModifier->getVille());
    $structureValue = getPropSafe($praticienAModifier, 'structure'); 
    field("Structure", $structureValue ? $structureValue->getNom() : "Aucune");
    $motifsValue = getPropSafe($praticienAModifier, 'motifsVisite'); 
    field("Motifs", $motifsValue ? count($motifsValue) : 0);
    
    $cabinetBigot = $entityManager->find(Structure::class, '3444bdd2-8783-3aed-9a5e-4d298d2a2d7c');
    $motifs = $entityManager->getRepository(MotifVisite::class)->findBy(['specialiteId' => 3], null, 2);
    setProp($praticienAModifier, 'ville', 'Paris'); 
    setProp($praticienAModifier, 'structure', $cabinetBigot);
    setProp($praticienAModifier, 'structureId', '3444bdd2-8783-3aed-9a5e-4d298d2a2d7c');
    setProp($praticienAModifier, 'motifsVisite', new ArrayCollection($motifs));
    $entityManager->flush(); 
    echo "<strong>Modifié</strong><br>";
    
    subsection("Après"); 
    field("Ville", $praticienAModifier->getVille()); 
    field("Structure", $praticienAModifier->getStructure()->getNom());
    echo "Motifs: "; 
    listItems($praticienAModifier->getMotifsVisite(), fn($m) => htmlspecialchars($m->getLibelle()));
}

section("Q9 : Supprimer praticien");
if ($praticienASupprimer = $entityManager->find(Praticien::class, $nouveauPraticienId)) {
    subsection("Avant"); 
    echo htmlspecialchars($praticienASupprimer->getNom() . " " . $praticienASupprimer->getPrenom()) . "<br>";
    $entityManager->remove($praticienASupprimer); 
    $entityManager->flush(); 
    echo "<strong>Supprimé</strong><br>";
    $entityManager->clear(); 
    $verification = $entityManager->find(Praticien::class, $nouveauPraticienId);
    subsection("Après"); 
    noResult($verification === null ? "N'existe plus" : "Erreur");
} else { 
    noResult("N'existe pas"); 
}

// EXERCICE 2
section("Ex2-Q1 : Email Gabrielle.Klein@live.com");
if ($praticien = $entityManager->getRepository(Praticien::class)->findOneBy(['email' => 'Gabrielle.Klein@live.com'])) {
    field("Nom", $praticien->getNom() . " " . $praticien->getPrenom()); 
    field("Ville", $praticien->getVille());
    field("Spécialité", $praticien->getSpecialite()->getLibelle());
} else { 
    noResult("Aucun"); 
}

section("Ex2-Q2 : Goncalves à Paris");
if ($praticien = $entityManager->getRepository(Praticien::class)->findOneBy(['nom' => 'Goncalves', 'ville' => 'Paris'])) {
    field("Nom", $praticien->getNom() . " " . $praticien->getPrenom()); 
    field("Email", $praticien->getEmail());
} else { 
    noResult("Aucun"); 
}

section("Ex2-Q3 : Pédiatrie et praticiens");
if ($specialite = $entityManager->getRepository(Specialite::class)->findOneBy(['libelle' => 'pédiatrie'])) {
    field("Spécialité", $specialite->getLibelle());
    $praticiens = $entityManager->getRepository(Praticien::class)->findBy(['specialiteId' => $specialite->getId()]);
    subsection("Praticiens (" . count($praticiens) . ")");
    listItems($praticiens, fn($p) => htmlspecialchars($p->getNom() . " " . $p->getPrenom() . " - " . $p->getVille()));
} else { 
    noResult("Aucune"); 
}

section("Ex2-Q4 : Structures 'santé'");
$criteria = Criteria::create()->where(Criteria::expr()->contains('nom', 'santé'))->orWhere(Criteria::expr()->contains('nom', 'Santé'));
$structures = $entityManager->getRepository(Structure::class)->matching($criteria);
if (count($structures) > 0) {
    echo "<strong>Trouvées:</strong> " . count($structures) . "<br>";
    listItems($structures, fn($s) => "<strong>" . htmlspecialchars($s->getNom()) . "</strong> - " . htmlspecialchars($s->getVille()));
} else { 
    noResult("Aucune"); 
}

section("Ex2-Q5 : Ophtalmo à Paris");
if ($specialite = $entityManager->getRepository(Specialite::class)->findOneBy(['libelle' => 'ophtalmologie'])) {
    $criteria = Criteria::create()->where(Criteria::expr()->eq('ville', 'Paris'))->andWhere(Criteria::expr()->eq('specialiteId', $specialite->getId()));
    $praticiens = $entityManager->getRepository(Praticien::class)->matching($criteria);
    if (count($praticiens) > 0) {
        echo "<strong>Trouvés:</strong> " . count($praticiens) . "<br>";
        listItems($praticiens, fn($p) => htmlspecialchars($p->getNom() . " " . $p->getPrenom()));
    } else { 
        noResult("Aucun"); 
    }
} else { 
    noResult("Spécialité introuvable"); 
}

// EXERCICE 3 (DQL)
section("Ex3-Q1 : Spécialités 'Imagerie' (DQL)");
$specialites = $entityManager->getRepository(Specialite::class)->findByKeyword('Imagerie');
if (count($specialites) > 0) {
    echo "<strong>Trouvées:</strong> " . count($specialites) . "<br>";
    listItems($specialites, fn($s) => "<strong>" . htmlspecialchars($s->getLibelle()) . "</strong> - " . htmlspecialchars($s->getDescription() ?? 'N/A'));
} else { 
    noResult("Aucune"); 
}

section("Ex3-Q2 : Praticiens 'ologie' (DQL)");
$praticiens = $entityManager->getRepository(Praticien::class)->findBySpecialiteKeyword('ologie');
if (count($praticiens) > 0) {
    echo "<strong>Trouvés:</strong> " . count($praticiens) . "<br>";
    listItems($praticiens, fn($p) => htmlspecialchars($p->getNom() . " " . $p->getPrenom() . " - " . $p->getSpecialite()->getLibelle()));
} else { 
    noResult("Aucun"); 
}

section("Ex3-Q3 : Pédiatrie + carte bancaire (DQL)");
if ($specialite = $entityManager->getRepository(Specialite::class)->findOneBy(['libelle' => 'pédiatrie'])) {
    $praticiens = $entityManager->getRepository(Praticien::class)->findBySpecialiteAndPayment($specialite->getId(), 'carte bancaire');
    if (count($praticiens) > 0) {
        echo "<strong>Trouvés:</strong> " . count($praticiens) . "<br>";
        listItems($praticiens, fn($p) => htmlspecialchars($p->getNom() . " " . $p->getPrenom() . " - " . $p->getVille()));
    } else { 
        noResult("Aucun"); 
    }
} else { 
    noResult("Spécialité introuvable"); 
}
