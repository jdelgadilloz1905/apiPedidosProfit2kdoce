<?php

require_once "conexion.php";

class ModelUsers{

    /*=============================================
        MOSTRAR USUARIOS
        =============================================*/

    static public function mdlShowUsers($tabla, $item, $valor, $item2){

        if($item != null){

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item or $item2 = :$item2");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

            $stmt -> bindParam(":".$item2, $valor, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();

        }else{

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id");

            $stmt -> execute();

            return $stmt -> fetchAll();

        }


        $stmt -> close();

        $stmt = null;

    }

    /*=============================================
	ACTUALIZAR USUARIO
	=============================================*/

    static public function mdlUpdateUser($tabla, $item1, $valor1, $item2, $valor2){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

        $stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
        $stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);


        if($stmt -> execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }

    /*=============================================
	ACTUALIZAR NOMBRE Y APELLIDO DE USUARIO
	=============================================*/

    static public function mdlUpdateName($tabla, $data){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $data[item] = :$data[item] WHERE id = :id");

        $stmt -> bindParam(":id", $data["id"], PDO::PARAM_STR);
        $stmt -> bindParam(":".$data["item"], $data["valor"], PDO::PARAM_STR);

        if($stmt -> execute()){


            return "ok" ;


        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }
    /*=============================================
      bUSCO REGISTROS EN LA TABLA USUARIOS MASTER PROFIT
      =============================================*/
    static public function mdlShowUser($tabla, $item, $valor){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

        $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;

    }

    /*=============================================
  MOSTRAR LOS VENDEEDORES DE LA BASE DE DATOS
  =============================================*/
    static public function mdlShowUserProfit($tabla, $item, $valor){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

        $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;

    }

    /*=============================================
	REGISTRAR USUARIOS
	=============================================*/

    static public function mdlUserRegister($tabla, $datos)
    {

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id, email, usuario, nombre, password, foto,estado, modo, co_ven) 
                                                                                VALUES (:id, :email, :usuario, :nombre, :password, :foto, :estado, :modo, :co_ven)");


        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_STR);

        $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);

        $stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);

        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);

        $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);

        $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);

        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);

        $stmt->bindParam(":modo", $datos["modo"], PDO::PARAM_STR);

        $stmt->bindParam(":co_ven", $datos["co_ven"], PDO::PARAM_STR);



        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt->close();

        $stmt = null;
    }

    /*============================================
		ACTUALIZAR PASSWORD
	==============================================*/

    static public function mdlUpdatePassword($tabla, $datos){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET password = :password WHERE id = :id");

        $stmt -> bindParam(":id", $datos["id"], PDO::PARAM_STR);
        $stmt -> bindParam(":password", $datos["password"], PDO::PARAM_STR);

        if($stmt -> execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt-> close();

        $stmt = null;

    }

    static public function mdlUpdateDataUser($tabla,$item ,$valor,$item2,$valor2){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item = :$item WHERE $item2 = :$item2");

        $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
        $stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

        if($stmt -> execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt-> close();

        $stmt = null;
    }

    static public function mdlShowVendedor($tabla){

        try {
            //SELECT co_ven as value,ven_des as label FROM $tabla where co_ven not in(select MasterProfit..usuarios.co_ven from  MasterProfit..usuarios) order by co_ven
            $stmt = Conexion::conectar()->prepare("SELECT co_ven as value,ven_des as label FROM $tabla where co_ven not in(select co_ven from  usuarios)");

            $stmt -> execute();

            return $response = array(
                "status"=>200,
                "result"=>$stmt -> fetchAll(PDO::FETCH_ASSOC),
                "comment" => ""
            );

            $stmt -> close();

            $stmt = null;
                
        } catch (\Throwable $th) {

            return $response = array(
                "status"=>500,
                "result"=>$th,
                "comment" => "Fallo el proceso"
        );
        }

        
    }

    /*=============================================
    MOSTRAR REGISTROS 
    =============================================*/

    static public function mdlShowRegister($tabla, $item, $valor){

        if($item != null){

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);


            $stmt -> execute();

            return $stmt -> fetch();

        }else{

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id DESC ");

            $stmt -> execute();

            return $stmt -> fetchAll();

        }


        $stmt -> close();

        $stmt = null;

    }

    /*=============================================
    REGISTRAR DATOS  
    =============================================*/
    static public function mdlRegisterVendedor($table, $data){

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

    static public function mdlUpdateVendedor($table,$data){

        try {
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET ven_des = :ven_des WHERE co_ven = :co_ven");

            $stmt -> bindParam(":co_ven", $data["co_ven"], PDO::PARAM_STR);
            $stmt -> bindParam(":ven_des", $data["ven_des"], PDO::PARAM_STR);


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

        } catch (\Throwable $th) {
            return $response = array(
                "status"=>500,
                "result"=>$th,
                "comment" => "Fallo el proceso"
        );
        }
    }


}