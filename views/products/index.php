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

    case  "all":
        $respuesta = ControllerProducts::ctrShowProducts($obj);
        echo $respuesta;
        break;

    case  "productregisterapp":

        $respuesta = ControllerProducts::ctrRegistrarProductoApp($obj);
        echo $respuesta;
        break;    

    case  "precioproducto":
        $respuesta = ControllerProducts::ctrConsultarPrecioArticulo($obj);
        echo $respuesta;
        break;
    
    case "buscarpreciotipocliente":

        $respuesta = ControllerProducts::ctrConsultarPrecioXTipoCliente($obj);
        echo $respuesta;
        break;

    case  "unidadproducto":

        $respuesta = ControllerProducts::ctrConsultarUnidadArticulo($obj);
        echo $respuesta;
        break;

    case  "stockproducto":

        $respuesta = ControllerProducts::ctrConsultarStockArticulo($obj);
        echo $respuesta;
        break;

    case "favorites":
        $respuesta = ControllerProducts::ctrGetProductsFavorites($obj);
        echo $respuesta;
        break;

    case "deletefavorites":
        $respuesta = ControllerProducts::ctrDeleteProductsFavorites($obj);
        echo $respuesta;
        break;

    case "addfavorites":
        $respuesta = ControllerProducts::ctrAddFavorites($obj);
        echo $respuesta;
        break;

    case "getFavoriteApi" :
        $respuesta = ControllerProducts::ctrGetfavoriteApi($obj);
        echo $respuesta;
        break;

    case "getlike":
        $respuesta = ControllerProducts::ctrGetProductsLike($obj);
        echo $respuesta;
        break;

    case "gettasa":

        $respuesta = ControllerProducts::ctrTasa();

        echo $respuesta;

        break;


    default:
        echo json_encode(
            array(
                "error" => true,
                "statusCode"=>400,
                "metodo" =>$method,
                "variable" =>$obj
            ));
            break;
}

