<?php
interface IBDApis
{
    public static function Alta($request,$response,$next);
    public static function TraerTodos($request,$response,$next);
    public static function Borrar($request,$response,$next);
    public static function Modificar($request,$response,$next);
}
?>