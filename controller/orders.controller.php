<?php
class ControllerOrders{

    static public function ctrCreateOrder($data){

        //BUSCAR CORRELATIVO

        $pedido = ModelsOrders::mdlConsecutivoProximo();

        if(isset($data["client"]["cli_des"])){

            $datos = array(
                "sdFec_Emis"=>date("Ymd H:i:s"),
                "sDoc_Num"=>str_pad($pedido["Codigo"]+1, 10, "0", STR_PAD_LEFT),
                "sDescrip"=>"Pedido generado desde la App movil",
                "sCo_Cli"=>$data["client"]["co_cli"],
                "sCo_Tran"=>$data["transporte"],
                "sCo_Cond"=>$data["formaPago"],
                "sCo_Ven"=>$data["co_ven"],
                "sCo_Cta_Ingr_Egr"=>"NULL",
                "sCo_Mone"=>"US$",
                "bAnulado"=>0,
                "sdFec_Reg"=>date("Ymd H:i:s"),
                "sdFec_Venc"=>date("Ymd H:i:s"),
                "sStatus"=>"0",
                "deTasa"=>1,
                "sN_Control"=>"NULL",
                "sPorc_Desc_Glob"=>"NULL",
                "deMonto_Desc_Glob"=>0,
                "sPorc_Reca"=>"NULL",
                "deMonto_Reca"=>0,
                "deSaldo"=>$data["total_neto"],
                "deTotal_Bruto"=>$data["total_neto"],
                "deMonto_Imp"=>0,
                "deMonto_Imp3"=>0,
                "deOtros1"=>0,
                "deOtros2"=>0,
                "deOtros3"=>0,
                "deMonto_Imp2"=>0,
                "deTotal_Neto"=>$data["total_neto"],
                "sComentario"=>$data["direc_ven"],
                "sDir_Ent"=>"NULL",
                "bContrib"=>1,
                "bImpresa"=>0,
                "sSalestax"=>"NULL",
                "sImpfis"=>"NULL",
                "sImpfisfac"=>"NULL",
                "bVen_Ter"=>0,
                "sDis_Cen"=>"NULL",
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
                "sco_sucu_in"=>"NULL",
                "sco_us_in"=>"",
                "sMaquina"=>"appMovil"

            );

            $resultado = ModelsOrders::mdlCreateClientOrder("saPedidoVentaApp",$data,str_pad($pedido["Codigo"]+1, 10, "0", STR_PAD_LEFT));

            //armar el array para insertar el encabezado

            $resultadoProfit= ModelsOrders::mdlInsertarEncabezadoProfit($datos);


            if($resultado=="ok" && $resultadoProfit=="ok"){

                foreach ($data["products"] as $key => $value) {


                    //BUSCO LA UNIDAD DEL PRODUCTO
                    $unidaItem = ModelProducts::mdlConsultarUnidadArticulo("co_art",$value["co_art"]);

                    //se arma el array de los renglones

                    $pedidoRenglon = array(
                        "sDoc_Num"=>str_pad($pedido["Codigo"]+1, 10, "0", STR_PAD_LEFT),
                        "sCo_Art"=>$value["co_art"],
                        "sDes_Art"=>"NULL",
                        "sCo_Uni"=>$unidaItem["co_uni"],
                        "sSco_Uni"=>"NULL",
                        "sCo_Alma"=>$data["co_alma"],
                        "sCo_Precio"=>$data["client"]["tipo_precio"],
                        "sTipo_Imp"=>"1",
                        "sTipo_Imp2"=>"NULL",
                        "sTipo_Imp3"=>"NULL",
                        "deTotal_Art"=>$value["quantity"],
                        "deStotal_Art"=>0,
                        "dePrec_Vta"=>$value["price"],
                        "sPorc_Desc"=>$value["descuento"] =="" ? "NULL" : $value["descuento"],
                        "deMonto_Desc"=>$value["descuento"] =="" ? 0 :  (($value["price"] * $value["quantity"]) * $value["descuento"] )/100,
                        "dePorc_Imp"=>0,
                        "dePorc_Imp2"=>0,
                        "dePorc_Imp3"=>0,
                        "deReng_Neto"=>$value["descuento"] =="" ?  $value["price"] * $value["quantity"] : ($value["price"] * $value["quantity"]) - (($value["price"] * $value["quantity"]) * $value["descuento"] )/100,
                        "dePendiente"=>$value["quantity"],
                        "dePendiente2"=>0,
                        "sTipo_Doc"=>"NULL",
                        "gRowguid_Doc"=>"NULL",
                        "sNum_Doc"=>"NULL",
                        "deMonto_Imp"=>0,
                        "deTotal_Dev"=>0,
                        "deMonto_Dev"=>0,
                        "deOtros"=>0,
                        "deMonto_Imp2"=>0,
                        "deMonto_Imp3"=>0,
                        "sComentario"=>"NULL",
                        "sDis_Cen"=>"NULL",
                        "deMonto_Desc_Glob"=>0,
                        "deMonto_Reca_Glob"=>0,
                        "deOtros1_Glob"=>0,
                        "deOtros2_glob"=>0,
                        "deOtros3_glob"=>0,
                        "deMonto_imp_afec_glob"=>0,
                        "deMonto_imp2_afec_glob"=>0,
                        "deMonto_imp3_afec_glob"=>0,
                        "iRENG_NUM"=>$key+1,
                        "sREVISADO"=>"NULL",
                        "sTRASNFE"=>"NULL",
                        "sco_sucu_in"=>"NULL",
                        "sco_us_in"=>"",
                        "sMaquina"=>"appMovil"
                    );

                    $response = array(
                        "reng_doc" => $key+1,
                        "fact_num" => str_pad($pedido["Codigo"]+1, 10, "0", STR_PAD_LEFT),
                        "co_art" => $value["co_art"],
                        "total_art"=>$value["quantity"],
                        "prec_vta" =>$value["price"],
                        "art_des" =>$value["art_des"],
                        "total"=>($value["descuento"] =="" ?  $value["price"] * $value["quantity"] : ($value["price"] - ($value["price"] * $value["descuento"])/100) * $value["quantity"]),
                        "descuento"=>$value["descuento"],
                        "stock_prev"=>$value["stock_act"] !="NULL" ? $value["stock_act"] : 0 ,
                        "stock_act"=>$value["stock_act"]- $value["quantity"]

                    );
                    $respuesta2= ModelsOrders::mdlCreateProduct("saPedidoVentaRengApp",$response);

                    $respuesta =ModelsOrders::mdlInsertarRenglonPedido($pedidoRenglon);

                    if($respuesta=="ok"){

                        /*==================================================
                        REALIZO LOS MOVIMIENTOS EN INVENTARIO
                        ====================================================*/

                        $respo = array(
                            "sCo_Alma"=>$data["co_alma"],
                            "sCo_Art"=>$value["co_art"],
                            "sCo_Uni"=>$unidaItem["co_uni"],
                            "deCantidad"=>$value["quantity"],
                            "sTipoStock"=>"COM",
                            "bSumarStock"=>1,
                            "bPermiteStockNegativo"=>0
                        );



                       ModelProducts::mdlActualizarStock($respo);


                    }

                }

                echo json_encode(
                    array(
                        "error" => false,
                        "statusCode"=>200,
                        "renglones"=>$respuesta,
                        "pedido" => $pedido["Codigo"] + 1,
                        "mensaje" =>"Se genero el pedido # ".str_pad($pedido["Codigo"]+1, 10, "0", STR_PAD_LEFT),

                    ));

            }else{
                echo json_encode(
                    array(
                        "error" => true,
                        "statusCode"=>400,
                        "mensaje" =>"Error insertando el cliente, contacte con el administrador"
                    ));
            }

        }else{

            echo json_encode(
                array(
                    "error" => true,
                    "statusCode"=>400,
                    "mensaje" =>"No ha seleccionado un cliente"
                ));
        }
    }

