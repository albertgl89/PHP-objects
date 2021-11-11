<?php
include('models/Account.php');
session_start();

if (isset($_POST["tancaSessio"])) {
  for ($i = 0; $i < count($_COOKIE); $i++) {
    unset($_COOKIE["PHPSESSID"]);
  }
  session_destroy();
  unset($_SESSION["usuari"]);
  unset($_POST["tancaSessio"]);
}

//Emmagatzematge per fer tests
if (!isset($_SESSION["usuari"])) {
  $compte = new Account("Albert", "Garcia Llorca", "2785 9665 8547 9658", 2569.36);
  $_SESSION["usuari"] = serialize($compte);
}

$compteUsuari = unserialize($_SESSION["usuari"]);

if (!isset($_POST["import"])) {
  $_POST["import"] = "";
  unset($_SESSION["success"]);
} else if ($_POST["import"] > 0 || isset($_SESSION["success"])) { //Comprova si el contingut de POST ja ha sigut tractat (és un refresh del navegador i no cal tornar a crear el moviment)
  if (isset($_SESSION["ultimaTransaccio"])) {
    $saldoFinal = str_replace('<i class="far fa-money-bill-alt m-1"></i>', '', $_SESSION["ultimaTransaccio"][3]);
    $saldoFinal = str_replace('€', '', $saldoFinal);
    $importMoviment = str_replace('€', '', $_SESSION["ultimaTransaccio"][2]);
    $importMoviment = str_replace('<i class="fas fa-minus-square m-1"></i>', '', $importMoviment);
    $importMoviment = str_replace('<i class="fas fa-plus-square m-1"></i>', '', $importMoviment);

    if ($saldoFinal == (string)$compteUsuari->getSaldo() && $_POST["import"] == $importMoviment) {
      $_POST["import"] = "";
      unset($_POST["ingressar"]);
      unset($_POST["retirar"]);
      unset($_SESSION["success"]);
    }
  }
}
if (!isset($_POST["errors"])) {
  $_POST["errors"] = 0;
}

//Gestiona la petició de l'usuari
$import = test_input($_POST["import"]);

if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['ingressar']) || isset($_POST['retirar']))) {

  if ($import === false) { //Import no és vàlid

    $_POST["import"] = "";
    $_POST["errors"] = 1;
    if (count($compteUsuari->getMoviments()) > 0) {
      $_SESSION["ultimaTransaccio"] = $compteUsuari->getMoviments()[count($compteUsuari->getMoviments()) - 1];
    }
    $_SESSION["usuari"] = serialize($compteUsuari);

  } else if ($import == "") { //Import és buit
    
    //No facis res
    if (count($compteUsuari->getMoviments()) > 0) {
    
      $_SESSION["ultimaTransaccio"] = $compteUsuari->getMoviments()[count($compteUsuari->getMoviments()) - 1];
    }
    $_SESSION["usuari"] = serialize($compteUsuari);

  } else if (isset($_POST['ingressar'])) { //Import vàlid, es vol ingressar
   
    $compteUsuari->registraMoviment('ingressar', $import);
    $_POST["errors"] = 3;
    unset($_POST['ingressar']);
    unset($_POST['import']);
    $_SESSION["ultimaTransaccio"] = $compteUsuari->getMoviments()[count($compteUsuari->getMoviments()) - 1];
    $_SESSION["usuari"] = serialize($compteUsuari);
    $_SESSION["success"] = "";

  } else if (isset($_POST['retirar'])) { //Import vàlid, es vol retirar
    
    if (!$compteUsuari->registraMoviment('retirar', $import)) {//Si no hi ha fons suficients, no retiris
     
      $_POST["errors"] = 2;
      unset($_POST['retirar']);
      unset($_POST['import']);
      if (count($compteUsuari->getMoviments()) > 0) {
    
        $_SESSION["ultimaTransaccio"] = $compteUsuari->getMoviments()[count($compteUsuari->getMoviments()) - 1];
      }
      $_SESSION["usuari"] = serialize($compteUsuari);

    } else {
     
      $_POST["errors"] = 3;
      unset($_POST['retirar']);
      unset($_POST['import']);
      if (count($compteUsuari->getMoviments()) > 0) {
    
        $_SESSION["ultimaTransaccio"] = $compteUsuari->getMoviments()[count($compteUsuari->getMoviments()) - 1];
      }
      $_SESSION["usuari"] = serialize($compteUsuari);
      $_SESSION["success"] = "";
    }
  }
}


?>

<!DOCTYPE html>
<html lang="ca">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Operacions d'ingrés i retirada - ITA Bank</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
  <script src="https://kit.fontawesome.com/72dc2f6b98.js" crossorigin="anonymous"></script>
</head>

