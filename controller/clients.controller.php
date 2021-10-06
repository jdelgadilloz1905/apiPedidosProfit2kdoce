<?php
class ControllerClients{

    /*=============================================
    MOSTRAR LOS CLIENTES
    =============================================*/
    static public function ctrShowClients(){

        $tabla = "saCliente";

        $respuesta = ModelClients::mdlShowClients($tabla);

        echo json_encode($respuesta);

    }

    /*=============================================
      CLIENTE SEARCH
      =============================================*/
    static public function ctrGetClientsLike($obj){

        $tabla = "saCliente";

        $respuesta = ModelClients::mdlGetClientsLike($tabla,$obj["like"]);

        echo json_encode($respuesta);
    }

    static public function ctrGetCuentaXCobrar($obj){

        $respuesta = ModelClients::mdlGetCuentaXCobrar("saFacturaVenta",$obj);

        if(count($respuesta)>0){

            echo json_encode(
                array(
                    "error" => false,
                    "statusCode"=>200,
                    "infoFacturaPendiente" =>$respuesta
                ));
        }else{
            echo json_encode(
                array(
                    "error" => true,
                    "statusCode"=>400,
                    "infoFacturaPendiente" =>"No se encontraron registros"
                ));

        }
    }

    static public function ctrObtenerNotasEntregaXCliente($obj){

        $respuesta = ModelClients::mdlObtenerNotasEntregaXCliente($obj);

        if(count($respuesta)>0){

            echo json_encode(
                array(
                    "error" => false,
                    "statusCode"=>200,
                    "infoNotaEntrega" =>$respuesta
                ));
        }else{
            echo json_encode(
                array(
                    "error" => true,
                    "statusCode"=>400,
                    "infoNotaEntrega" =>"No se encontraron registros"
                ));

        }

    }

    static public function ctrObtenerDocumentos($obj){

        $respuesta = ModelClients::mdlObtenerDocumentos($obj);

        echo json_encode(
            array(
                "error" => false,
                "statusCode"=>200,
                "resultado" =>print_r($respuesta)
            )

        );

//        if(count($respuesta)>0){
//
//            echo json_encode(
//                array(
//                    "error" => false,
//                    "statusCode"=>200,
//                    "infoDocumentos" =>$respuesta
//                ));
//        }else{
//            echo json_encode(
//                array(
//                    "error" => true,
//                    "statusCode"=>400,
//                    "infoDocumentos" =>"No se encontraron registros"
//                ));
//
//        }

    }
}