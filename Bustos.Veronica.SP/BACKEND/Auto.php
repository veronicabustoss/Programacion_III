<?php
require_once 'IBDApis.php';

class Auto implements IBDApis
{
    public $id;
    public $color;
    public $marca;
    public $precio;
    public $modelo;

    public function __construct($id=null,$color=null,$marca=null,$precio=null,$modelo=null)
    {
        $this->id = $id;
        $this->color = $color;
        $this->marca = $marca;
        $this->precio = $precio;
        $this->modelo = $modelo;
    }

    
    public static function Alta($request,$response,$next)
    {
        $ArrayDeParametros = $request->getParsedBody();

        $media=json_decode($ArrayDeParametros['auto']);
        //json de retorno
        $objJson= new stdClass();
        $objJson->Exito=true;
        $objJson->Mensaje="Se agrego el auto";
        $mediaObj = new Auto(null,$media->color,$media->marca,$media->precio,$media->modelo);
        if(!$mediaObj->AltaMediaBd())
        {
            $objJson->Exito=false;
            $objJson->Mensaje="NO se agrego el auto";
            return $response->withJson($objJson,418);
        }
       return $response->withJson($objJson,200);
      // return $response->getBody()->write("Se ha insertado la media."); //Podria poner el
    }

    public static function TraerTodos($request,$response,$next)
    {
        $objJson= new stdClass();
        $auto = new Auto();
        $arrayAutos=$auto->TraerTodasLasMediasBD();
        $objJson->Exito=true;
        $objJson->Mensaje="Se recuperaron todos los autos";

        if($arrayAutos == null)
        {
            $objJson->Exito=false;
            $objJson->Mensaje="No se pudieron recuperar los autos";
            return $response->withJson($objJson,424);
        }
        $grilla = '<table class="table" border="1" align="center"><thead><tr><th>ID<th><th>COLOR<th><th>MARCA<th><th>PRECIO<th><th>MODELO<th><tr><thead>';  

        foreach($arrayAutos as $aut)
        {
            $grilla .= "<tr><td>".$aut->id."<td><td>".$aut->color."<td><td>".$aut->marca."<td><td>".$aut->precio."<td><td>".$aut->modelo."<td><tr>";
        }

        $grilla.="<table>";

      //$response->getBody()->write($grilla);

        $objJson->tabla = $grilla;
      return $response->withJson($objJson,200);
      // return $response->withJson($objJson,200);
    }

    public static function Borrar($request,$response,$next)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $id=$ArrayDeParametros['id'];

        $media = new Media();

        $cantidadDeBorrados=$media->BorrarMediaBD($id);
        $objDelaRespuesta= new stdclass();
        
        $objDelaRespuesta->cantidad=$cantidadDeBorrados;
        
        if($cantidadDeBorrados>0)
		{
			$objDelaRespuesta->resultado="Se borro la media con exito!!!";
		}
		else
		{
		   $objDelaRespuesta->resultado="No se borro la media!!!";
        }
       return $response->withJson($objDelaRespuesta, 200); 

    }
    public static function Modificar($request,$response,$next)
    {
        $obj = json_decode($request->getParsedBody()["media"]);
        $auxMedia = new Media();

        $objDelaRespuesta= new stdclass();

        $verificar = $auxMedia->ModificarMediaBD($obj);

        if($verificar)
        {
            $objDelaRespuesta->resultado="La media se pudo modificar con exito!!!";
        }
        else
        {
            $objDelaRespuesta->resultado="La media no se pudo modificar!!!";
        }
        
        return $response->withJson($objDelaRespuesta, 200); 
        
    }


    //----------------------------------------------------------------------------------------------------------------------------

    public function AltaMediaBd()
    {
        $objetoAcccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAcccesoDatos->RetornarConsulta("INSERT INTO autos(color,marca,precio,modelo) VALUES (:color,:marca,:precio,:modelo)");

        $consulta->bindValue(":color",$this->color);
        $consulta->bindValue(":marca",$this->marca);
        $consulta->bindValue(":precio",$this->precio);
        $consulta->bindValue(":modelo",$this->modelo);
        
        return $consulta->execute();  

    }

    public  function TraerTodasLasMediasBD()
    {
        $medias = array();
        $objetoDatos =AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoDatos->RetornarConsulta('SELECT * FROM autos'); //Se prepara la consulta, aquí se podrían poner los alias
        $consulta->execute();
 
        while($fila = $consulta->fetch())
        {
          $media= new Auto($fila[0],$fila[1],$fila[2],$fila[3],$fila[4]);
          array_push($medias,$media);
        }
        return $medias;
    }

    public function BorrarMediaBD($id)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("DELETE FROM medias WHERE id=:id");	
            $consulta->bindValue(':id',$id);		
            $consulta->execute();
            return $consulta->rowCount();
 
    }

    public  function ModificarMediaBD($objMedia)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE medias SET color=:color,marca=:marca,precio=:precio,talle=:talle WHERE id=:id");
        $consulta->bindValue(':id', $objMedia->id, PDO::PARAM_INT);
        $consulta->bindValue(':color',$objMedia->color);
        $consulta->bindValue(':marca',$objMedia->marca);
        $consulta->bindValue(':precio',$objMedia->precio);
        $consulta->bindValue(':talle',$objMedia->talle);
        $consulta->execute();

        return $consulta->execute();
    }

}


?>