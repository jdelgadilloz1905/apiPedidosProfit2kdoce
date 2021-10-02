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

        $stmt = Conexion::conectar()->prepare("SELECT  top 10 c.cli_des, f.fact_num,CONVERT(varchar,f.fec_emis, 101) as fec_emis, f.saldo  from $tabla f 
                                                            left join clientes c 
                                                            on c.co_cli = f.co_cli where f.saldo>0 and f.co_cli =:co_cli order by f.co_cli desc ");

        $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);


        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }
}