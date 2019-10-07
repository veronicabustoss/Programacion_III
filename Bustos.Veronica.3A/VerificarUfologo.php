<?php

require_once ("./clases/Ufologo.php");

$legajo = isset($_POST["legajo"]) ? $_POST["legajo"] : NULL;

$clave = isset($_POST["clave"]) ? $_POST["clave"] : NULL;

$ufoAux = new Ufologo("null",$legajo,$clave);

$jsonUfo = Ufologo::VerificarExistencia($ufoAux);


if($jsonUfo->existe)
{
    $auxUfo = json_decode($ufoAux->ToJSON());
    //$auxEmail = str_replace(".", ".", $auxUfo->legajo);
    setcookie($auxUfo->legajo, date("YmdGis") , time()+360,"./MostrarCookie");
    $jsonUfo->mensaje = "El ufologo si existe, la cookie se creo con exito!";
    var_dump($jsonUfo); 
    header("location:ListadoUfologo.php");
      

}
else
{
    $jsonUfo->mensaje = "No se pudo crear la cookie, hubo un error!";
    var_dump($jsonUfo);
}

$codeado =json_encode($jsonUfo);
echo  $codeado;



?>