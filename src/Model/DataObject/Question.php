<?php

namespace App\Feurum\Model\DataObject;



class Question extends Proposition {

    private string $titre;
    private string $description;
    private string $dateDebutVote;
    private string $dateFinVote;
    private string $dateDebutReponse;
    private string $dateFinReponse;
    private bool $isPrivate;

    public function __construct(?string $id, string $titre, string $description, string $dateDebutVote, string $dateFinVote, string $dateDebutReponse, string $dateFinReponse, bool $isPrivate) {
        parent::__construct($id);
        $this->dateDebutVote = $dateDebutVote;
        $this->dateFinVote = $dateFinVote;
        $this->dateDebutReponse = $dateDebutReponse;
        $this->dateFinReponse = $dateFinReponse;
        $this->titre = $titre;
        $this->description = $description;
        $this->isPrivate = $isPrivate;
    }

    public function getDateDebutVote(): string {
        return $this->dateDebutVote;
    }

    public function setDateDebutVote(string $dateDebutVote): void {
        $this->dateDebutVote = $dateDebutVote;
    }

    public function getDateFinVote(): string {
        return $this->dateFinVote;
    }

    public function setDateFinVote(string $dateFinVote): void {
        $this->dateFinVote = $dateFinVote;
    }

    public function getDateDebutReponse(): string {
        return $this->dateDebutReponse;
    }

    public function setDateDebutReponse(string $dateDebutReponse): void {
        $this->dateDebutReponse = $dateDebutReponse;
    }

    public function getDateFinReponse(): string {
        return $this->dateFinReponse;
    }

    public function setDateFinReponse(string $dateFinReponse): void {
        $this->dateFinReponse = $dateFinReponse;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function isPrivate(): bool {
        return $this->isPrivate;
    }

    public function setPrivate(bool $isPrivate): void {
        $this->isPrivate = $isPrivate;
    }
}