    static public function ctrShowOrderUser($obj){

        $respuesta = ModelsOrders::mdlShowOrderUser("saPedidoVentaApp","co_user",$obj["co_user"]); //BUSCO TODOS LOS CLIENTES

        if(count($respuesta)>0){

            foreach ($respuesta as $key => $value){

                $resul = ModelsOrders::mdlFindOrder($value["fact_num"]);

                $resultado[$key] = array(
                    "_id"=> $key+1,
                    "doc_num" => $value["fact_num"],
                    "co_cli" => $value["co_cli"],
                    "co_user" => $value["co_user"],
                    "total_neto" => $value["tot_neto"],
                    "cli_des" => $value["cli_des"],
                    "direc1" => $value["direc1"],
                    "rif" => $value["rif"],
                    "estatus"=> isset($resul["estatus"]) ? $resul["estatus"] : "",
                    "telefonos" => $value["telefonos"],
                    "fecha_reg" =>$value["fecha_reg"],
                    "renglones" =>ModelsOrders::mdlShowOrderUser("saPedidoVentaRengApp","fact_num",$value["fact_num"])
                );
            }

            echo json_encode(
                array(
                    "error" => false,
                    "statusCode"=>200,
                    "infoOrder" =>$resultado
                ));
        }else{
            echo json_encode(
                array(
                    "error" => true,
                    "statusCode"=>400,
                    "infoOrder" =>$respuesta,
                    "mensaje" => "no tienes pedido"
                ));

        }

    }

