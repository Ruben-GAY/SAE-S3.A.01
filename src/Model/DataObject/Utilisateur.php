<?php

namespace App\Feurum\Model\DataObject;

class Utilisateur {

    private ?string $id;
    private string $nom;
    private string $prenom;
    private string $pseudo;
    private string $email;
    private string $motDePasse;
    private string $dateDeNaissance;
    private ?string $role;

    public function __construct(?string $id, string $nom, string $prenom, string $pseudo, string $email, string $motDePasse, string $dateDeNaissance, ?string $role) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->motDePasse = $motDePasse;
        $this->dateDeNaissance = $dateDeNaissance;
        $this->role = $role;
    }

    public function getId(): ?string {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }
    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function getPseudo(): string {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): void {
        $this->pseudo = $pseudo;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getMotDePasse(): string {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): void {
        $this->motDePasse = $motDePasse;
    }

    public function getDateDeNaissance(): string {
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(string $dateDeNaissance): void {
        $this->dateDeNaissance = $dateDeNaissance;
    }

    public function getRole(): ?string {
        return $this->role;
    }

    public function setRole(?string $role): void {
        $this->role = $role;
    }
}
