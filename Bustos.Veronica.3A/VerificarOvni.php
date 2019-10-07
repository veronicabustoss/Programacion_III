<?php
require_once "./clases/Ovni.php";
//obtengo la cadena ya casteada a objeto json desde POST
$ovniJson = isset($_POST['ovni']) ? ($_POST['ovni']) : NULL;
//creo un objeto de ovni a partir de los datos recuperados

$variableJson = json_encode($ovniJson);

var_dump($variableJson);

$objOvni = new Ovni($variableJson->tipo,$variableJson->velocidad,$variableJson->planeta);
//traigo todo el array de ovnis desde la base de datos
$arrayOvni=$objOvni->Traer();
//verifico que el ovni obtenido por POST este en el array de la base de datos
if($objOvni->Existe($arrayOvni))
{
    //si esta retorno al ovni utilizando el metodo "ToJson()"
    echo $objOvni->ToJSON();
}
else
{
    $tipoComp=false;
    $planetaComp=false;
    foreach($arrayOvni as $auxOvni)
    {
        if($auxOvni->tipo== $objOvni->tipo)
        {
            $tipoComp=true;
        }
        if($auxOvni->planetaOrigen== $objOvni->planetaOrigen)
        {
            $planetaComp=true;
        }
    }
    if($tipoComp ==false && $planetaComp ==false)
    {
        echo "no coincide ni tipo ni planeta";
    }
    if($tipoComp==true)
    {
       echo "solo coincide el tipo";
    }
    if($planetaComp==true)
    {
      echo "solo coincide el planeta";
    }
}
?>