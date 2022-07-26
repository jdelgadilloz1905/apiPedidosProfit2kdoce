<?php


//phpinfo();

try {
    $link = new PDO("mysql:host=localhost;dbname=michacha_db",
        "omendj","198405", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    $me="conexion correcta";
} catch (Exception $e) {
    $link= "Ocurrió un error con la base de datos: " . $e->getMessage();
    $me="conexion fallida";
}

var_dump($me) ;


