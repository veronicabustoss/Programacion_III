<?php

require_once ("./clases/Ovni.php");

$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : NULL;
$velocidad = isset($_POST["velocidad"]) ? $_POST["velocidad"] : NULL;
$planeta = isset($_POST["planeta"]) ? $_POST["planeta"] : NULL;

$objJson =  new stdClass();
$objJson->Exito=false;
$objJson->Mensaje="No se puede agregar el ovni";

$ovni = new Ovni($tipo,$velocidad,$planeta);

if($ovni->Agregar())
{
    $objJson->Exito=true;
    $objJson->Mensaje="Se agrego con exito el ovni!";
}

var_dump($objJson);

?>