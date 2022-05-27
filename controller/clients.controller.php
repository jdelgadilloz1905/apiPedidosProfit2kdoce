<?php
class ControllerClients{

    /*=============================================
    MOSTRAR LOS CLIENTES
    =============================================*/
    static public function ctrShowClients($data){

        $tabla = "clientes";

        $respuesta = ModelClients::mdlShowClients($tabla,$data);

        if(count($respuesta)>0){

            $result = array(
                "status"=>200,
                "infoCli" =>$respuesta
            );

        }else{
            $result = array(
                "status"=>400,
                "infoCli" =>""
            );
        }


        echo json_encode($result,http_response_code($result["statusCode"]));

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

        $respuesta = ModelClients::mdlGetCuentaXCobrar("facturas",$obj);

        if(count($respuesta)>0){

            $result = array(
                "error" => false,
                "statusCode"=>200,
                "infoFacturaPendiente" =>$respuesta
            );

        }else{
            $result = array(
                "error" => false,
                "statusCode"=>400,
                "infoFacturaPendiente" =>""
            );
        }
        echo json_encode($result,http_response_code($result["statusCode"]));
    }

    static public function ctrCuentaXCobrarVendedor($obj){

        $respuesta = ModelClients::mdlCuentaXCobrarVendedor($obj);

        if(count($respuesta)>0){

            $result = array(
                "error" => false,
                "statusCode"=>200,
                "infoDocumentos" =>$respuesta
            );

        }else{
            $result = array(
                "error" => false,
                "statusCode"=>400,
                "infoDocumentos" =>""
            );
        }
        echo json_encode($result,http_response_code($result["statusCode"]));
    }

    static public function ctrObtenerNotasEntregaXCliente($obj){

        $respuesta = ModelClients::mdlObtenerNotasEntregaXCliente($obj);

        if(count($respuesta)>0){

            $result = array(
                "error" => false,
                "statusCode"=>200,
                "infoNotaEntrega" =>$respuesta
            );

        }else{
            $result = array(
                "error" => false,
                "statusCode"=>400,
                "infoNotaEntrega" =>""
            );
        }
        echo json_encode($result,http_response_code($result["statusCode"]));



    }

