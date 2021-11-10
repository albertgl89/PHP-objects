<?php

class Triangle extends Shape {

    public function __construct($ample, $alt){
        Shape::__construct($ample, $alt);
    }

    public function area(){
        return ($this->ample / 2) * $this->alt;
    }
}