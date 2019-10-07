<?php

require_once ("./clases/Ufologo.php");

$pais = isset($_POST["pais"]) ? $_POST["pais"] : NULL;

$legajo = isset($_POST["legajo"]) ? $_POST["legajo"] : NULL;

$clave = isset($_POST["clave"]) ? $_POST["clave"] : NULL;

$nuevoUfologo = new Ufologo($pais,$legajo,$clave);

$resultado = $nuevoUfologo->GuardarEnArchivo();

echo json_encode($resultado);


?>