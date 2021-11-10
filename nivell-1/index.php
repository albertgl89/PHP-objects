<?php

echo "<h1>Exercici 1</h1>";

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

//Test
$empleat = new Employee();
$empleat->initialize("Albert", 5500);
$empleat->print();
$empleat->initialize("Ramon", 6500);
$empleat->print();

echo "<br>";
echo "<h1>Exercici 2</h1>";

class Shape {
    protected $ample;
    protected $alt;

    public function __construct($ample, $alt){
        $this->ample = $ample;
        $this->alt = $alt;
    }
}

class Triangle extends Shape {

    public function __construct($ample, $alt){
        Shape::__construct($ample, $alt);
    }

    public function area(){
        return ($this->ample / 2) * $this->alt;
    }
}

class Rectangle extends Shape {

    public function __construct($ample, $alt){
        Shape::__construct($ample, $alt);
    }

    public function area(){
        return $this->ample * $this->alt;
    }
}

$forma1 = new Triangle(5,3);
$forma2 = new Rectangle(5,5);
echo "L'àrea del triangle és ". $forma1->area() . "<br>";//Esperat 7.5
echo "L'àrea del rectangle és ". $forma2->area() . "<br>";//Esperat 25