<?php

include('models/PokerDice.php');

echo "<h1>Exercici 1</h1>";

$cub = [new PokerDice(), new PokerDice(), new PokerDice(), new PokerDice(), new PokerDice()];

function tirarDaus($cubDaus){
    foreach($cubDaus as $dau){
        $dau->shapeName($dau->throw());
    }

    $totalTirades = 0;
    
    foreach($cubDaus as $dau){
        $totalTirades += $dau->getTotalThrows();
    }
    
    echo "<br> <br> En total, s'han tirat ". $totalTirades . " daus.<br> <br>";
}

//Test
tirarDaus($cub);
tirarDaus($cub);
tirarDaus($cub);