<?php

class PokerDice {
    private $cares = ["As", "K", "Q", "J", "7", "8"];
    private $tirades = 0;

    public function throw(){
        $this->tirades++;
        return rand(0,5);
    }

    public function shapeName($cara){
       echo "Ha sortit <b>" . $this->cares[$cara] . "</b>! <br>";
    }

    public function getTotalThrows(){
        return $this->tirades;
    }

}