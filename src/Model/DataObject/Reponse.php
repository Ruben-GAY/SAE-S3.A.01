<?php

namespace App\Feurum\Model\DataObject;



class Reponse extends Proposition {

    private string $titre;
    private string $idQuestion;

    public function __construct(?string $id,string $titre, string $idQuestion) {
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

    public function getAvis(): string {
        return $this->avis;
    }

    public function setAvis(string $avis): void {
        $this->avis = $avis;
    }

    public function getContenue(): string {
        return $this->contenue;
    }

    public function setContenue(string $contenue): void {
        $this->contenue = $contenue;
    }

    public function getIdQuestion(): string {
        return $this->idQuestion;
    }

    public function setIdQuestion(string $idQuestion): void {
        $this->idQuestion = $idQuestion;
    }

    public function afficher() {
        echo "<p> Utilisateur {$this->prenom} {$this->nom} de login {$this->login} </p>";
    }

    public function __toString() : string {
        return "<p> Utilisateur {$this->prenom} {$this->nom} de login {$this->login} </p>";
    }
}