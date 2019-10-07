<?php

require_once "./clases/Ufologo.php";
class Listado {
    public static function CrearListadoJson() {
        $auxReturn = "";
        $auxArray = Ufologo::TraerTodos();
        foreach ($auxArray as $ufos) {
            $auxReturn.= $ufos->ToJSON()."<br>";
        }
        return $auxReturn;
    }
}
echo Listado::CrearListadoJson();

?>