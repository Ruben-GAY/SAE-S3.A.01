<?php

namespace App\Feurum\Model\DataObject;

abstract class Texte {

    private ?string  $id;

    public function __construct(?string $id) {
        $this->id = $id;
    }

    public function getId(): ?string {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }


    public function __toString() : string {
        return "<p> Text id: {$this->id}  </p>";
    }
}