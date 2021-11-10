<?php

class Rectangle extends Shape {

    public function __construct($ample, $alt){
        Shape::__construct($ample, $alt);
    }

    public function area(){
        return $this->ample * $this->alt;
    }
}