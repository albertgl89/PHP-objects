<?php

class Employee {
    private $nom;
    private $sou;

    public function initialize($nom, $sou){
        $this->nom = $nom;
        $this->sou = $sou;
    }

    public function print(){
        echo "Nom empleat: ". $this->nom . "<br>";
        echo ($this->sou > 6000? "Sí" : "No") . " ha de pagar impostos. <br>";
    }
}