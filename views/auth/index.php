<?php
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
//busco las consultas segun el metodo de l URL

//$answer = ModelsConfig::mdlConfig();
//echo $answer["API_KEY"];

$rutas = explode("/", $_GET["ruta"]);
$method = str_replace("-","",$rutas[1]);
//$_SERVER['REQUEST_METHOD'] == 'POST' validar el metodo de envio dependiendo del tipo de consulta

switch ($method){

    case  "login":
        $respuesta = ControllerUsers::ctrLoginUser($obj);
        echo $respuesta;
        
        break;

    case "changeuser":

        $respuesta = ControllerUsers::ctrUpdateUser($obj);

        echo $respuesta;

        break;

    case "changename":

        $respuesta = ControllerUsers::ctrUpdateName($obj);

        echo $respuesta;

        break;

    case "changepassword":

        $respuesta = ControllerUsers::ctrUpdatePassword($obj);
        echo $respuesta;

        break;

    case "changeemail":

        $respuesta = ControllerUsers::ctrUpdateEmail($obj);

        echo $respuesta;

        break;

    case "changeusername":

        $respuesta = ControllerUsers::ctrUpdateDataUser($obj);

        echo $respuesta;

        break;

    case "userregister":

        $respuesta = ControllerUsers::ctrUserRegister($obj);

        echo $respuesta;

        break;

    case "verifyaccount":

        $respuesta = ControllerUsers::ctrVerifyUser($obj);

        echo $respuesta;

        break;
    case "vendedores":

        $respuesta = ControllerUsers::ctrShowVendedor();

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

