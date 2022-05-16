<?php
class ControllerUsers{
    /*=============================================
    INGRESAR USUARIO
    =============================================*/
    static public function ctrLoginUser($data){

        if(isset($data["identifier"])){
            if($data["modo"] == "directo"){
                if(preg_match('/^[a-zA-Z0-9.,]+$/', $data["password"])){

                    $encrypt = crypt($data["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                    $table = "usuarios";

                    $item = "email";

                    $item2 = "usuario";

                    $value = $data["identifier"];

                    $answer = ModelUsers::mdlShowUsers($table,$item,$value,$item2);

                    if(isset($answer["email"])){

                        if(($answer["email"] == $value or $answer["usuario"] == $value) && $answer["password"] == $encrypt){

                            if($answer["estado"] == 1){

                                $resultado = array(
                                    "id" =>$answer["id"],
                                    "usuario" =>$answer["usuario"],
                                    "nombre" =>$answer["nombre"],
                                    "apellido" =>$answer["apellido"],
                                    "modo" =>"directo",
                                    "email" =>$answer["email"],
                                    "foto" =>$answer["foto"],
                                    "co_ven" =>$answer["co_ven"],
                                    "rol" =>json_decode($answer["rol"])
                                );

                                /*=============================================
                                REGISTRAR FECHA PARA SABER EL ÚLTIMO LOGIN
                                =============================================*/

                                //self::ctrUpdateLastLogin($table,$answer["id"]);

                                echo json_encode(array(
                                    "statusCode" => 200,
                                    "error" => false,
                                    "infoUser" =>$resultado,
                                    "mensaje" =>""
                                ));


                            }else{

                                echo json_encode(array(
                                    "statusCode" => 400,
                                    "error" => true,
                                    "mensaje" =>"El email ó usuario aún no está activado"
                                ));

                            }

                        }else{

                            echo json_encode(array(
                                "statusCode" => 400,
                                "error" => true,
                                "mensaje" =>"Usuario o clave invalida"
                            ));

                        }
                    }else{
                        echo json_encode(array(
                            "statusCode" => 400,
                            "error" => true,
                            "mensaje" =>"Usuario no existe  "
                        ));

                    }


                }
            }else{
                if(preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $data["identifier"])){

                    $table = "usuarios";

                    $item = "email";

                    $item2 = "usuario";

                    $value = $data["identifier"];

                    $answer = ModelUsers::mdlShowUsers($table,$item,$value, $item2);

                    if($answer["email"] == $value){

                        $resultado = array(
                            "id" =>$answer["id"],
                            "nombre" =>$answer["nombre"],
                            "modo" =>$answer["modo"],
                            "email" =>$answer["email"],
                            "foto" =>$answer["foto"],
                            "perfil" =>$answer["perfil"],
                            "rol"=>$answer["rol"],
                            "error" => false,
                            "statusCode" => 200,
                        );

                        echo json_encode(array(
                            "statusCode" => 200,
                            "error" => false,
                            "data" =>$resultado
                        ));

                    }


                }
            }

        }
    }

    /*=============================================
    RECUPERAR LA CLAVE
    =============================================*/

    static public function ctrRecoverPassword($data){

        if(isset($data["identifier"])){

                /*=============================================
                GENERAR CONTRASEÑA ALEATORIA
                =============================================*/

                function generarPassword($longitud){

                    $key = "";
                    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";

                    $max = strlen($pattern)-1;

                    for($i = 0; $i < $longitud; $i++){

                        $key .= $pattern[mt_rand(0,$max)];

                    }

                    return $key;

                }

                $nuevaPassword = generarPassword(11);

                $encriptar = crypt($nuevaPassword, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                $tabla = "usuarios";

                $item1 = "email";
                $item2 = "usuario";

                $valor1 = $data["identifier"];

                $respuesta1 = ModelUsers::mdlShowUsers($tabla,$item1,$valor1, $item2);

                if($respuesta1){

                    $valor = $respuesta1["id"];
                    $item2 = "password";
                    $valor2 = $encriptar;

                    $respuesta2 = ModelUsers::mdlUpdateUser($tabla, $item2, $valor2, "id", $valor );

                    if($respuesta2  == "ok"){

                        /*=============================================
                        CAMBIO DE CONTRASEÑA
                        =============================================*/

                        date_default_timezone_set("America/Bogota");

                        $url = Ruta::ctrRutaEnvioEmail();

                        $mail = new PHPMailer;

                        $mail->CharSet = 'UTF-8';

                        $mail->isMail();

                        $mail->setFrom('hola@prujula.com', 'PRUJULA');

                        $mail->addReplyTo('hola@prujula.com', 'PRUJULA');

                        $mail->Subject = "¿Olvidaste tu contraseña?";

                        $mail->addAddress($data["conEmail"]);

                        $mail->msgHTML('<div style="width:100%; background:#eee; position:relative; font-family:sans-serif; padding-bottom:40px">

								<center>

									<img style="padding:20px; width:10%" src="">

								</center>

								<div style="position:relative; margin:auto; width:600px; background:white; padding:20px">

									<center>

									<img style="padding:20px; width:15%" src="http://tutorialesatualcance.com/tienda/icon-pass.png">

									<h3 style="font-weight:100; color:#999">SOLICITUD DE NUEVA CONTRASEÑA</h3>

									<hr style="border:1px solid #ccc; width:80%">

									<h4 style="font-weight:100; color:#999; padding:0 20px"><strong>Su nueva contraseña: </strong>'.$nuevaPassword.'</h4>

									<a href="'.$url.'" target="_blank" style="text-decoration:none">

									<div style="line-height:60px; background:#450E10; width:60%; color:white">Ingrese nuevamente al sitio</div>

									</a>

									<br>

									<hr style="border:1px solid #ccc; width:80%">

									<h5 style="font-weight:100; color:#999">Si no se inscribió en esta cuenta, puede ignorar este correo electrónico y la cuenta se eliminará.</h5>

									</center>

								</div>

							</div>');

                        $envio = $mail->Send();

                        if(!$envio){

                            echo json_encode(array(
                                "statusCode" => 400,
                                "error" => true,
                                "NuevoPassword"=>$nuevaPassword,
                                "mensaje" =>"¡Ha ocurrido un problema enviando cambio de contraseña a ".$data["conEmail"].$mail->ErrorInfo."!",
                            ));

                        }else{

                            echo json_encode(array(
                                "statusCode" => 200,
                                "NuevoPassword"=>$nuevaPassword,
                                "error" => false,
                                "mensaje" =>"",
                            ));

                        }

                    }

                }else{

                    echo json_encode(array(
                        "statusCode" => 400,
                        "error" => true,
                        "mensaje" =>"¡El correo electrónico no existe en el sistema!",
                    ));
                }



        }


    }
    /*=============================================
    CAMBIAR CLAVE
    =============================================*/

    static public function ctrUpdatePassword($data){

        $passwordNuevo = crypt($data["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

        $tabla = "usuarios";

        $datos = array(
            "id"=>$data["id"],
            "password"=>$passwordNuevo
        );
        $resp = ModelUsers::mdlUpdatePassword($tabla, $datos);

        if($resp == "ok"){

            echo json_encode(array(
                "statusCode" => 200,
                "error" => false,
                "mensaje" =>"Tu contraseña ha sido cambiada exitosamente."
            ));

        }else{

            echo json_encode(array(
                "statusCode" => 400,
                "error" => true,
                "mensaje" =>"¡Error al cambiar su contraseña, contacte con el administrador!"
            ));

        }

    }

    /*=============================================
    REGISTRO DE CUENTA DIRECTA
    =============================================*/

    static public function ctrUserRegister($data){

        $encriptar = crypt($data["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

        //busco los datos del vendedor
        $datoVendedor = ModelUsers::mdlShowUserProfit("saVendedor","co_ven",$data["co_ven"]);

        //busco el ultimo id generado
        $codigo = ModelsConfig::mdlCorrelativoUsuario("usuarios","id");


        $datos = array(
            "id" => isset($codigo["codigo"]) ? $codigo["codigo"] : 1,
            "email" => $data["email"],
            "usuario" => $data["username"],
            "nombre" => $datoVendedor["ven_des"],
            "password" => $encriptar,
            "foto" => "",
            "estado" => 1,
            "modo" => "directo",
            "co_ven" => $data["co_ven"]);

        //ANTES REALIZO UNA VALIDACION SI EL USUARIO EXISTE NUEVAMENTE PARA EVITAR DUPLICIDAD
        $result = self::ctrShowUsers("email",trim($data["email"]),"usuario") ;

        //echo json_encode(array("datos"=>$datos));
        if(isset($result["email"])){

            echo json_encode(array(
                "statusCode" => 400,
                "error" => true,
                "mensaje" =>"¡El correo electrónico y/o usuario ya existe.",
            ));

        }else{
            $tabla = "usuarios";

            $respuesta = ModelUsers::mdlUserRegister($tabla, $datos);

            if($respuesta == "ok"){

                echo json_encode(array(
                    "statusCode" => 200,
                    "error" => false,
                    "datos"=>$datos,
                    "mensaje" =>"¡Excelente trabajo " . $data["username"],
                ));
            }else{

                echo json_encode(array(
                    "statusCode" => 400,
                    "error" => true,
                    "datos"=>$datos,
                    "mensaje" =>"¡Error registrando el usuario, contacte al administrador ",
                ));
            }
        }

    }

    /*=============================================
    MOSTRAR USUARIOS
    =============================================*/
    static public function ctrShowUsers($item,$valor,$item2){

        $tabla = "usuarios";

        $respuesta = ModelUsers::mdlShowUsers($tabla,$item,$valor, $item2);

        return $respuesta;
    }

    /*=============================================
    VERIFICACION DE EMAIL DE CUENTA DIRECTA
    =============================================*/

    static public function ctrVerifyUser($data){


        $item = "email_encriptado";

        $item2 = "usuario";

        $valor = $data["conVerifyUser"];

        $respuesta = self::ctrShowUsers($item, $valor,$item2);

        if(isset($respuesta["email_encriptado"])){

            $tabla = "usuarios";

            $item2 = "id";

            $valor2 = $respuesta["id"];

            $item1 = "verificacion";

            $valor1 = 0;

            $respuesta2 = ModelUsers::mdlUpdateUser($tabla, $item1, $valor1, $item2, $valor2);

            if($respuesta2 == "ok"){

                echo json_encode(array(
                    "statusCode" => 200,
                    "error" => false,
                    "mensaje" =>"Usuario verificado"
                ));

            }

        }else{
            echo json_encode(array(
                "statusCode" => 400,
                "error" => true,
                "mensaje" =>"¡Error verificando el usuario, contacte con el administrador!"
            ));
        }
    }

    static public function ctrUpdateName($data){

        $respuesta = ModelUsers::mdlUpdateName("usuarios",$data);

        if($respuesta == "ok")
        {
            echo json_encode(array(
                "statusCode" => 200,
                "error" => false,
                "infoUser" =>ModelUsers::mdlShowUser("usuarios","id",$data["id"])
            ));
        }else{
            echo json_encode(array(
                "statusCode" => 400,
                "error" => true,
                "infoUser" =>$respuesta
            ));
        }


        //echo $respuesta;
    }

    static public function ctrUpdateEmail($data){

        $result = ModelUsers::mdlShowUser("usuarios","email",$data["email"]);

        if(isset($result["email"])){
            echo json_encode(array(
                "statusCode" => "201",
                "error" => true,
                "infoUser" =>""
            ));
        }else{
            $respuesta = ModelUsers::mdlUpdateDataUser("usuarios","email",$data["email"],"id",$data["id"]);
            if($respuesta =="ok"){

                echo json_encode(array(
                    "statusCode" => 200,
                    "error" => false,
                    "infoUser" =>ModelUsers::mdlShowUser("usuarios","id",$data["id"])
                ));
            }else{
                echo json_encode(array(
                    "statusCode" => 400,
                    "error" => true,
                    "infoUser" =>$respuesta
                ));
            }

        }
    }

    static public function ctrUpdateDataUser($data){

        //validar primero si el usuario existe
        $result = ModelUsers::mdlShowUser("usuarios","usuario",$data["usuario"]);

        if(isset($result["id"])){

            echo json_encode(array(
                "statusCode" => 201,
                "error" => false,
                "infoUser" =>ModelUsers::mdlShowUser("usuarios","id",$data["id"])
            ));
        }else{

            $respuesta = ModelUsers::mdlUpdateDataUser("usuarios","usuario",$data["usuario"],"id",$data["id"]);

            if($respuesta =="ok"){
                echo json_encode(array(
                    "statusCode" => 200,
                    "error" => false,
                    "infoUser" =>ModelUsers::mdlShowUser("usuarios","id",$data["id"])
                ));
            }else{
                echo json_encode(array(
                    "statusCode" => 400,
                    "error" => true,
                    "infoUser" =>$respuesta
                ));
            }
        }


    }

    static public function ctrShowVendedor(){

        $respuesta = ModelUsers::mdlShowVendedor("vendedores");

        echo json_encode($respuesta,http_response_code($respuesta["status"]));
    }

    static public function ctrRegisterVendedor($data){
        
        $respuesta = ModelUsers::mdlShowRegister("vendedores", "co_ven", $data["co_ven"]);

        if(isset($respuesta["id"])){
            //update
            $result = ModelUsers::mdlUpdateVendedor("vendedores",$data);
        }else{
            //register
            $result = ModelUsers::mdlRegisterVendedor("vendedores", $data);
        }
        echo json_encode($result,http_response_code($result["status"]));
            
    }

    /*=============================================
        FUNCIONES
    =============================================*/

    /*=============================================
        REGISTRAR FECHA PARA SABER EL ÚLTIMO LOGIN
    =============================================*/
    static public function ctrUpdateLastLogin($tabla,$id){

        date_default_timezone_set('America/Bogota');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');

        $fechaActual = $fecha.' '.$hora;

        $item1 = "ultimo_login";
        $valor1 = $fechaActual;

        $item2 = "id";
        $valor2 = $id;

        ModelUsers::mdlUpdateUser($tabla, $item1, $valor1, $item2, $valor2);
    }
}