<?php

require_once "conexion.php";

class ModelsOrders{

    static public function mdlCreateClientOrder($tabla,$datos, $pedido){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (fact_num, co_cli, co_user, tot_neto, cli_des, direc1, rif, telefonos, co_tran, forma_pag,co_sucu, id_app) 
                                                            VALUES (:fact_num, :co_cli, :co_user, :tot_neto, :cli_des, :direc1, :rif, :telefonos, :co_tran, :forma_pag, :co_sucu, :id_app)");


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

        $stmt->bindParam(":id_app", $datos["id_pedido"], PDO::PARAM_STR);

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

        $stmt = Conexion::conectar()->prepare("SELECT  o.*, c.cli_des FROM $tabla  o LEFT JOIN clientes c ON o.co_cli = c.co_cli	WHERE o.$item = :$item order BY o.fecha_creacion desc ");

        $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    //ENCABEZADO
    static public function mdlShowOrderUserReport($co_ven,$co_user,$fecha_desde,$fecha_hasta){

        if($co_ven == 99999){
            $stmt = Conexion::conectar()->query("SELECT o.*, c.cli_des FROM ordenes o 
                                                        LEFT JOIN clientes c 
                                                          ON o.co_cli = c.co_cli 
                                                            where o.fecha_creacion between '".$fecha_desde."' and '".$fecha_hasta."' ORDER BY o.doc_num DESC");

        }else{

            $stmt = Conexion::conectar()->query("SELECT o.*, c.cli_des FROM ordenes o 
                                                        LEFT JOIN clientes c 
                                                          ON o.co_cli = c.co_cli 
                                                            where o.fecha_creacion between '".$fecha_desde."' and '".$fecha_hasta."' AND o.co_user = '".$co_user."' ORDER BY o.doc_num DESC");
        }



        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    //DETALLE
    static public function mdlShowOrderReport($valor){

        $stmt = Conexion::conectar()->query("SELECT r.reng_num,r.doc_num,r.co_art,r.co_alma,r.total_art,FORMAT(r.prec_vta,'##,###.00') prec_vta,r.total_art total,
                                                            fecha_reg = '',  a.art_des, descuento =0, stock_prev=0, 
                                                            (SELECT ISNULL([ACT], 0) AS STOCK_ACT
                                                                FROM
                                                                    ( SELECT
                                                                        saArticulo.co_art CO_ART, SUM(saStockAlmacen.stock) AS stock, saStockAlmacen.tipo AS tipo
                                                                      FROM
                                                                        saArticulo
                                                                        LEFT JOIN saStockAlmacen ON saArticulo.co_art = saStockAlmacen.co_art
                                                                      WHERE
                                                                        saArticulo.co_art = a.co_art
                                                                      GROUP BY
                                                                        saArticulo.co_art, saStockAlmacen.tipo
                                                                    ) pstockact PIVOT ( SUM(stock) FOR tipo IN ( [ACT], [LLE], [COM], [DES], [SACT], [SLLE], [SCOM], [SDES] ) ) 
                                                                    AS PVTSTOCK
                                                                    LEFT JOIN saArtUnidad ArtUnidadP ON ArtUnidadP.co_art = PVTSTOCK.CO_ART
                                                                                                        AND ArtUnidadP.uni_principal = 1
                                                                    LEFT JOIN saUnidad UnidadP ON ArtUnidadP.co_uni = UnidadP.co_uni
                                                                    LEFT JOIN saArtUnidad ArtUnidadS ON ArtUnidadS.co_art = PVTSTOCK.CO_ART
                                                                                                        AND ArtUnidadS.uni_secundaria = 1
                                                                    LEFT JOIN saUnidad UnidadS ON ArtUnidadS.co_uni = UnidadS.co_uni) as stock_actual
                                                        FROM saPedidoVentaReng r
                                                        LEFT JOIN saArticulo a
                                                        ON r.co_art = a.co_art WHERE r.doc_num = '$valor' ");

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
    static public function mdlInsertarEncabezadoProfit($datos){

        try {


            $sql = "pInsertarPedidoVenta @sdFec_Emis='$datos[sdFec_Emis]',@sDoc_Num='$datos[sDoc_Num]',@sDescrip='$datos[sDescrip]',@sCo_Cli='$datos[sCo_Cli]',@sCo_Tran='$datos[sCo_Tran]',@sCo_Cond='$datos[sCo_Cond]',@sCo_Ven='$datos[sCo_Ven]',
            @sCo_Cta_Ingr_Egr=$datos[sCo_Cta_Ingr_Egr],@sCo_Mone='$datos[sCo_Mone]',@bAnulado=$datos[bAnulado],@sdFec_Reg='$datos[sdFec_Reg]',@sdFec_Venc='$datos[sdFec_Venc]',@sStatus='$datos[sStatus]',@deTasa=$datos[deTasa],@sN_Control=$datos[sN_Control],@sPorc_Desc_Glob=$datos[sPorc_Desc_Glob],@deMonto_Desc_Glob=$datos[deMonto_Desc_Glob],
            @sPorc_Reca=$datos[sPorc_Reca],@deMonto_Reca=$datos[deMonto_Reca],@deSaldo=$datos[deSaldo],@deTotal_Bruto=$datos[deTotal_Bruto],@deMonto_Imp=$datos[deMonto_Imp],@deMonto_Imp3=$datos[deMonto_Imp3],@deOtros1=$datos[deOtros1],@deOtros2=$datos[deOtros2],@deOtros3=$datos[deOtros3],@deMonto_Imp2=$datos[deMonto_Imp2],
            @deTotal_Neto=$datos[deTotal_Neto],@sComentario='$datos[sComentario]',@sDir_Ent=$datos[sDir_Ent],@bContrib=$datos[bContrib],@bImpresa=$datos[bImpresa],@sSalestax=$datos[sSalestax],@sImpfis=$datos[sImpfis],@sImpfisfac=$datos[sImpfisfac],@bVen_Ter=$datos[bVen_Ter],@sDis_Cen=$datos[sDis_Cen],@sCampo1=$datos[sCampo1],
            @sCampo2=$datos[sCampo2],@sCampo3=$datos[sCampo3],@sCampo4=$datos[sCampo4],@sCampo5=$datos[sCampo5],@sCampo6=$datos[sCampo6], @sCampo7=$datos[sCampo7],@sCampo8='$datos[sCampo8]',@sRevisado=$datos[sRevisado],@sTrasnfe=$datos[sTrasnfe],@sco_sucu_in=$datos[sco_sucu_in],@sco_us_in='$datos[sco_us_in]',@sMaquina='$datos[sMaquina]'";

            $stmt = Conexion::conectar()->query($sql);

            return "ok";

            $stmt->close();

            $stmt = null;


        }catch (PDOException $pe) {

            die("Error occurred:" . $pe->getMessage());

        }


    }

    static public function mdlInsertarRenglonPedido($datos){

        try {

            $sql = "pInsertarRenglonesPedidoVenta @sDoc_Num='$datos[sDoc_Num]',@sCo_Art='$datos[sCo_Art]',@sDes_Art=$datos[sDes_Art],@sCo_Uni='$datos[sCo_Uni]',@sSco_Uni=$datos[sSco_Uni],@sCo_Alma='$datos[sCo_Alma]',
            @sCo_Precio='$datos[sCo_Precio]',@sTipo_Imp='$datos[sTipo_Imp]',@sTipo_Imp2=$datos[sTipo_Imp2],@sTipo_Imp3=$datos[sTipo_Imp3],
			@deTotal_Art=$datos[deTotal_Art],@deStotal_Art=$datos[deStotal_Art],@dePrec_Vta=$datos[dePrec_Vta],@sPorc_Desc=$datos[sPorc_Desc],@deMonto_Desc=$datos[deMonto_Desc],@dePorc_Imp=$datos[dePorc_Imp],@dePorc_Imp2=$datos[dePorc_Imp2],
			@dePorc_Imp3=$datos[dePorc_Imp3],@deReng_Neto=$datos[deReng_Neto], @dePendiente=$datos[dePendiente],@dePendiente2=$datos[dePendiente2],@sTipo_Doc=$datos[sTipo_Doc], @gRowguid_Doc=$datos[gRowguid_Doc],@sNum_Doc=$datos[sNum_Doc],
			@deMonto_Imp=$datos[deMonto_Imp],@deTotal_Dev=$datos[deTotal_Dev],@deMonto_Dev=$datos[deMonto_Dev],@deOtros=$datos[deOtros],@deMonto_Imp2=$datos[deMonto_Imp2],@deMonto_Imp3=$datos[deMonto_Imp3],@sComentario=$datos[sComentario],
			@sDis_Cen=$datos[sDis_Cen],@deMonto_Desc_Glob=$datos[deMonto_Desc_Glob], @deMonto_Reca_Glob=$datos[deMonto_Reca_Glob], @deOtros1_Glob=$datos[deOtros1_Glob],@deOtros2_glob=$datos[deOtros2_glob],@deOtros3_glob=$datos[deOtros3_glob],
			@deMonto_imp_afec_glob=$datos[deMonto_imp_afec_glob],@deMonto_imp2_afec_glob=$datos[deMonto_imp2_afec_glob],@deMonto_imp3_afec_glob=$datos[deMonto_imp3_afec_glob],@iRENG_NUM=$datos[iRENG_NUM],@sREVISADO=$datos[sREVISADO],
			@sTRASNFE=$datos[sTRASNFE],@sco_sucu_in=$datos[sco_sucu_in], @sco_us_in='$datos[sco_us_in]',@sMaquina='$datos[sMaquina]'";

            $stmt = Conexion::conectar()->query($sql);

            return "ok";

            $stmt->close();

            $stmt = null;


        }catch (PDOException $pe) {

            die("Error occurred:" . $pe->getMessage());

        }

        return $stmt -> fetch();

        $stmt -> close();

        $stmt = null;
    }


    static public function mdlFindOrder($fact_num){

        $stmt = Conexion::conectar()->query("select  
                                                        (case when status=0 THEN 'Sin procesar' ELSE 
                                                        CASE WHEN status=1 THEN 'Parc procesado' ELSE
                                                        CASE WHEN status=2 THEN 'Procesado' END END END) AS estatus 
                                                            from saPedidoVenta  
                                                            where doc_num = '".$fact_num."' ");

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlFindOrderIdApp($idPedido){

        $stmt = Conexion::conectar()->prepare("SELECT  * FROM ordenes  where id_pedido = :id_pedido");

        $stmt -> bindParam(":id_pedido", $idPedido, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlProbarStored(){

        try {
            $dato = array(
                "valor" =>'0101005'
            );

            $sql = "EXEC pSeleccionarArticulo @sco_art='$dato[valor]'";

            $stmt = Conexion::conectar()->query($sql);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);


        }catch (PDOException $pe) {

            die("Error occurred:" . $pe->getMessage());

        }

        return $stmt -> fetch();

        $stmt -> close();

        $stmt = null;

    }

    static public function mdlConsecutivoProximo(){

        try {

            $sql ="EXEC pObtenerProximoNumero @sTabla=N'saPedidoVenta', @sCampo=N'doc_num'";

            $stmt = Conexion::conectar()->query($sql);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);


        }catch (PDOException $pe) {

            die("Error occurred:" . $pe->getMessage());

        }

        return $stmt -> fetch();

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlRegisterFile($table, $data){

        try {
            $columns = "";
            $params="";
            foreach ($data as $key => $value){

                $columns .=$key.",";
                $params .=":".$key.",";
            }
            $columns = substr($columns, 0, -1);
            $params = substr($params, 0, -1);

            $link = Conexion::conectar();
            $sql = "INSERT INTO $table ($columns) VALUES ($params )";

            $stmt = $link->prepare($sql);

            foreach ($data as $key => $value){

                $stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);
            }

            if($stmt->execute()){

                $response = array(
                    "statusCode"=>200,
                    "result"=>$link->lastInsertId(),
                    "mensaje" => "El proceso fue exitoso"
                );
            }else{

                $response = array(
                    "statusCode"=>404,
                    "result"=>$link->errorInfo(),
                    "mensaje" => "Fallo el proceso"
                );
            }
            return $response;

        } catch (PDOException $th) {
            return $response = array(
                "statusCode"=>500,
                "result"=>$th,
                "mensaje" => "Fallo el proceso"
            );
        }

    }

    static public function mdlShowOrdenes(){

        $stmt = Conexion::conectar()->prepare("SELECT o.*, c.tipo_precio from ordenes o left join clientes c on c.co_cli = o.co_cli where o.estatus_sin = 0 order by o.id desc ");

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    /*=============================================
        ACTUALIZAR DATOS DE LAS ORDENES
    =============================================*/

    static public function mdlUpdateOrden($data){

        try{

            $stmt = Conexion::conectar()->prepare("UPDATE ordenes SET doc_num = :doc_num, estatus_sin = :estatus_sin, comentario = :comentario, renglones = :renglones WHERE id_pedido = :id_pedido");

            $stmt -> bindParam(":doc_num", $data["doc_num"], PDO::PARAM_STR);
            $stmt -> bindParam(":id_pedido", $data["id_pedido"], PDO::PARAM_STR);
            $stmt -> bindParam(":estatus_sin", $data["estatus_sin"], PDO::PARAM_STR);
            $stmt -> bindParam(":comentario", $data["comentario"], PDO::PARAM_STR);
            $stmt -> bindParam(":renglones", $data["renglones"], PDO::PARAM_STR);

            if($stmt -> execute()){

                return "ok";

            }else{

                return "error";

            }

            $stmt -> close();

            $stmt = null;
        }catch (PDOException $pe){
            return "Error occurred:" . $pe->getMessage();
        }


    }


}