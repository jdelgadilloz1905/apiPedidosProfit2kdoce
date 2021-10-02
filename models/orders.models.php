<?php

require_once "conexion.php";

class ModelsOrders{

    static public function mdlCreateClientOrder($tabla,$datos, $pedido){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (fact_num, co_cli, co_user, tot_neto, cli_des, direc1, rif, telefonos, co_tran, forma_pag,co_sucu) 
                                                            VALUES (:fact_num, :co_cli, :co_user, :tot_neto, :cli_des, :direc1, :rif, :telefonos, :co_tran, :forma_pag, :co_sucu)");


        $stmt->bindParam(":fact_num", $pedido, PDO::PARAM_STR);

        $stmt->bindParam(":co_cli", $datos["client"]["co_cli"], PDO::PARAM_STR);

        $stmt->bindParam(":co_user", $datos["co_user"], PDO::PARAM_STR);

        $stmt->bindParam(":tot_neto", $datos["total_neto"], PDO::PARAM_STR);

        $stmt->bindParam(":cli_des", $datos["client"]["cli_des"], PDO::PARAM_STR);

        $stmt->bindParam(":direc1", $datos["client"]["direc1"], PDO::PARAM_STR);

        $stmt->bindParam(":rif", $datos["client"]["rif"], PDO::PARAM_STR);

        $stmt->bindParam(":telefonos", $datos["client"]["telefonos"], PDO::PARAM_STR);

        $stmt->bindParam(":co_tran", $datos["transporte"], PDO::PARAM_STR);

        $stmt->bindParam(":forma_pag", $datos["formaPago"], PDO::PARAM_STR);

        $stmt->bindParam(":co_sucu", $datos["sucursal"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt->close();

        $stmt = null;
    }

    static public function mdlCreateProduct($tabla,$datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (reng_doc, fact_num, co_art, total_art, prec_vta, total, art_des, descuento, stock_prev, stock_act, fecha_reg) 
                                                                VALUES (:reng_doc, :fact_num, :co_art, :total_art, :prec_vta, :total, :art_des, :descuento, :stock_prev, :stock_act, SYSDATETIME())");

        $stmt->bindParam(":reng_doc", $datos["reng_doc"], PDO::PARAM_STR);

        $stmt->bindParam(":fact_num", $datos["fact_num"], PDO::PARAM_INT);

        $stmt->bindParam(":co_art", $datos["co_art"], PDO::PARAM_STR);

        $stmt->bindParam(":total_art", $datos["total_art"], PDO::PARAM_STR);

        $stmt->bindParam(":prec_vta", $datos["prec_vta"], PDO::PARAM_STR);

        $stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);

        $stmt->bindParam(":art_des", $datos["art_des"], PDO::PARAM_STR);

        $stmt->bindParam(":descuento", $datos["descuento"], PDO::PARAM_STR);

        $stmt->bindParam(":stock_prev", $datos["stock_prev"], PDO::PARAM_STR);

        $stmt->bindParam(":stock_act", $datos["stock_act"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt->close();

        $stmt = null;


    }

    static public function mdlShowOrderUser($tabla,$item,$valor){

        $stmt = Conexion::conectar()->prepare("SELECT  * FROM $tabla  where $item = :$item order by fact_num desc");

        $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }




    //ENCABEZADO
    static public function mdlShowOrderUserReport($tabla,$fecha_desde,$fecha_hasta){

        $stmt = Conexion::conectar()->query("select p.fact_num,p.co_cli,p.tot_neto, p.dir_ent direc1, 
                                                        (case when p.status=0 THEN 'Sin procesar' ELSE 
                                                        CASE WHEN p.status=1 THEN 'Parc procesado' ELSE
                                                        CASE WHEN p.status=2 THEN 'Procesado' END END END) AS estatus, 
                                                        p.fec_emis fecha_reg,c.cli_des, c.rif, c.telefonos
                                                            from $tabla p 
                                                            left join clientes c
                                                            on p.co_cli = c.co_cli
                                                            where p.fec_emis between DATEADD(DAY,-1,'".$fecha_desde."') and '".$fecha_hasta."' order by p.fact_num DESC");


        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    //DETALLE
    static public function mdlShowOrderReport($tabla,$valor){

        $stmt = Conexion::conectar()->query("select r.reng_doc,r.fact_num,r.co_art,r.co_alma,r.total_art,r.prec_vta,r.total_art total, fecha_reg = '',  a.art_des, descuento =0, stock_prev=0, a.stock_act
                                                            from $tabla r
                                                            left join art a
                                                            on r.co_art = a.co_art  where r.fact_num = $valor ");

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlListOptionPedido($tabla,$item1,$item2){

        $stmt = Conexion::conectar()->query("SELECT $item1 as value,$item2 as label FROM $tabla  ");

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }
    /*=======================================================
    FUNCIONES QUE TOCAN LA BASE DE DATOS PROFIT DIRECTO
    =========================================================*/
    static public function mdlInsertarEncabezadoProfit($tabla,$datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (fact_num,status,descrip,saldo,co_cli,co_ven,co_tran,forma_pag,tot_bruto,tot_neto,iva,tasa,moneda,co_sucu,tasag,tasag10,tasag20, fec_emis,fec_venc,feccom) 
                                                    VALUES (:fact_num, :status, :descrip, :saldo, :co_cli, :co_ven, :co_tran, :forma_pag, :tot_bruto, :tot_neto, :iva,:tasa, :moneda,:co_sucu,:tasag,:tasag10,:tasag20, SYSDATETIME(),SYSDATETIME(),SYSDATETIME())");

        $stmt->bindParam(":fact_num", $datos["fact_num"], PDO::PARAM_INT);

        $stmt->bindParam(":status", $datos["status"], PDO::PARAM_STR);

        $stmt->bindParam(":descrip", $datos["descrip"], PDO::PARAM_STR);

        $stmt->bindParam(":saldo", $datos["saldo"], PDO::PARAM_STR);

        $stmt->bindParam(":co_cli", $datos["co_cli"], PDO::PARAM_STR);

        $stmt->bindParam(":co_ven", $datos["co_ven"], PDO::PARAM_STR);

        $stmt->bindParam(":co_tran", $datos["co_tran"], PDO::PARAM_STR);

        $stmt->bindParam(":forma_pag", $datos["forma_pag"], PDO::PARAM_STR);

        $stmt->bindParam(":tot_bruto", $datos["tot_bruto"], PDO::PARAM_STR);

        $stmt->bindParam(":tot_neto", $datos["tot_neto"], PDO::PARAM_STR);

        $stmt->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);

        $stmt->bindParam(":tasa", $datos["tasa"], PDO::PARAM_STR);

        $stmt->bindParam(":moneda", $datos["moneda"], PDO::PARAM_STR);

        $stmt->bindParam(":co_sucu", $datos["co_sucu"], PDO::PARAM_STR);

        $stmt->bindParam(":tasag", $datos["tasag"], PDO::PARAM_STR);

        $stmt->bindParam(":tasag10", $datos["tasag10"], PDO::PARAM_STR);

        $stmt->bindParam(":tasag20", $datos["tasag20"], PDO::PARAM_STR);



        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt->close();

        $stmt = null;
    }

    static public function mdlInsertarRenglonPedido($tabla,$datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (fact_num, reng_num,tipo_doc,co_art,co_alma,total_art,stotal_art,pendiente,uni_venta,prec_vta,tipo_imp,reng_neto,cant_imp,total_uni, fec_lote) 
                                                    VALUES (:fact_num,:reng_num,:tipo_doc,:co_art,:co_alma,:total_art,:stotal_art,:pendiente,:uni_venta,:prec_vta,:tipo_imp,:reng_neto,:cant_imp,:total_uni,SYSDATETIME())");

        $stmt->bindParam(":fact_num", $datos["fact_num"], PDO::PARAM_STR);

        $stmt->bindParam(":reng_num", $datos["reng_num"], PDO::PARAM_STR);

        $stmt->bindParam(":tipo_doc", $datos["tipo_doc"], PDO::PARAM_STR);

        $stmt->bindParam(":co_art", $datos["co_art"], PDO::PARAM_STR);

        $stmt->bindParam(":co_alma", $datos["co_alma"], PDO::PARAM_STR);

        $stmt->bindParam(":total_art", $datos["total_art"], PDO::PARAM_STR);

        $stmt->bindParam(":stotal_art", $datos["stotal_art"], PDO::PARAM_STR);

        $stmt->bindParam(":pendiente", $datos["pendiente"], PDO::PARAM_STR);

        $stmt->bindParam(":uni_venta", $datos["uni_venta"], PDO::PARAM_STR);

        $stmt->bindParam(":prec_vta", $datos["prec_vta"], PDO::PARAM_STR);

        $stmt->bindParam(":tipo_imp", $datos["tipo_imp"], PDO::PARAM_STR);

        $stmt->bindParam(":reng_neto", $datos["reng_neto"], PDO::PARAM_STR);

        $stmt->bindParam(":cant_imp", $datos["cant_imp"], PDO::PARAM_STR);

        $stmt->bindParam(":total_uni", $datos["total_uni"], PDO::PARAM_STR);



        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt->close();

        $stmt = null;
    }


    static public function mdlFindOrder($fact_num){

        $stmt = Conexion::conectar()->query("select  
                                                        (case when status=0 THEN 'Sin procesar' ELSE 
                                                        CASE WHEN status=1 THEN 'Parc procesado' ELSE
                                                        CASE WHEN status=2 THEN 'Procesado' END END END) AS estatus 
                                                            from pedidos  
                                                            where fact_num = '".$fact_num."' ");

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }



}