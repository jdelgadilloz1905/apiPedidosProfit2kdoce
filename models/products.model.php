<?php
require_once "conexion.php";

class ModelProducts{

    /*=============================================
    TODOS LOS PRODUCTOS
    =============================================*/

    static public function mdlShowProducts($item,$valor){


        if($item != null){
            $stmt = Conexion::conectar()->prepare("SELECT * from productos WHERE $item = :$item");


            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch(PDO::FETCH_ASSOC);
        }else{

            $stmt = Conexion::conectar()->query("SELECT * FROM productos order by co_art desc ");

            $stmt -> execute();

            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }


        $stmt -> close();

        $stmt = null;
    }

    /*=============================================
    PRECIOS DE LOS PRODUCTOS
    =============================================*/

    static public function mdlConsultarPreciosArticulo($item,$valor){

        if($item != null){

            $stmt = Conexion::conectar()->query("SELECT * FROM precios_productos WHERE inactivo = 0 AND $item = '$valor'");
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }else{
            $stmt = Conexion::conectar()->query("SELECT * FROM precios_productos where inactivo = 0 ORDER BY co_art, co_precio ASC ");
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }



        $stmt -> close();

        $stmt = null;
    }

    static public function mdlConsultarPrecioXTipoCliente($valor,$valor1){

        try{

            $stmt = Conexion::conectar()->query("SELECT * FROM precios_productos
                                                WHERE co_art = '$valor'
                                                AND co_precio = '$valor1'
                                                AND inactivo = 0 ");

            $stmt -> execute();

            return $stmt -> fetch(PDO::FETCH_ASSOC);

        }catch (PDOException $pe) {

            return "Error occurred:" . $pe->getMessage();

        }


    }
    /*=============================================
    STOCK DE LOS PRODUCTOS
    =============================================*/
    static public function mdlConsultarStockArticulo($item,$valor){

        if($item != null){

            $stmt = Conexion::conectar()->prepare("select
                                                            LTRIM(RTRIM(pvtstock.co_art)) co_art, '' as co_alma, 'todos' as desc_alma, isnull(artunidadp.co_uni, '') as unidad,
                                                            isnull(unidadp.des_uni, '') as descripcion, isnull([act], 0) as stock_act, isnull([com], 0) as stock_com,
                                                            isnull([lle], 0) as stock_lle, isnull([des], 0) as stock_des, isnull(artunidads.co_uni, '') as unidads,
                                                            isnull(unidads.des_uni, '') as descripcions, isnull([sact], 0) as sstock_act,
                                                            isnull([scom], 0) as sstock_com, isnull([slle], 0) as sstock_lle, isnull([sdes], 0) as sstock_des
                                                        from
                                                            ( select
                                                                saarticulo.co_art co_art, sum(sastockalmacen.stock) as stock, sastockalmacen.tipo as tipo
                                                              from
                                                                saarticulo
                                                                left join sastockalmacen on saarticulo.co_art = sastockalmacen.co_art
                                                              where
                                                                saarticulo.$item = :$item
                                                              group by
                                                                saarticulo.co_art, sastockalmacen.tipo
                                                            ) pstockact pivot ( sum(stock) for tipo in ( [act], [lle], [com], [des], [sact], [slle], [scom], [sdes] ) ) 
                                                            as pvtstock
                                                            left join saartunidad artunidadp on artunidadp.co_art = pvtstock.co_art
                                                                                                and artunidadp.uni_principal = 1
                                                            left join saunidad unidadp on artunidadp.co_uni = unidadp.co_uni
                                                            left join saartunidad artunidads on artunidads.co_art = pvtstock.co_art
                                                                                                and artunidads.uni_secundaria = 1
                                                            left join saunidad unidads on artunidads.co_uni = unidads.co_uni");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);


            $stmt -> execute();

            return $stmt -> fetch(PDO::FETCH_ASSOC);

        }else{

            $stmt = Conexion::conectar()->query("select
                                                            LTRIM(RTRIM(pvtstock.co_art)) co_art, '' as co_alma, 'todos' as desc_alma, isnull(artunidadp.co_uni, '') as unidad,
                                                            isnull(unidadp.des_uni, '') as descripcion, isnull([act], 0) as stock_act, isnull([com], 0) as stock_com,
                                                            isnull([lle], 0) as stock_lle, isnull([des], 0) as stock_des, isnull(artunidads.co_uni, '') as unidads,
                                                            isnull(unidads.des_uni, '') as descripcions, isnull([sact], 0) as sstock_act,
                                                            isnull([scom], 0) as sstock_com, isnull([slle], 0) as sstock_lle, isnull([sdes], 0) as sstock_des
                                                        from
                                                            ( select
                                                                saarticulo.co_art co_art, sum(sastockalmacen.stock) as stock, sastockalmacen.tipo as tipo
                                                              from
                                                                saarticulo
                                                                left join sastockalmacen on saarticulo.co_art = sastockalmacen.co_art                                                                
                                                              group by
                                                                saarticulo.co_art, sastockalmacen.tipo
                                                            ) pstockact pivot ( sum(stock) for tipo in ( [act], [lle], [com], [des], [sact], [slle], [scom], [sdes] ) ) 
                                                            as pvtstock
                                                            left join saartunidad artunidadp on artunidadp.co_art = pvtstock.co_art
                                                                                                and artunidadp.uni_principal = 1
                                                            left join saunidad unidadp on artunidadp.co_uni = unidadp.co_uni
                                                            left join saartunidad artunidads on artunidads.co_art = pvtstock.co_art
                                                                                                and artunidads.uni_secundaria = 1
                                                            left join saunidad unidads on artunidads.co_uni = unidads.co_uni");


            $stmt -> execute();

            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }



        $stmt -> close();

        $stmt = null;

    }

    /*=============================================
    UNIDADES DE LOS PRODUCTOS
    =============================================*/

    static public function mdlConsultarUnidadArticulo($item,$valor){

        if($item != null){

            $stmt = Conexion::conectar()->prepare("SELECT Au.co_art,Au.co_uni,Au.relacion,Au.equivalencia,Au.uso_venta, Au.uso_compra,
	                                                                Au.uni_principal,Au.uso_principal,Au.uni_secundaria,Au.uso_secundaria, u.des_uni AS des_Uni
                                                                FROM  saArtUnidad AS Au
                                                                    INNER JOIN saUnidad u ON Au.co_uni = u.co_uni
                                                                    WHERE Au.$item = :$item and Au.uni_principal=1
                                                                ORDER BY co_art DESC, uni_principal DESC, uso_principal DESC, uni_secundaria DESC, uso_secundaria DESC");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);


            $stmt -> execute();

            return $stmt -> fetch(PDO::FETCH_ASSOC);

        }else{

            $stmt = Conexion::conectar()->query("SELECT Au.co_art,Au.co_uni,Au.relacion,Au.equivalencia,Au.uso_venta, Au.uso_compra,
	                                                                Au.uni_principal,Au.uso_principal,Au.uni_secundaria,Au.uso_secundaria, u.des_uni AS des_Uni
                                                                FROM  saArtUnidad AS Au
                                                                    INNER JOIN saUnidad u ON Au.co_uni = u.co_uni
                                                                ORDER BY co_art DESC, uni_principal DESC, uso_principal DESC, uni_secundaria DESC, uso_secundaria DESC");

            $stmt -> execute();

            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }


        $stmt -> close();

        $stmt = null;
    }



    static public function mdlGetProductsFavorites($tabla,$data){

        $stmt = Conexion::conectar()->prepare("SELECT  * FROM $tabla  where co_user = :co_user and co_art = :co_art  ");

        $stmt -> bindParam(":co_user", $data["co_user"], PDO::PARAM_STR);

        $stmt -> bindParam(":co_art", $data["co_art"], PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlDeleteProductsFavorites($tabla,$data){

        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE co_user = :co_user and co_art = :co_art");

        $stmt -> bindParam(":co_user", $data["co_user"], PDO::PARAM_STR);

        $stmt -> bindParam(":co_art", $data["co_art"], PDO::PARAM_STR);

        if($stmt -> execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlAddFavorites($tabla,$data){

        try{
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (co_user,co_art)
                                                VALUES (:co_user,:co_art)");

            $stmt -> bindParam(":co_user", $data["co_user"], PDO::PARAM_STR);

            $stmt -> bindParam(":co_art", $data["co_art"], PDO::PARAM_STR);

            if($stmt->execute()){

                return "ok";

            }else{

                return "error";

            }

            $stmt->close();
            $stmt = null;
        }catch (PDOException $pe) {

            die("Error occurred:" . $pe->getMessage());

        }


    }

    static public function mdlGetFavoriteApi($co_user){

        $stmt = Conexion::conectar()->prepare("SELECT  f.co_user,p.co_art,p.art_des, p.des_color, p.cat_des, p.lin_des, p.des_ubicacion, p.des_proc, p.stock_actual
                                                            FROM favoritos f 
                                                                LEFT JOIN productos p  
                                                                    ON p.co_art = f.co_art 
                                                            WHERE p.anulado = 0 and f.co_user = :co_user order by p.co_art desc");

//        $stmt = Conexion::conectar()->prepare("SELECT  f.co_user, LTRIM(RTRIM(a.co_art)) co_art,a.art_des, c.des_color, ca.cat_des, l.lin_des, u.des_ubicacion, p.des_proc,
//                                                              (select isnull([act], 0) as stock_act
//                                                                    from
//                                                                    ( select
//                                                                        saarticulo.co_art co_art, sum(sastockalmacen.stock) as stock, sastockalmacen.tipo as tipo
//                                                                        from
//                                                                        saarticulo
//                                                                        left join sastockalmacen on saarticulo.co_art = sastockalmacen.co_art
//                                                                        where
//                                                                          saarticulo.co_art = a.co_art
//                                                                        group by
//                                                                        saarticulo.co_art, sastockalmacen.tipo
//                                                                    ) pstockact pivot ( sum(stock) for tipo in ( [act], [lle], [com], [des], [sact], [slle], [scom], [sdes] ) )
//                                                                    as pvtstock
//                                                                    left join saartunidad artunidadp on artunidadp.co_art = pvtstock.co_art
//                                                                                                        and artunidadp.uni_principal = 1
//                                                                    left join saunidad unidadp on artunidadp.co_uni = unidadp.co_uni
//                                                                    left join saartunidad artunidads on artunidads.co_art = pvtstock.co_art
//                                                                                                        and artunidads.uni_secundaria = 1
//                                                                    left join saunidad unidads on artunidads.co_uni = unidads.co_uni) stock_actual
//                                                            FROM saFavoritos f
//                                                                LEFT JOIN saArticulo a
//                                                                    ON a.co_art = f.co_art
//                                                                LEFT JOIN saColor c
//                                                                    ON a.co_color = c.co_color
//                                                                LEFT JOIN saCatArticulo ca
//                                                                    ON a.co_cat = ca.co_cat
//                                                                LEFT JOIN saLineaArticulo l
//                                                                    ON a.co_lin = l.co_lin
//                                                                LEFT JOIN saUbicacion u
//                                                                    ON a.co_ubicacion = u.co_ubicacion
//                                                                LEFT JOIN saProcedencia p
//                                                                    ON a.cod_proc = p.cod_proc
//                                                            WHERE a.anulado = 0 AND a.tipo = 'V' and f.co_user = :co_user order by a.co_art desc");

        $stmt -> bindParam(":co_user", $co_user, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlGetProductsLike($tabla,$likess){

        $stmt = Conexion::conectar()->query("SELECT  a.co_art,a.art_des, a.stock_act, LTRIM(RTRIM(a.uni_venta)) uni_venta, a.uni_relac,
                                                           a.prec_vta1, a.prec_vta2,a.prec_vta3,a.prec_vta4,a.prec_vta5,
                                                            a.picture,
                                                                co.des_col, l.lin_des,
                                                                case when c.cat_des IS NULL then 'No tiene' else c.cat_des end as cat_des
                                                        FROM $tabla a  
                                                        LEFT JOIN cat_art c
                                                          on a.co_art = c.co_cat
                                                        left join colores co
                                                          on a.co_color = co.co_col
                                                        left join lin_art l
                                                          on a.co_lin = l.co_lin
                                                          where (a.co_art like '%$likess%' or a.art_des like '%$likess%') 
                                                          and  a.anulado = 0 and a.prec_vta1 >0 and a.stock_act >0 and a.tipo = 'V' order by a.co_art desc  ");


        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlActualizarStock($datos){

        try {

            $sql = "exec pStockActualizar @sCo_Alma='$datos[sCo_Alma]',@sCo_Art='$datos[sCo_Art]',@sCo_Uni='$datos[sCo_Uni]',@deCantidad=$datos[deCantidad],@sTipoStock='$datos[sTipoStock]',
                    @bSumarStock=$datos[bSumarStock],@bPermiteStockNegativo=$datos[bPermiteStockNegativo]";

            $stmt = Conexion::conectar()->query($sql);

            return "ok";

            $stmt->close();

            $stmt = null;


        }catch (PDOException $pe) {

            die("Error occurred:" . $pe->getMessage());

        }


        if($stmt -> execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlUpdateArt($tabla,$datos){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET stock_com = stock_com + :stock_com WHERE  co_art = :co_art");

        $stmt -> bindParam(":stock_com", $datos["total_art"], PDO::PARAM_STR);
        $stmt -> bindParam(":co_art", $datos["co_art"], PDO::PARAM_STR);


        if($stmt -> execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }

    static public function mdlTasa(){

        $stmt = Conexion::conectar()->query("select top 1 * from tasas where co_mone = 'US$' order by fecha desc  ");

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlFindUnidadMultiple($co_art){

        //$stmt = Conexion::conectar()->prepare("select case when uni_emp != '' THEN uni_emp ELSE uni_venta END AS unidad from art where co_art = :co_art");
        $stmt = Conexion::conectar()->prepare("select uni_venta AS unidad from art where co_art = :co_art");

        $stmt -> bindParam(":co_art", $co_art, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }


    /*CONSULTAS DEL SERVIDOR DE PROFIT  */

    /*=============================================
    REGISTRAR DATOS
    =============================================*/
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
                    "status"=>200,
                    "result"=>$link->lastInsertId(),
                    "comment" => "El proceso fue exitoso"
                );
            }else{

                $response = array(
                    "status"=>404,
                    "result"=>$link->errorInfo(),
                    "comment" => "Fallo el proceso"
                );
            }
            return $response;

        } catch (\Throwable $th) {
            return $response = array(
                "status"=>500,
                "result"=>$th,
                "comment" => "Fallo el proceso"
            );
        }

    }

    static public function mdlUpdateProducto($table,$data){

        try {
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET art_des = :art_des, des_color = :des_color, cat_des = :cat_des, lin_des = :lin_des,
                                                                     des_ubicacion = :des_ubicacion, des_proc = :des_proc, stock_actual = :stock_actual 
                                                                     WHERE co_art = :co_art");

            $stmt -> bindParam(":co_art", $data["co_art"], PDO::PARAM_STR);
            $stmt -> bindParam(":art_des", $data["art_des"], PDO::PARAM_STR);
            $stmt -> bindParam(":des_color", $data["des_color"], PDO::PARAM_STR);
            $stmt -> bindParam(":cat_des", $data["cat_des"], PDO::PARAM_STR);
            $stmt -> bindParam(":lin_des", $data["lin_des"], PDO::PARAM_STR);
            $stmt -> bindParam(":des_ubicacion", $data["des_ubicacion"], PDO::PARAM_STR);
            $stmt -> bindParam(":des_proc", $data["des_proc"], PDO::PARAM_STR);
            $stmt -> bindParam(":stock_actual", $data["stock_actual"], PDO::PARAM_STR);

            if($stmt -> execute()){

                $response = array(
                    "status"=>200,
                    "result"=>"ok",
                    "comment" => "El proceso fue exitoso"
                );

            }else{

                $response = array(
                    "status"=>404,
                    "result"=>$link->errorInfo(),
                    "comment" => "Fallo el proceso"
                );

            }

            //$stmt -> close();

            $stmt = null;

            return $response;

        } catch (Exception $e) {

            return $response = array(
                "status"=>500,
                "result"=>$e->getMessage(),
                "comment" => "Fallo el proceso"
            );
        }
    }

    static public function mdlUpdatePrecioProducto($table,$data){

        try {
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET monto = :monto, desde = :desde, co_alma_calculado = :co_alma_calculado
                                                                     WHERE co_art = :co_art and co_precio = :co_precio and desde = :desde");

            $stmt -> bindParam(":co_art", $data["co_art"], PDO::PARAM_STR);
            $stmt -> bindParam(":co_precio", $data["co_precio"], PDO::PARAM_STR);
            $stmt -> bindParam(":co_alma_calculado", $data["co_alma_calculado"], PDO::PARAM_STR);
            $stmt -> bindParam(":monto", $data["monto"], PDO::PARAM_STR);
            $stmt -> bindParam(":desde", $data["desde"], PDO::PARAM_STR);


            if($stmt -> execute()){

                $response = array(
                    "status"=>200,
                    "result"=>"ok",
                    "comment" => "El proceso fue exitoso"
                );

            }else{

                $response = array(
                    "status"=>404,
                    "result"=>$link->errorInfo(),
                    "comment" => "Fallo el proceso"
                );

            }

            //$stmt -> close();

            $stmt = null;

            return $response;

        } catch (Exception $e) {

            return $response = array(
                "status"=>500,
                "result"=>$e->getMessage(),
                "comment" => "Fallo el proceso"
            );
        }
    }

    static public function mdlShowProductsApp($data)
    {
        $stmt = Conexion::conectar()->prepare(" SELECT * from productos where co_art = :co_art");

        $stmt -> bindParam(":co_art", $data["co_art"], PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlShowPrecioProductoApp($data){

        $stmt = Conexion::conectar()->prepare(" SELECT * from precios_productos where co_art = :co_art and co_precio = :co_precio and desde = :desde");

        $stmt -> bindParam(":co_art", $data["co_art"], PDO::PARAM_STR);
        $stmt -> bindParam(":co_precio", $data["co_precio"], PDO::PARAM_STR);
        $stmt -> bindParam(":desde", $data["desde"], PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }
}