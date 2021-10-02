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
        $respuesta = ModelClients::mdlGetCuentaXCobrar("factura",$obj);

        echo json_encode($respuesta);
    }
}