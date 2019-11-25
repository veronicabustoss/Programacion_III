<?php

require_once 'IBDApis.php';
require_once 'AccesoDatos.php';
use Firebase\JWT\JWT;

class Usuario implements IBDApis
{
    public $id;
    public $correo;
    public $clave;
    public $apellido;
    public $nombre;
    public $perfil;
    public $foto;

    public function __construct($id=null,$correo=null,$clave=null,$apellido=null,$nombre=null,$perfil=null,$foto=null)
    {
        $this->id = $id;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->apellido = $apellido;
        $this->nombre = $nombre;
        $this->perfil = $perfil;
        $this->foto = $foto;
    }

    
    public static function Alta($request,$response,$next)
    {
        $ArrayDeParametros = $request->getParsedBody();

        $usuario=json_decode($ArrayDeParametros['usuario']);
        //json de retorno
        $objJson= new stdClass();
        $objJson->Exito=true;
        $objJson->Mensaje="Se agrego el usuario";


        $archivos= $request->getUploadedFiles();
        $foto=$archivos['foto']->getClientFilename();
        $destino ="./BACKEND/fotos/" . $foto;
        
        $usuaObj = new Usuario(null,$usuario->correo,$usuario->clave,$usuario->apellido,$usuario->nombre,$usuario->perfil,$foto);
        if($usuaObj->AltaUsuarioBd())
        {
            $archivos["foto"]->moveTo($destino);
            return $response->withJson($objJson,200); 
        }
        else
        {
            $objJson->Exito=false;
            $objJson->Mensaje="No se agrego el usuario";
        }
        
       return $response->withJson($objJson,418);
        
            
      
    }

    public static function TraerTodos($request,$response,$next)
    {
        $objJson= new stdClass();
        $usuario = new Usuario();
        $arrayUsua=$usuario->TraerTodasLosUsuariosBD();
        $objJson->Exito=true;
        $objJson->Mensaje="Se pudieron recuperar los usuarios";

        if($arrayUsua == null)
        {
            $objJson->Exito=false;
            $objJson->Mensaje="No se pudieron recuperar los usuarios";
            return $response->withJson($objJson,424);
        }
        $grilla ='<table class="table" border="1" align="center"><thead><tr><th>ID <th><th>CORREO<th><th>CLAVE<th><th>NOMBRE<th><th>APELLIDO<th><th>PERFIL<th><th>FOTO<th><tr><thead>';  

        foreach($arrayUsua as $usu)
        {
            $grilla .= "<tr><td>".$usu->id."<td><td>".$usu->correo."<td><td>".$usu->clave."<td><td>".$usu->nombre."<td><td>".$usu->apellido."<td><td>".$usu->perfil."<td><td><img src=./BACKEND/fotos/".$usu->foto. " height='100px' width='100px'><td><tr>";
        }

        $grilla.="<table>";

        //return $request->getBody()->Write($grilla);

        //return $response->getBody()->write("[{'tabla':'".$grilla."','Exito':'True','Mensaje':'".$objJson->Mensaje."'}]");
       
        $objJson->tabla = $grilla;

        return $response->withJson($objJson,200);

    }

    public static function Borrar($request,$response,$next)
    {

    }

    public static function Modificar($request,$response,$next)
    {
        
    }

    public static function CrearToken($request,$response,$next)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $correo = $ArrayDeParametros['correo'];
        $clave = $ArrayDeParametros['clave'];
        $obj = Usuario::ExisteEnBD($correo,$clave);
        $stdNuevo = new stdClass();
        $stdNuevo->token = null;
        $stdNuevo->exito = $obj->existe;
        if($obj->existe)
        {
            $ahora = time();
            
            $payload = array(
                'iat' => $ahora,            //CUANDO SE CREO EL JWT (OPCIONAL)
                'exp' => $ahora + (1000),     //INDICA EL TIEMPO DE VENCIMIENTO DEL JWT (OPCIONAL)
                'data' => $obj->usuario,           //DATOS DEL JWT
                'app' => "API REST 2019"    //INFO DE LA APLICACION (PROPIO)
            );

            $token = JWT::encode($payload, "miClaveSecreta");
    
            $stdNuevo = new stdClass();
            $stdNuevo->token = $token;
            $stdNuevo->exito = $obj->existe;

            return $response->withJson($stdNuevo, 200);
        }
        
        return $response->withJson($stdNuevo, 403);
        
    }

    public static function VerificarJWTAPI($request, $response, $args)
    {
        $token = $_GET["token"];
        $obj = Usuario::VerificarJWT($token);
        return $response->withJson($obj->mensaje, $obj->status);  
    }


    public function AltaUsuarioBd()
    {
        $objetoAcccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAcccesoDatos->RetornarConsulta("INSERT INTO usuarios( correo, clave, nombre, apellido, perfil,foto) VALUES(:correo,:clave,:nombre,:apellido,:perfil,:foto)");

        $consulta->bindValue(":correo",$this->correo);
        $consulta->bindValue(":clave",$this->clave);
        $consulta->bindValue(":nombre",$this->nombre);
        $consulta->bindValue(":apellido",$this->apellido);
        $consulta->bindValue(":perfil",$this->perfil);
        $consulta->bindValue(":foto",$this->foto);
        
        return $consulta->execute();  

    }

    public  function TraerTodasLosUsuariosBD()
    {
        $user = array();
        $objetoDatos =AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoDatos->RetornarConsulta('SELECT * FROM usuarios'); //Se prepara la consulta, aquí se podrían poner los alias
        $consulta->execute();
 
        while($fila = $consulta->fetch())
        {
          $media= new Usuario($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6]);
          array_push($user,$media);
        }
        return $user;
    }

    public function ExisteEnBD($correo,$clave)
    {
        $json = new stdClass(); 

        $json->existe = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuarios WHERE correo=:correo && clave=:clave");        

        $consulta->bindValue(":correo",$correo);

        $consulta->bindValue(":clave",$clave);

        $consulta->execute(); 
        
        if($consulta->rowCount() == 1) //Tengo que verificar que se modificaron las columnas
        {
            $json->existe = true;
            $json->usuario = $consulta->fetchObject();
        }

        return $json;
         //En el swtich tengo que crear un objeto de tipo JSON 
        //Me tiene que devolver si existe en base de datos
        //Hace un select en usuario donde correo sea clave como parametro
    }

    public static function VerificarJWT($token)
    {
        $retorno = new stdClass();
        $retorno->mensaje = "Token validado correctamente";
        $retorno->payload = null;
        $retorno->status = 200;
        try{
            if(empty($token) || $token === "")
            {
                throw new Exception("Token vacio");
            }
            
        $decodificado = JWT::decode($token, 'miClaveSecreta', ['HS256']);
            $retorno->payload = $decodificado->data; //Me devuelvuelve la info del usuario
            $retorno->mensaje .= " , usuario {$retorno->payload->correo}";
        }
        catch(Exception $e){
            $retorno->status = 409;
            $retorno->mensaje = "Token no valido!!! --> " . $e->getMessage();
        }
        
        return $retorno;
    }


}

?>