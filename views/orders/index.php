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

        $pedido = ModelsConfig::mdlCorrelativo("par_emp","ped_num");  //la idea es ir a la tabla de correaltivos TOMAR EL UTLIMO CORRELATIVO Y POSTERIORMENTE ACTUALIZIAR
        //actualizo el correlativo de una vez
        $actualizado = ModelsConfig::mdlUpdateCorrelativo("par_emp","ped_num",$pedido["ped_num"]);

        if($actualizado == "ok"){

            $respuesta = ControllerOrders::ctrCreateOrder($obj,$pedido["ped_num"]);

            echo $respuesta;

        }else{
            echo json_encode(
                array(
                    "error" => true,
                    "statusCode"=>400,
                    "metodo" =>$method,
                    "variables"=>$obj,
                    "correlativo"=>$pedido["ped_num"],
                    "mensaje" =>"Error actualizando correlativo, contacte al administrador"
                ));
        }
        //insertamos el encabezado




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
