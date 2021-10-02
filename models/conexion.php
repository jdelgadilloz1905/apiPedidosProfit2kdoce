<?php

class Conexion{

	static public function conectar(){

        try {
            $link = new PDO("sqlsrv:server=localhost;database=DEMOA", "profit","profit" );
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            $link= "Ocurrió un error con la base de datos: " . $e->getMessage();
        }

		return $link;

	}
    static public function conectarMasterProfit(){

        try {
            $link = new PDO("sqlsrv:server=localhost;database=MasterProfit", "profit","profit" );
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            $link= "Ocurrió un error con la base de datos: " . $e->getMessage();
        }

        return $link;

    }


}