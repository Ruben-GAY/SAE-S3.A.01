<?php

namespace App\Feurum\Model\DataObject;

abstract class Proposition extends Texte {

    public function __construct(?string $id) {
        parent::__construct($id);
    }

    public function __toString(): string {
        return "<p> Text id: {$this->getId()} organisateur </p>";
    }
}
