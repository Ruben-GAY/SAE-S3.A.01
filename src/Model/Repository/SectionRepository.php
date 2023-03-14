<?php

namespace App\Feurum\Model\Repository;

use App\Feurum\Model\DataObject\Section;

class SectionRepository {

    // construit un objet Section à partir tableau
    static function construire(array $sectionTab) : Section {
        return new Section(
            $sectionTab['id'],
            $sectionTab['titre'],
            $sectionTab['contenu'],
            $sectionTab['ordre'],
            $sectionTab['idProposition'],
        );
    }
    // retourne toutes les sections
    static function getAllSections() {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->query("SELECT * FROM section");
        $res = [];
        foreach ($pdoStatement as $section) {
            $res[] = static::construire($section);
        }
        return $res;
    }

    // retourne la section correspondant à l'id de la proposition passer en paramètre
    static function getAllSectionsByPropositionId(string $idProposition) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM section WHERE idProposition = :idProposition");
        $pdoStatement->execute(['idProposition' => $idProposition]);
        $res = [];
        foreach ($pdoStatement as $section) {
            $res[] = static::construire($section);
        }
        return $res;
    }

    // retourne la section correspondant à l'id passer en paramètre
    static function getSectionById(string $id) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM section WHERE id = :id");
        $pdoStatement->execute(['id' => $id]);
        $section = $pdoStatement->fetch();
        return static::construire($section);
    }

    // sauvegarde la section passer en paramètre dans la base de données
    static function sauvegarder(Section $section) {
        $pdo = DatabaseConnection::getPdo();
        TexteRepository::sauvegarder($section);
        $pdoStatement = $pdo->prepare("INSERT INTO section (id, titre, contenu, ordre, idProposition) VALUES (:id, :titre, :contenu, :ordre, :idProposition)");
        $pdoStatement->execute([
            'id' => $section->getId(),
            'titre' => $section->getTitre(),
            'contenu' => $section->getContenu(),
            'ordre' => $section->getOrdre(),
            'idProposition' => $section->getIdProposition(),
        ]);
    }

    // met à jour la section passer en paramètre dans la base de données
    static function update(Section $section) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("UPDATE section SET titre = :titre, contenu = :contenu, ordre = :ordre, idProposition = :idProposition WHERE id = :id");
        $pdoStatement->execute([
            'id' => $section->getId(),
            'titre' => $section->getTitre(),
            'contenu' => $section->getContenu(),
            'ordre' => $section->getOrdre(),
            'idProposition' => $section->getIdProposition(),
        ]);
        TexteRepository::update($section);
    }

    // supprime la section correspondant à l'id passer en paramètre
    static function supprimer(string $id) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("DELETE FROM section WHERE id = :id");
        $pdoStatement->execute(['id' => $id]);
    }

}