<?php

namespace App\Feurum\Model\DataObject;
/*
	1	iduser  Primaire	int(11)			Non	Aucun(e)			 Modifier Modifier	 Supprimer Supprimer	
Plus Plus
	2	idreponse  PrimaireIndex	int(11)			Non	Aucun(e)			 Modifier Modifier	 Supprimer Supprimer	
Plus Plus
	3	valeur
*/
class Vote {
    private int $iduser;
    private int $idreponse;
    private int $valeur;

    public function __construct(int $iduser, int $idreponse, int $valeur) {
        $this->iduser = $iduser;
        $this->idreponse = $idreponse;
        $this->valeur = $valeur;
    }

    public function getIduser(): int {
        return $this->iduser;
    }

    public function setIduser(int $iduser): void {
        $this->iduser = $iduser;
    }

    public function getIdreponse(): int {
        return $this->idreponse;
    }

    public function setIdreponse(int $idreponse): void {
        $this->idreponse = $idreponse;
    }

    public function getValeur(): int {
        return $this->valeur;
    }

    public function setValeur(int $valeur): void {
        $this->valeur = $valeur;
    }

    public static $voteToNb = [
        "Très Favorable" => 2,
        "Favorable" => 1,
        "Neutre" => 0,
        "Défavorable" => -1,
        "Très Défavorable" => -2
    ];

    
}