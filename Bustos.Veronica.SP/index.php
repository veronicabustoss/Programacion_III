<?php

//Incluimos la libreria
require_once './BACKEND/Auto.php';
require_once './vendor/autoload.php';
require_once './BACKEND/Usuario.php';
//require_once './BACKEND/MW.php';

//Esto va siempre sino no podremos usar el Api Rest
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;//Se agrega para poder crear tokens


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

//Creamos la clase Slim
$app = new \Slim\App(["settings" => $config]);

//A nivel de aplicacion 
$app->post('[/]', \Auto::class . '::Alta');

$app->get('[/]', \Usuario::class . '::TraerTodos');

//$app->delete('[/]', \Media::class . '::Borrar')->add(MW::class . ':VerificarPropietario')->add(MW::class . ':VerificarToken');

///Validar bien ya que si no es propietario o encargado, el otro genera un error
//$app->put('[/]',\Media::class . '::Modificar')->add(MW::class . ':VerificarEncargadoYPropietario')->add(MW::class . ':VerificarToken');

//A nivel de ruta
$app->get('/autos', \Auto::class . '::TraerTodos');

$app->post('/usuarios', \Usuario::class . '::Alta');

$app->get('/login',\Usuario::class . '::VerificarJWTAPI');

$app->post('/login', \Usuario::class . '::CrearToken');//->add(MW::class . ':VerificarExistencia')->add(MW::class . '::VerificarVacio')->add(MW::class . ':VerificarSeteo');

$app->group('/listados',function()
{
    $this->get('/sinId',\MW::class . ':MostrarMediasMenosId')->add(MW::class . ':VerificarEncargado')->add(MW::class . ':VerificarToken');

    $this->get('/colores',\MW::class . ':MostrarColoresDistintos');//->add(MW::class . ':VerificarEncargado')->add(MW::class . ':VerificarToken');
    
    $this->get('/[{id}]', \MW::class .'::MostrarPorID');//->add(MW::class . ':VerificarEncargado')->add(MW::class . ':VerificarToken');


});

//Siempre ponerlo al final, sino, nunca se ejecutara el Slim
$app->run();
?>