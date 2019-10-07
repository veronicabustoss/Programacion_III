<?php

$lejagoJson = isset($_GET['json']) ? $_GET['json'] : NULL;

$decodeado = json_decode($lejagoJson);

$auxReturn = new stdClass();
$auxReturn->Exito = false;
$auxReturn->Mensaje = "No hay cookie con ese legajo";

if(isset($_COOKIE[$decodeado->legajo])) {
        $auxReturn->Exito = true;
        $auxReturn->Mensaje = $_COOKIE[$decodeado->legajo];
}
else {
    echo "No se busca nada";
}
echo (json_encode($auxReturn));



?>