    static public function ctrObtenerDocumentos($obj){

        $respuesta = ModelClients::mdlObtenerDocumentos($obj);

        if(count($respuesta)>0){

            echo json_encode(
                array(
                    "error" => false,
                    "statusCode"=>200,
                    "infoCobros" =>$respuesta
                )

            );
        }else{
            echo json_encode(
                array(
                    "error" => true,
                    "statusCode"=>400,
                    "infoCobros" =>"No se encontraron registros"
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
                "total_neto" => number_format($value["saldo"]/($value["tasa_paralelo"] > 0 ? $value["tasa_paralelo"]: 1), 2, ',', '.'),
                "monto_imp" => number_format($value["monto_imp"], 2, ',', '.'),
                "fec_venc"  => $value["fec_emis"],

            );
        }

        return $resultado;
    }

    static public function ctrObtenerOpciones(){

        $getTipo  = ModelClients::mdlListOptionCliente("tipo_cliente","tip_cli","des_tipo");

        $getCondicionespago= ModelClients::mdlListOptionCliente("condicion_pago","co_cond","cond_des");

        $getSegmento= ModelClients::mdlListOptionCliente("segmento","co_seg","seg_des");

        //$proximoNumero = ModelClients::mdlProximoNumero();

        echo json_encode(
            array(
                "error" => false,
                "statusCode"=>200,
                "infoCondiciones" =>$getCondicionespago,
                "infoTipo" =>$getTipo,
                "infoSegmento" =>$getSegmento
            )
        );
    }

    static public function ctrRegistrarCliente($data){

        //$proximoNumero = ModelClients::mdlProximoNumero(); //ya no va se va tomar el codigo del rif del mismo cliente



        $datos = array(
            "co_cli"=>"",
            "cli_des"=>strtoupper($data["nombre"]),
            "co_seg"=>$data["segmento"],
            "lunes"=>$data["lunes"] == true ? 1 : 0,
            "martes"=>$data["martes"] == true ? 1 : 0,
            "miercoles"=>$data["miercoles"] == true ? 1 : 0,
            "jueves"=>$data["jueves"] == true ? 1 : 0,
            "viernes"=>$data["viernes"] == true ? 1 : 0,
            "sabado"=>$data["sabado"] == true ? 1 : 0,
            "domingo"=>$data["domingo"] == true ? 1 : 0,
            "direc1"=>strtoupper($data["direccion"]),
            "telefonos"=>$data["telefono"],
            "respons"=>strtoupper($data["responsable"]),
            "tip_cli"=>$data["tipo"],
            "rif"=>$data["rif"],
            "comentario"=>strtoupper($data["comentario"]),
            "ciudad"=>strtoupper($data["ciudad"]),
            "email"=>strtoupper($data["email"]),
            "dir_ent2"=>strtoupper($data["direccionEntrega"]),
            "co_ven"=>$data["co_ven"],
            "cond_pag"=>$data["condicion"],
            "tipo_precio"=>1,
        );


        $resultado = ModelClients::mdlRegisterFile("clientes",$datos);

        echo json_encode($resultado,http_response_code($resultado["status"]));
    }


    /*DESDE PROFIT  */
    static public function ctrRegistrarClienteApp($data){

        $respuesta = ModelClients::mdlShowFileApp("clientes","co_cli", $data["co_cli"]);
        
        if(isset($respuesta["id"])){
            
            $result = ModelClients::mdlUpdateCliente("clientes",$data);
        }else{
            $result = ModelClients::mdlRegisterFile("clientes", $data);
        }
        echo json_encode($result,http_response_code($result["status"]));


    }

    static public function ctrRegistrarTransporteApp($data){
        
        $respuesta = ModelClients::mdlShowFileApp("transporte","co_tran", $data["co_tran"]);
        
        if(isset($respuesta["id"])){
            
            $result = ModelClients::mdlUpdateTransporte("transporte",$data);
        }else{
            $result = ModelClients::mdlRegisterFile("transporte", $data);
        }
        echo json_encode($result,http_response_code($result["status"]));

    }

    static public function ctrRegistrarCondicioApp($data){
        
        $respuesta = ModelClients::mdlShowFileApp("condicion_pago","co_cond", $data["co_cond"]);
        
        if(isset($respuesta["id"])){
            
            $result = ModelClients::mdlUpdateCondicio("condicion_pago",$data);
        }else{
            $result = ModelClients::mdlRegisterFile("condicion_pago", $data);
        }
        echo json_encode($result,http_response_code($result["status"]));

    }

    static public function ctrRegistrarFacturasPendienteApp($data){

        $respuesta = ModelClients::mdlShowFileApp("facturas","doc_num", trim($data["doc_num"]));

        if(isset($respuesta["id"])){

            $result = ModelClients::mdlUpdateFacturaPendiente("facturas",$data);
        }else{
            $result = ModelClients::mdlRegisterFile("facturas", $data);
        }
        echo json_encode($result,http_response_code($result["status"]));
    }

    static public function ctrRegistrarNeApp($data){

        $respuesta = ModelClients::mdlShowFileApp("notas_entregas","doc_num", trim($data["doc_num"]));

        if(isset($respuesta["id"])){

            $result = ModelClients::mdlUpdateFacturaPendiente("notas_entregas",$data);  //se deja el mismo nombre porque la tabla es la misma y no se cambia la tabla porque despues para recorrer se torna lento el renderizado
        }else{
            $result = ModelClients::mdlRegisterFile("notas_entregas", $data);
        }
        echo json_encode($result,http_response_code($result["status"]));


    }

    static public function ctrRegistrarDocumentosApp($data){

        $respuesta = ModelClients::mdlShowDocumentosApp("documentos","co_tipo_doc", trim($data["co_tipo_doc"]), "nro_doc", trim($data["nro_doc"]));

        if(isset($respuesta["id"])){

            $result = ModelClients::mdlUpdateDocumentos("documentos",$data);  //se deja el mismo nombre porque la tabla es la misma y no se cambia la tabla porque despues para recorrer se torna lento el renderizado
        }else{
            $result = ModelClients::mdlRegisterFile("documentos", $data);
        }
        echo json_encode($result,http_response_code($result["status"]));


    }

    static public function ctrRegistrarClienteProfit(){

        $respuesta = ModelClients::mdlShowClientsParaProfit();
        if(count($respuesta)>0){

            $result = array(
                    "status"=>200,
                    "result" =>$respuesta
                );
        }else{
            $result=array(
                    "status"=>400,
                    "result" =>"No se encontraron registros"
                );
        }

        echo json_encode($result,http_response_code($result["status"]));


    }

    static public function ctrUpdateClient($data){

        $result = ModelClients::mdlUpdateClient($data);

        echo json_encode($result,http_response_code($result["status"]));
    }
}