<header class="container mx-auto border rounded-md border-transparent bg-pink-600 text-white m-4 p-4 max-w-xl grid grid-cols-2 place-content-around">
  <div>
    <h1 class="text-xl font-bold">ITA Bank</h1>
    <h2 class="text-lg">Ingrés i retirada d'efectiu</h2>
  </div>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="flex self-center justify-self-end">
    <button type="submit" name="tancaSessio" class="border border-transparent rounded-3xl hover:rounded-3xl hover:bg-pink-800 m-2 p-2"><i class="fas fa-power-off p-1"></i>Tanca sessió</button>
  </form>
</header>

<body class="m-1">
  <div id="Operacions" class="container mx-auto border rounded-md border-transparent bg-pink-300 m-4 p-4 max-w-xl shadow-inner">
    <p class="text-lg">Hola, <b><?php echo $compteUsuari->getNom(); ?></b>! </p>
    <p>El teu compte <?php echo $compteUsuari->getNumCompte(); ?> presenta un saldo actual de <b><?php echo $compteUsuari->getSaldo(); ?>€</b>.</p><br>

    <form name="formulari" method="post" class="border rounded-md p-2 border-pink-600" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="font-bold p-2 grid grid-cols-4 gap-2 items-center md:text-base text-xs">
        <label for="import" class="p-2 justify-self-end">Import</label><input type="text" name="import" required class="col-span-2 font-bold p-2 md:text-xl text-xs text-right border rounded-3xl border-transparent"> <i class="fas fa-euro-sign p-2 md:text-xl text-xs"></i>
      </div>
      <div class="grid grid-cols-2">
        <button type="submit" name="retirar" class="border rounded-3xl bg-pink-600 font-bold text-xs md:text-lg text-white p-2 m-2 hover:bg-pink-800"><i class="fas fa-sign-out-alt m-2"></i>Retirar import</button>
        <button type="submit" name="ingressar" class="border rounded-3xl bg-pink-600 font-bold text-xs md:text-lg text-white p-2 m-2 hover:bg-pink-800"><i class="fas fa-sign-in-alt m-2"></i>Ingressar import</button>
      </div>
    </form>

    <?php

    //Determina tipus d'error o event
    function mostraErrors()
    {
      if ($_POST["errors"] > 0) {
        echo ' inline-block ';
        $_POST["import"] = "";
      } else if ($_POST["errors"] == 0) {
        echo ' hidden ';
      }
    }
    //Determina missatge a tornar a l'usuari en cas d'error o event
    function missatgesError($codi)
    {
      switch ($codi) {
        case 0:
          echo '<p class="col-span-4">Codi zero</p>';
          break;
        case 1:
          echo '<i class="fas fa-exclamation-triangle m-2 text-xl"></i><p class="col-span-4">No s\'ha introduït un import adequat. El format és <b>1250.26</b>, o <b>785.00</b>.</p>';
          $_POST["errors"] = 0;
          unset($_POST["import"]);
          break;
        case 2:
          echo '<i class="fas fa-exclamation-triangle m-2 text-xl col-span-1"></i><p class="col-span-3">No hi ha fons suficients per a fer la retirada.</p>';
          unset($_POST["import"]);
          $_POST["errors"] = 0;
          break;
        case 3:
          echo '<i class="fas fa-check-circle text-green-600 col-span-1"></i><p class="col-span-3 text-green-600">Moviment efectuat correctament!</p>';
          unset($_POST["import"]);
          $_POST["errors"] = 0;
          break;
      }
    }
    //Comprova que l'import sigui un número abans d'enviar-lo a processar per Account
    function test_input($data)
    {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      if (is_numeric($data) || $data == "") {
        return $data;
      } else {
        return false;
      }
    }
    ?>
    <!--Quadre de diàleg de missatges d'error o event-->
    <div id="errors" class=" <?php mostraErrors(); ?> absolute max-w-lg inset-x-0 top-4 animacio-missatge container mx-auto bg-white text-left text-pink-600 text-lg m-2 p-2 border rounded-md border-transparent shadow-2xl grid grid-cols-5 place-items-center">

      <?php missatgesError($_POST["errors"]); ?>
    </div>
    <!--Taula de darreres transaccions-->
    <div class="<?php if (count($compteUsuari->getMoviments()) > 0) {
                  echo ' inline-block ';
                } else {
                  echo ' hidden ';
                } ?> container mx-auto border rounded-lg border-pink-600 bg-gradient-to-b from-pink-300 via-white to-white p-2 m-2 shadow-xl">
      <table class="container mx-auto">

        <body>
          <?php
          $compteUsuari->imprimeixMoviments($compteUsuari->getMoviments());
          ?>
        </body>

      </table>
    </div>


  </div>
</body>

</html>