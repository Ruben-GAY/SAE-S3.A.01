<?php

namespace App\Feurum\Model\DataObject;



class Reponse extends Proposition {

    private string $titre;
    private string $idQuestion;

    public function __construct(?string $id, string $titre, string $idQuestion) {
        parent::__construct($id);
        $this->titre = $titre;
        $this->idQuestion = $idQuestion;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }

    public function getIdQuestion(): string {
        return $this->idQuestion;
    }

    public function setIdQuestion(string $idQuestion): void {
        $this->idQuestion = $idQuestion;
    }
}