    //REPORTES

    static public function ctrShowOrderUserReport($obj){

        $fecha_desde = $obj["dateStart"];
        $fecha_hasta = $obj["dateEnd"];

        $respuesta = ModelsOrders::mdlShowOrderUserReport($fecha_desde,$fecha_hasta); //BUSCO TODOS LOS CLIENTES

        if(count($respuesta)>0){

            foreach ($respuesta as $key => $value){

                $resultado[$key] = array(
                    "_id"=> $key+1,
                    "doc_num" => $value["doc_num"],
                    "co_cli" => $value["co_cli"],
                    "co_user" => $obj["co_user"],
                    "total_neto" => $value["total_neto"],
                    "cli_des" => $value["cli_des"],
                    "direc1" => $value["direc1"],
                    "rif" => $value["rif"],
                    "telefonos" => $value["telefonos"],
                    "fecha_reg" =>$value["fecha_reg"],
                    "estatus" =>$value["estatus"],
                    "renglones" =>ModelsOrders::mdlShowOrderReport($value["doc_num"])
                );
            }

            echo json_encode(
                array(
                    "error" => false,
                    "statusCode"=>200,
                    "infoOrder" =>$resultado
                ));
        }else{
            echo json_encode(
                array(
                    "error" => true,
                    "statusCode"=>400,
                    "infoOrder" =>"no tienes pedido"
                ));

        }

    }



    static public function ctrListOptionPedido($data){

        $listaTransporte = ModelsOrders::mdlListOptionPedido("saTransporte","co_tran","des_tran");

        $listaCondicio = ModelsOrders::mdlListOptionPedido("saCondicionPago","co_cond","cond_des");

        echo json_encode(
            array(
                "error" => false,
                "statusCode"=>200,
                "listTrans" =>$listaTransporte,
                "listCond" =>$listaCondicio

            )

        );

    }

    static public function ctrProbarStored($data){

        //$respuesta = ModelsOrders::mdlConsecutivoProximo();

        $respuesta = ModelsOrders::mdlProbarStored();


        echo json_encode(
            array(
                "error" => false,
                "statusCode"=>200,
                "resultado" =>$respuesta["co_art"]
            )

        );
    }




}