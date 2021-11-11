<?php

class Account{
    private $numCompte;
    private $nom;
    private $cognoms;
    private $saldo;
    private $moviments = [];

    public function __construct($nom, $cognoms, $numCompte, $saldo){
        $this->nom = $nom;
        $this->cognoms = $cognoms;
        $this->numCompte = $numCompte;
        $this->saldo = $saldo;
    }

    public function deposit($amount){
        $this->saldo += $amount;
    }

    public function withdraw($amount){
        if ($amount <= $this->saldo){
            $this->saldo -= $amount;
            return true;
        } else {
            return false;
        }
    }

    public function registraMoviment($tipus, $quantitat)
    {
        $moviment = [];
        $saldoPrevi = $this->saldo;

        $timestamp = new DateTime();

        $timestamp = $timestamp->format('D d M Y H:i');

        if($tipus == 'ingressar'){
            array_push($moviment, $timestamp);
            $this->deposit($quantitat);
            array_push($moviment, "Saldo previ: ${saldoPrevi}€");
            array_push($moviment, '<i class="fas fa-plus-square m-1"></i>'."${quantitat}€");            
            array_push($moviment, '<i class="far fa-money-bill-alt m-1"></i>'."{$this->saldo}€");

            array_push($this->moviments, $moviment);

        } else if ($tipus == 'retirar'){
            if ($this->withdraw($quantitat)){
                array_push($moviment, $timestamp);
                array_push($moviment, "Saldo previ: ${saldoPrevi}€");
                array_push($moviment, '<i class="fas fa-minus-square m-1"></i>'."${quantitat}€");            
                array_push($moviment, '<i class="far fa-money-bill-alt m-1"></i>'."{$this->saldo}€");
    
                array_push($this->moviments, $moviment);
                return true;
            } else {
                return false;
            }
            
        }

    }
    
    public function imprimeixMoviments($moviments)
    {
        if (count($moviments) > 0){
            echo '<h2 class="font-bold text-lg text-left pb-2"><i class="fas fa-hand-holding-usd p-2"></i>Últims moviments</h2>';
            foreach($moviments as $linia){
                echo '<tr>';
                echo "<td> {$linia[0]} </td>";//Data
                echo "<td> {$linia[1]} </td>";//Saldo anterior
                echo "<td> {$linia[2]} </td>";//Moviment
                echo "<td> {$linia[3]} </td>";//Saldo final
                echo "</tr>";
            }
        }
    }

    public function getNom(){
        return $this->nom;
    }

    public function getCognoms(){
        return $this->cognoms;
    }

    public function getNumCompte(){
        return $this->numCompte;
    }
    
    public function getSaldo(){
       return $this->saldo;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setCognoms($cognoms)
    {
        $this->cognoms = $cognoms;
    }
    
    public function setNumCompte($numCompte)
    {
        $this->numCompte = $numCompte;
    }

    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    }

    public function getMoviments()
    {
        return $this->moviments;
    }
}