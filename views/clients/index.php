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
        $respuesta = ControllerClients::ctrShowClients($obj);
        echo $respuesta;
        break;

    case "getlike":
        $respuesta = ControllerClients::ctrGetClientsLike($obj);
        echo $respuesta;
        break;

    case "cuentacobrar":

        $respuesta = ControllerClients::ctrGetCuentaXCobrar($obj);
        echo $respuesta;
        break;

    case "reportnotaentrega":

        $respuesta = ControllerClients::ctrObtenerNotasEntregaXCliente($obj);

        echo $respuesta;

        break;

    case "documentoscliente" :

        $respuesta = ControllerClients::ctrObtenerDocumentos($obj);

        echo $respuesta;

        break;

    case "buscaroptions":

        $respuesta = ControllerClients::ctrObtenerOpciones();

        echo $respuesta;

        break;

    case "clientregister":

        $respuesta = ControllerClients::ctrRegistrarCliente($obj);

        echo $respuesta;

        break;

    case "clientregisterapp":

        $respuesta = ControllerClients::ctrRegistrarClienteApp($obj);

        echo $respuesta;

        break;

    case "registrarclienteprofit":

        $respuesta = ControllerClients::ctrRegistrarClienteProfit($obj);

        echo $respuesta;

        break;

    case "updateclient":

        $respuesta = ControllerClients::ctrUpdateClient($obj);

        echo $respuesta;

        break;

    case "cxcvendedor":

        $respuesta = ControllerClients::ctrCuentaXCobrarVendedor($obj);

        echo $respuesta;

        break;

    case "registertransporteapp":

        $respuesta = ControllerClients::ctrRegistrarTransporteApp($obj);

        echo $respuesta;

        break;

    case "registercondicioapp":

        $respuesta = ControllerClients::ctrRegistrarCondicioApp($obj);

        echo $respuesta;

        break;

    case "registerfacturaspendienteapp":

        $respuesta = ControllerClients::ctrRegistrarFacturasPendienteApp($obj);

        echo $respuesta;

        break;

    case "registernependienteapp":

        $respuesta = ControllerClients::ctrRegistrarNeApp($obj);

        echo $respuesta;

        break;

    case "registerdocumentosapp":

        $respuesta = ControllerClients::ctrRegistrarDocumentosApp($obj);

        echo $respuesta;

        break;

    case "obtenerfacturas":

        $respuesta = ControllerClients::ctrObtenerFacturas();

        echo $respuesta;
        break;

    case "buscarcliente":

        $respuesta = ControllerClients::ctrBuscarCliente($obj);

        echo $respuesta;

        break;

    case "uploadimage":

        $respuesta = ControllerClients::ctrCargaImagen($obj);

        echo $respuesta;

        break;

    case "registerimage":

        $respuesta = ControllerClients::ctrRegistrarImagenesCliente($obj);

        echo $respuesta;

        break;

    case "buscarimagenes":

        $respuesta = ControllerClients::ctrBuscarImagenesCliente($obj);

        echo $respuesta;

        break;

    case "deleteimagen":

        $respuesta = ControllerClients::ctrDeleteImagen($obj);

        echo $respuesta;

        break;



    default:
        echo json_encode(
            array(
                "error" => true,
                "status"=>200,
                "metodo" =>$method,
                "variable" =>$_FILES,
                "variable_POST" =>$_POST,
                "variable" =>$obj
            ));
        break;
}

