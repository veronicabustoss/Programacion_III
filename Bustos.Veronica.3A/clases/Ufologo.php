<?php

class Ufologo
{
    private $_pais;
    private $_legajo;
    private $_clave;

    public function __construct($pais,$legajo,$clave)
    {
        $this->_pais = $pais;
        $this->_legajo = $legajo;
        $this->_clave = $clave;
    }

    public function ToJSON()
    {
        $json = new stdClass();

        $json->pais = $this->_pais;
        $json->legajo = $this->_legajo;
        $json->clave = $this->_clave;

        return json_encode($json);
    }

    public function GuardarEnArchivo()
    {
        $json = new stdClass();
        $json->exito = false;
        $json->mensaje = "";

        $ar = fopen("./archivos/ufologos.json","a+");

        if($ar != false)
        {
            $aux = fwrite($ar,$this->ToJSON()."/n");
            if($aux != false)
            {
                $json->exito = true;
                $json->mensaje = "El ufologo se guardo con exito!";
            }
            else
            {
                $json->exito = false;
                $json->mensaje = "El ufologo NO se pudo guardar.";
            }
        }

        fclose($ar);
        //Retorna un Json
        return $json;

    }


    public static function TraerTodos() {
        $auxReturn = array();
        if(file_exists("./archivos/Ufologos.json")) {
            $file = fopen("./archivos/Ufologos.json", "r");
            if($file != false) {
                while(!feof($file)) {
                    $auxlinea = trim(fgets($file));
                    if($auxlinea != "") {
                        $auxJson = json_decode($auxlinea);
                        $auxUsuario = new Ufologo($auxJson->pais,$auxJson->legajo, $auxJson->clave);
                        array_push($auxReturn, $auxUsuario);
                    }
                }
                fclose($file);
            }
        }
        return $auxReturn;
    }


    public static function VerificarExistencia($ufologo)
    {
               //Retorna true si el ufologo esta registrado
            $ufologos = Ufologo::TraerTodos();

            $ufoAux = json_decode($ufologo->ToJSON());

            $json = new stdClass();

            $json->existe = false;
            
            $json->mensaje = "El ufologo no se encuentra";

            foreach($ufologos as $ufo)
            {
                $ufoJson = json_decode($ufo->ToJSON());

                if($ufoJson->clave == $ufoAux->clave && $ufoJson->legajo == $ufoAux->legajo)
                {
                    $json->mensaje = "El ufologo SI se encuentra.";
                    $json->existe = true;
                }
        }
       
        return $json;
    }

}


?>