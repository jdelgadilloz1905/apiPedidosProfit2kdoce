<?php
require_once "conexion.php";

class ModelClients{

    static public function mdlShowClients($tabla){

        $stmt = Conexion::conectar()->query(" SELECT  c.co_cli, c.cli_des,c.direc1, c.telefonos, c.rif, c.tip_cli, 
                                                            (select top 1 co_precio from saTipoCliente where tip_cli = c.tip_cli ) tipo_precio
                                                            from $tabla c 
                                                                where c.inactivo =0 
                                                                order by c.co_cli desc");

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlGetClientsLike($tabla,$likess){

        $stmt = Conexion::conectar()->query("SELECT  c.co_cli, c.cli_des,c.direc1, c.telefonos, c.rif,c.tip_cli, 
                                                          (select top 1 co_precio from saTipoCliente where tip_cli = c.tip_cli ) tipo_precio
                                                          from $tabla c where (c.co_cli like '%$likess%' or c.cli_des like '%$likess%' or c.direc1 like '%$likess%' or c.rif like '%$likess%') and c.inactivo =0 
                                                            order by c.co_cli desc ");

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlGetCuentaXCobrar($tabla,$data){

        $stmt = Conexion::conectar()->prepare("SELECT  top 10 c.cli_des, f.doc_num,CONVERT(VARCHAR,f.fec_emis, 101) AS fec_emis, f.saldo  
                                                            FROM saFacturaVenta f 
                                                            LEFT JOIN saCliente c 
                                                            ON c.co_cli = f.co_cli WHERE f.saldo>0 AND f.co_cli = :co_cli ORDER BY f.co_cli DESC ");

        $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);


        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlObtenerNotasEntregaXCliente($data){

        $stmt = Conexion::conectar()->prepare("SELECT top 10 c.cli_des, v.doc_num, CONVERT(VARCHAR,v.fec_emis, 101) AS fec_emis,v.total_neto saldo
                                                            FROM saNotaEntregaVenta v
                                                                INNER JOIN saCliente c ON v.co_cli = c.co_cli
                                                                WHERE v.anulado = 0 AND v.co_cli = :co_cli 
                                                                ORDER BY v.doc_num desc ");

        $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);


        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlObtenerDocumentos($data){

        try {

            $sql ="EXEC pObtenerDocumentosVenta @sCliente=N'$data[co_cli]'";

            $stmt = Conexion::conectar()->query($sql);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);


        }catch (PDOException $pe) {

            die("Error occurred:" . $pe->getMessage());

        }

        return $stmt -> fetchAll();

        $stmt -> close();

        $stmt = null;
    }
}