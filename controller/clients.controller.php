<?php
class ControllerClients{

    /*=============================================
    MOSTRAR LOS CLIENTES
    =============================================*/
    static public function ctrShowClients($data){

        $tabla = "saCliente";

        $respuesta = ModelClients::mdlShowClients($tabla,$data);

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

    static public function ctrCuentaXCobrarVendedor($obj){

        $respuesta = ModelClients::mdlCuentaXCobrarVendedor($obj);

        if(count($respuesta)>0){

            echo json_encode(
                array(
                    "error" => false,
                    "statusCode"=>200,
                    "cantidad" =>count($respuesta),
                    "infoDocumentos" =>$respuesta
                ));
        }else{
            echo json_encode(
                array(
                    "error" => true,
                    "statusCode"=>400,
                    "cantidad" =>0,
                    "infoDocumentos" =>"No se encontraron registros"
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
                "total_neto" => number_format($value["saldo"]/($value["tasa_paralelo"] > 0 ? $value["tasa_paralelo"]: 1), 2, ',', '.'),
                "monto_imp" => number_format($value["monto_imp"], 2, ',', '.'),
                "fec_venc"  => $value["fec_emis"],

            );
        }

        return $resultado;
    }

    static public function ctrObtenerOpciones(){

        $getTipo  = ModelClients::mdlListOptionCliente("saTipoCliente","tip_cli","des_tipo");

        $getCondicionespago= ModelClients::mdlListOptionCliente("saCondicionPago","co_cond","cond_des");

        $getSegmento= ModelClients::mdlListOptionCliente("saSegmento","co_seg","seg_des");

        $proximoNumero = ModelClients::mdlProximoNumero();

        echo json_encode(
            array(
                "error" => false,
                "statusCode"=>200,
                "infoCondiciones" =>$getCondicionespago,
                "infoTipo" =>$getTipo,
                "infoSegmento" =>$getSegmento,
                "correlativo" => $proximoNumero["Codigo"] +1
            )
        );
    }

    static public function ctrRegistrarCliente($data){

        $proximoNumero = ModelClients::mdlProximoNumero();

        $datos = array(
            "sCo_Cli"=>$proximoNumero["Codigo"] +1,
            "sCli_Des"=>strtoupper($data["nombre"]),
            "sCo_Seg"=>$data["segmento"],
            "sCo_Zon"=>"0002",
            "sSalesTax"=>"NULL",
            "sLogin"=>"NULL",
            "binactivo"=>1,
            "blunes"=>0,
            "bmartes"=>0,
            "bmiercoles"=>0,
            "bjueves"=>0,
            "bviernes"=>0,
            "bsabado"=>0,
            "bdomingo"=>0,
            "bcontrib"=>1,
            "bvalido"=>0,
            "bsincredito"=>0,
            "sDirec1"=>strtoupper($data["direccion"]),
            "sDirec2"=>strtoupper($data["direccion"]),
            "stelefonos"=>$data["telefono"],
            "sfax"=>"NULL",
            "sRespons"=>strtoupper($data["responsable"]),
            "sdfecha_reg"=>date("Ymd H:i:s"),
            "stip_cli"=>$data["tipo"],
            "demont_cre"=>0,
            "iplaz_pag"=>0,
            "iId"=>0,
            "iPuntaje"=>0,
            "dedesc_ppago"=>0,
            "dedesc_glob"=>0,
            "srif"=>$data["rif"],
            "sdis_cen"=>"NULL",
            "snit"=>"NULL",
            "sco_cta_ingr_egr"=>"120101",
            "scomentario"=>"NULL",
            "bjuridico"=>0,
            "itipo_adi"=>1,
            "smatriz"=>"NULL",
            "sco_tab"=>"NULL",
            "stipo_per"=>"NULL",
            "sco_pais"=>"VE",
            "sciudad"=>"CARACAS",
            "szip"=>"NULL",
            "sWebSite"=>"NULL",
            "bcontribu_e"=>0,
            "brete_regis_doc"=>0,
            "deporc_esp"=>0,
            "spassword"=>"NULL",
            "sestado"=>"NULL",
            "sserialp"=>"NULL",
            "semail"=>strtoupper($data["email"]),
            "sdir_ent2"=>"NULL",
            "sfrecu_vist"=>"NULL",
            "shorar_caja"=>"NULL",
            "sco_ven"=>$data["co_ven"],
            "sco_mone"=>"US$",
            "scond_pag"=>$data["condicion"],
            "sTComp"=>"NULL",
            "sN_db"=>"NULL",
            "sN_cr"=>"NULL",
            "semail_alterno"=>"NULL",
            "sCampo1"=>"NULL",
            "sCampo2"=>"NULL",
            "sCampo3"=>"NULL",
            "sCampo4"=>"NULL",
            "sCampo5"=>"NULL",
            "sCampo6"=>"NULL",
            "sCampo7"=>"NULL",
            "sCampo8"=>"NULL",
            "sRevisado"=>"NULL",
            "sTrasnfe"=>"NULL",
            "sco_sucu_in"=>$data["sucursal"],
            "sco_us_in"=>"PROFIT",
            "sMaquina"=>"AppMovil"
        );


        $resultado = ModelClients::mdlregistrarCliente($datos);

        if($resultado == "ok"){
            echo json_encode(
                array(
                    "error" => false,
                    "statusCode"=>200,
                    "mensaje" =>"Registro creado exitosamente #".$proximoNumero["Codigo"] +1
                ));
        }else{
            echo json_encode(
                array(
                    "error" => true,
                    "statusCode"=>400,
                    "mensaje" =>"Error, registrando el cliente ". $resultado
                ));
        }
    }
}