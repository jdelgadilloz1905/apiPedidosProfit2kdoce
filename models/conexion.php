<?php

class Conexion{

	static public function conectar(){

        try {
            $link = new PDO("mysql:host=localhost:33065;dbname=app_movil",
                             "root","", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            
        } catch (Exception $e) {
            $link= "Ocurrió un error con la base de datos: " . $e->getMessage();
        }
		return $link;
	}
}
