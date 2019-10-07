<?php

require_once ("./clases/Ovni.php");

$ovni = new Ovni();

$objetoOvnis = $ovni->Traer();

$tabla = ("<table align='center' border='1'
            <tr>
            <th>TIPO</th>
            <th>VELOCIDAD</th>
            <th>PLANETA ORIGEN</th>
            <th>FOTO</th>
            <th>WARP</th>
            </tr>
            ");

            var_dump($objetoOvnis);
foreach($objetoOvnis as $auxOvnis)
{
    $jsonOvni = json_decode($auxOvnis->ToJSON());
    
    $tabla .= ("<tr>
                <td>".$jsonOvni->tipo."</td>
                <td>".$jsonOvni->velocidad."</td>
                <td>".$jsonOvni->planetaOrigen."</td>
                <td>".$jsonOvni->pathFoto."</td>
                <td>".$auxOvnis->ActivarVelocidadWarp()."</td>
                </tr>");
}

$tabla .="</table>";

echo $tabla;

?>