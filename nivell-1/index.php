<?php
include('models/Employee.php');
include('models/Shape.php');
include('models/Triangle.php');
include('models/Rectangle.php');

echo "<h1>Exercici 1</h1>";

//Test
$empleat = new Employee();
$empleat->initialize("Albert", 5500);
$empleat->print();
$empleat->initialize("Ramon", 6500);
$empleat->print();

echo "<br>";
echo "<h1>Exercici 2</h1>";

$forma1 = new Triangle(5,3);
$forma2 = new Rectangle(5,5);
echo "L'àrea del triangle és ". $forma1->area() . "<br>";//Esperat 7.5
echo "L'àrea del rectangle és ". $forma2->area() . "<br>";//Esperat 25