<?php
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
//busco las consultas segun el metodo de l URL

//echo $answer["API_KEY"];

$rutas = explode("/", $_GET["ruta"]);
$method = str_replace("-","",$rutas[1]);
//$_SERVER['REQUEST_METHOD'] == 'POST' validar el metodo de envio dependiendo del tipo de consulta

switch ($method){

    case  "createorder":

        $respuesta = ControllerOrders::ctrCreateOrder($obj);

        echo $respuesta;

        break;

    case "orderuser":

        $respuesta = ControllerOrders::ctrShowOrderUser($obj);

        echo $respuesta;

        break;

    case "reportorder":

        $respuesta = ControllerOrders::ctrShowOrderUserReport($obj);

        echo $respuesta;

        break;

    case "datospedido":

        $respuesta = ControllerOrders::ctrListOptionPedido($obj);

        echo $respuesta;

        break;

    case "prueba":

        $respuesta = ControllerOrders::ctrProbarStored($obj);

        echo $respuesta;

        break;


    default:
        echo json_encode(
            array(
                "error" => true,
                "statusCode"=>400,
                "metodo" =>$method,
                "variables"=>$obj
            ));
        break;
}
