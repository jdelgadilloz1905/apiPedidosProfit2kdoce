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

        if(count($respuesta)>0){

            echo json_encode(
                array(
                    "error" => false,
                    "statusCode"=>200,
                    "infoCobros" =>self::ctrPrepararJsonDocumento($respuesta)
                )

            );
        }else{
            echo json_encode(
                array(
                    "error" => true,
                    "statusCode"=>400,
                    "infoDocumentos" =>"No se encontraron registros"
                ));

        }

    }

    static public function ctrPrepararJsonDocumento($data){

        foreach ($data as $key => $value){

            $resultado[$key] =array(
                "id" => $key+1,
                "co_tipo_doc" => $value["co_tipo_doc"],
                "nro_doc" => $value["nro_doc"],
                "co_mone" => $value["co_mone"],
                "observa" => $value["observa"],
                "fec_emis" => $value["fec_emis"],
                "total_neto" => $value["total_neto"],
                "monto_imp" => $value["monto_imp"]

            );
        }

        return $resultado;
    }
}