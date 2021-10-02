<?php
class ControllerOrders{

    static public function ctrCreateOrder($data,$pedido){

        if(isset($data["client"]["cli_des"])){

            $datos = array(
                "fact_num" =>$pedido,
                "status"=>0,
                "descrip"=>"Pedido generado desde la App movil",
                "saldo" =>(($data["total_neto"]*16)/100)+$data["total_neto"],
                "co_cli"=>$data["client"]["co_cli"],
                "co_ven"=>$data["co_ven"],
                "co_tran"=>$data["transporte"],
                "forma_pag"=>$data["formaPago"],
                "tot_bruto"=>$data["total_neto"],
                "tot_neto" =>(($data["total_neto"]*16)/100)+$data["total_neto"],
                "iva"=>($data["total_neto"]*16)/100,
                "tasa"=>16,
                "moneda"=>"BS.S",
                "co_sucu"=>$data["sucursal"],
                "tasag"=>16,
                "tasag10"=>8,
                "tasag20"=>10
            );


            $resultado = ModelsOrders::mdlCreateClientOrder("pedidos_app",$data,$pedido);

            //armar el array para insertar el encabezado

            $resultadoProfit= ModelsOrders::mdlInsertarEncabezadoProfit("pedidos",$datos);


            if($resultado=="ok" && $resultadoProfit=="ok"){

                foreach ($data["products"] as $key => $value) {
                    //BUSCO LA UNIDAD DE RELACION PARA PODER SACAR EL CALCULO DE LA CANTIDAD Y EL MONTO

                    $datosProducto = ModelProducts::mdlShowProduct("art",$value["co_art"]);

                    $response = array(
                        "reng_doc" => $key+1,
                        "fact_num" => $pedido,
                        "co_art" => $value["co_art"],
                        "total_art"=>(trim($datosProducto["uni_venta"]) == "UND" ? $value["quantity"] * $datosProducto["uni_relac"] : $value["quantity"] ),
                        "prec_vta" =>$value["price"],
                        "art_des" =>$value["art_des"],
                        "total"=>$value["price"]*(trim($datosProducto["uni_venta"]) == "UND" ? $value["quantity"] * $datosProducto["uni_relac"] : $value["quantity"] ),
                        "descuento"=>$value["discount"],
                        "stock_prev"=>$value["stock_act"],
                        "stock_act"=>$value["stock_act"]-(trim($datosProducto["uni_venta"]) == "UND" ? $value["quantity"] * $datosProducto["uni_relac"] : $value["quantity"] )

                    );
                    ModelsOrders::mdlCreateProduct("reng_pedapp",$response);

                    //manejo de multiples unidades, busco la unidad de venta principal CAJA, UNIDAD uni_emp, tuni_venta

                    $unidadArt = ModelProducts::mdlFindUnidadMultiple($value["co_art"]);

                    $response2 = array(
                        "fact_num"=>$pedido,
                        "reng_num"=>$key+1,
                        "tipo_doc"=>'',
                        "co_art" =>$value["co_art"],
                        "co_alma"=>$data["co_alma"],
                        "total_art"=> (trim($datosProducto["uni_venta"]) == "UND" ? $value["quantity"] * $datosProducto["uni_relac"] : $value["quantity"] ),
                        "stotal_art"=>1,
                        "pendiente"=>(trim($datosProducto["uni_venta"]) == "UND" ? $value["quantity"] * $datosProducto["uni_relac"] : $value["quantity"] ),
                        "uni_venta"=>$unidadArt["unidad"],
                        "prec_vta"=>$value["price"],
                        "tipo_imp"=>1,
                        "reng_neto"=>$value["price"]*(trim($datosProducto["uni_venta"]) == "UND" ? $value["quantity"] * $datosProducto["uni_relac"] : $value["quantity"] ),
                        "cant_imp"=>0,
                        "total_uni"=>$value["stock_act"]-(trim($datosProducto["uni_venta"]) == "UND" ? $value["quantity"] * $datosProducto["uni_relac"] : $value["quantity"] )
                    );

                    $respuesta =ModelsOrders::mdlInsertarRenglonPedido("reng_ped",$response2);

                    if($respuesta=="ok"){

                        //hacer los movimientos en el almacen ST_ALMA

                        ModelProducts::mdlUpdateStAlma("st_almac",$response2);

                        ModelProducts::mdlUpdateArt("art",$response2);

                    }

                }

                echo json_encode(
                    array(
                        "error" => false,
                        "statusCode"=>200,
                        "renglones"=>$respuesta,
                        "mensaje" =>"Se genero el pedido # ".$pedido
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

        $respuesta = ModelsOrders::mdlShowOrderUser("pedidos_app","co_user",$obj["co_user"]); //BUSCO TODOS LOS CLIENTES

        if(count($respuesta)>0){

            foreach ($respuesta as $key => $value){

                $resul = ModelsOrders::mdlFindOrder($value["fact_num"]);

                $resultado[$key] = array(
                    "_id"=> $key+1,
                    "fact_num" => $value["fact_num"],
                    "co_cli" => $value["co_cli"],
                    "co_user" => $value["co_user"],
                    "tot_neto" => $value["tot_neto"],
                    "cli_des" => $value["cli_des"],
                    "direc1" => $value["direc1"],
                    "rif" => $value["rif"],
                    "estatus"=> isset($resul["estatus"]) ? $resul["estatus"] : '',
                    "telefonos" => $value["telefonos"],
                    "fecha_reg" =>$value["fecha_reg"],
                    "renglones" =>ModelsOrders::mdlShowOrderUser("reng_pedapp","fact_num",$value["fact_num"])
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
                    "infoOrder" =>"",
                    "mensaje" => "no tienes pedido"
                ));

        }

    }

    //REPORTES

    static public function ctrShowOrderUserReport($obj){

        $fecha_desde = $obj["dateStart"];
        $fecha_hasta = $obj["dateEnd"];

        $respuesta = ModelsOrders::mdlShowOrderUserReport("pedidos",$fecha_desde,$fecha_hasta); //BUSCO TODOS LOS CLIENTES

        if(count($respuesta)>0){

            foreach ($respuesta as $key => $value){

                $resultado[$key] = array(
                    "_id"=> $key+1,
                    "fact_num" => $value["fact_num"],
                    "co_cli" => $value["co_cli"],
                    "co_user" => $obj["co_user"],
                    "tot_neto" => $value["tot_neto"],
                    "cli_des" => $value["cli_des"],
                    "direc1" => $value["direc1"],
                    "rif" => $value["rif"],
                    "telefonos" => $value["telefonos"],
                    "fecha_reg" =>$value["fecha_reg"],
                    "estatus" =>$value["estatus"],
                    "renglones" =>ModelsOrders::mdlShowOrderReport("reng_ped",$value["fact_num"])
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


}