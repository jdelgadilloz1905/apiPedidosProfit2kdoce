<?php
require_once "conexion.php";

class ModelsConfig{

    static public function mdlConfig(){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM config");

        $stmt -> execute();

        return $stmt -> fetch();
    }


    static public function mdlCorrelativo($tabla,$item){

        $stmt = Conexion::conectar()->query("select $item  from $tabla ");

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlCorrelativoUsuario($tabla,$item){

        $stmt = Conexion::conectarMasterProfit()->query("select top 1 ($item +1) as codigo from $tabla order by id desc ");

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlUpdateCorrelativo($tabla,$item,$correlativo){

        $stmt = Conexion::conectar()->query("UPDATE $tabla SET $item = $correlativo +1 ");

        if($stmt -> execute()){

            return "ok";

        }else{

            return "error";
        }

        $stmt-> close();

        $stmt = null;
    }
}