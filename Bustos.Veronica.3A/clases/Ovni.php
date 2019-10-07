<?php
require_once ("AccesoDatos.php");
require_once ("IParte2.php");

class Ovni implements IParte2
{
    public $_tipo;
    public $_velocidad;
    public $_planetaOrigen;
    public $_pathFoto;

    public function __construct($tipo = null, $velocidad = null,$planetaOrigen = null, $pathFoto = null)
    {
        $this->_tipo = $tipo!=null ? $tipo : "";
        $this->_velocidad = $velocidad!=null ? $velocidad : "";
        $this->_planetaOrigen = $planetaOrigen!=null ? $planetaOrigen : "";
        $this->_pathFoto = $pathFoto!=null ? $pathFoto : "";
    }

    public function ToJSON()
    {
        $stdClass= new stdClass();
        $stdClass->tipo = $this->_tipo;
        $stdClass->velocidad = $this->_velocidad;
        $stdClass->planetaOrigen = $this->_planetaOrigen;
        $stdClass->pathFoto = $this->_pathFoto;

        return json_encode($stdClass);
    }

    public function Agregar()
    {
        $objPdo = AccesoDatos::DameUnObjetoAcceso();

        $consulta = $objPdo->RetornarConsulta("INSERT INTO ovnis(tipo, velocidad, planeta, foto)"
                                                . "VALUES(:tipo, :velocidad, :planeta, :foto)"); 
        $consulta->bindValue(':tipo',$this->_tipo,PDO::PARAM_STR);
        $consulta->bindValue(':velocidad',$this->_velocidad,PDO::PARAM_INT);
        $consulta->bindValue(':planeta',$this->_planetaOrigen,PDO::PARAM_STR);
        $consulta->bindValue('foto',$this->_pathFoto,PDO::PARAM_STR);

        return $consulta->execute();
    }

    public function Traer()
    {
        $objPdo = AccesoDatos::DameUnObjetoAcceso();

        $consulta = $objPdo->RetornarConsulta("SELECT * FROM ovnis WHERE 1");

        $consulta->execute();

        $consulta->setFetchMode(PDO::FETCH_INTO, new Ovni); 

        $arrayAux = array();

        foreach($consulta as $teles)
        {
            $auxTele = new Ovni($teles->tipo,$teles->velocidad,$teles->planeta,$teles->foto);

            array_push($arrayAux,$auxTele);
            
        }   

        return $arrayAux;

    }

    public function ActivarVelocidadWarp()
    {
        return $this->_velocidad * '10.45';
    }

    public function Existe($arOvni)
    {
        $retorno = false;

        foreach($arOvni as $ovnicitos)
        {
            if($ovnicitos->ToJSON() == $this->ToJSON())
            {
                $retorno = true;
                break;
            }
        }

        return $retorno;
    }

    /*
    public function Modificar($id,$tipo,$velocidad,$planeta,$foto)
    {
        $retorno = false;
        $objetoDatos = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoDatos->RetornarConsulta('UPDATE ovnis SET tipo = :tipo, velocidad = :velocidad, planeta = :planeta, foto = :foto WHERE id=:id');
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->bindValue(':velocidad', $velocidad, PDO::PARAM_INT);
        $consulta->bindValue(':planeta', $planeta, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $foto, PDO::PARAM_STR);
        $consulta->bindValue(':id',$id,PDO::PARAM_INT);

        $consulta->execute();
        if($consulta->rowCount() > 0) 
        {
            $retorno = true;
        }
        return $retorno;
    }*/

}

?>