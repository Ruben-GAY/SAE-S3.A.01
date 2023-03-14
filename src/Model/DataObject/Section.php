<?php

namespace App\Feurum\Model\DataObject;



class Section extends Texte {

    private string $titre;
    private string $contenu;
    private string $ordre;
    private string $idProposition;

    public function __construct(?string $id, string $titre, string $contenu, string $ordre, string $idProposition) {
        parent::__construct($id);
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->ordre = $ordre;
        $this->idProposition = $idProposition;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }

    public function getContenu(): string {
        return $this->contenu;
    }

    public function setContenu(string $contenu): void {
        $this->contenu = $contenu;
    }

    public function getOrdre(): string {
        return $this->ordre;
    }

    public function setOrdre(string $ordre): void {
        $this->ordre = $ordre;
    }

    public function getIdProposition(): string {
        return $this->idProposition;
    }

    public function setIdProposition(string $idProposition): void {
        $this->idProposition = $idProposition;
    }

}