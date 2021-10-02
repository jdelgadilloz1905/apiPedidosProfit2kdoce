<?php
//phpinfo();
//header("Access-Control-Allow-Origin: *");
//header('Access-Control-Allow-Credentials: true');
//header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
//header("Access-Control-Allow-Headers: X-Requested-With");
//header('Content-Type: text/html; charset=utf-8');
//header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
//
//
//$json = file_get_contents('php://input');
//$obj = json_decode($json,true);
//
////echo json_encode(array(
////    "statusCode" => 200,
////    "message" =>"Acceso denegado.",
////    "jwt"=> array(
////        "id" =>"2145",
////        "usuario" =>"jdelgadillo",
////        "nombre" =>"jorge",
////        "apellido"=>"Delgadillo",
////        "email" => "jdelgadilloz1905@gmail.com",
////        "jwt"=>"estoesloquehay23534"
////    ),
////    "error"=>"incorrecto"
////
////));
//
try {
    $link = new PDO("sqlsrv:server=localhost;database=demo", "sa","profit" );
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $me="conexion correcta";
} catch (Exception $e) {
    $link= "Ocurrió un error con la base de datos: " . $e->getMessage();
    $me="conexion fallida";
}

var_dump($me) ;


