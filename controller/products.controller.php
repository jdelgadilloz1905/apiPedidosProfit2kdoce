<?php

class ControllerProducts{

    /*=============================================
    MOSTRAR LOS PRODUCTOS
    =============================================*/
    static public function ctrShowProducts($data){

        $item = null;

        $valor = null;

        $respuesta = ModelProducts::mdlShowProducts($item,$valor);

        if(count($respuesta)>0){

            $json =array(
                "statusCode" => "200",
                "mensaje"=>"",
                "total" =>count($respuesta),
                "infoProduct" =>$respuesta
            );


        }else{
            $json =array(
                "statusCode" => "404",
                "mensaje"=>"No hay registros",
                "infoProduct" =>""
            );
        }

        echo json_encode($json,http_response_code($json["statusCode"]));

    }

    /*=============================================
      GET PRODUCTOS DE FAVORITOS
      =============================================*/
    static public function ctrGetfavoriteApi($data){

        $answer = ModelProducts::mdlGetFavoriteApi($data["co_user"]);

        echo json_encode(array(
            "statusCode" => 200,
            "error" => false,
            "total" =>count($answer),
            "infoProduct" =>$answer,
            "mensaje" =>""
        ));
    }

    static public function ctrPrepararProductos($data){


        foreach ($data as $key => $value){
            //solo enviar los productos con stock disponible

            if($value["stock_actual"] != ".00000"){

                $resultado[$key] = (object) [
                    "co_art" => $value["co_art"],
                    "art_des" => $value["art_des"],
                    "des_color" => $value["des_color"],
                    "cat_des" => $value["cat_des"],
                    "lin_des" => $value["lin_des"],
                    "des_ubicacion" => $value["des_ubicacion"],
                    "des_proc" => $value["des_proc"],
                    "stock_actual"  => $value["stock_actual"],

                ];
            }

        }

        return $resultado;
    }


    static public function ctrConsultarPrecioArticulo($data){

        $item = $data["item"];

        $valor = $data["valor"];

        $respuesta = ModelProducts::mdlConsultarPreciosArticulo($item,$valor);

        echo json_encode(array(
            "statusCode" => 200,
            "error" => false,
            "total" =>count($respuesta),
            "infoPrecioArticulo" =>$respuesta,
            "mensaje" =>""
        ));
    }

    static public function ctrConsultarPrecioXTipoCliente($data){

        $valor = $data["valor"];

        $valor1 = $data["valor1"];

        $respuesta = ModelProducts::mdlConsultarPrecioXTipoCliente($valor,$valor1);

        echo json_encode(array(
            "statusCode" => 200,
            "error" => false,
            "total" =>count($respuesta),
            "infoPrecioArticulo" =>$respuesta,
            "mensaje" =>""
        ));
    }


    static public function ctrConsultarStockArticulo($obj){

        $item = null;

        $valor = null;

        $respuesta = ModelProducts::mdlConsultarStockArticulo($item,$valor);

        echo json_encode(array(
            "statusCode" => 200,
            "error" => false,
            "total"=>count($respuesta),
            "infoStockArticulo" =>$respuesta,
            "mensaje" =>""
        ));
    }

    static public function ctrConsultarUnidadArticulo($obj){

        $item = null;

        $valor = null;

        $respuesta = ModelProducts::mdlConsultarUnidadArticulo($item,$valor);

        echo json_encode(array(
            "statusCode" => 200,
            "error" => false,
            "total"=>count($respuesta),
            "infoUnidadArticulo" =>$respuesta,
            "mensaje" =>""
        ));
    }

    /*=============================================
   PRODUCTOS FAVORITOS
   =============================================*/

    static public function ctrGetProductsFavorites($data){

        $tabla = "saFavoritos";

        $answer = ModelProducts::mdlGetProductsFavorites($tabla,$data);

        echo json_encode($answer);
    }

    /*=============================================
      ELIMINAR PRODUCTOS FAVORITOS
      =============================================*/

    static public function ctrDeleteProductsFavorites($data){

        $tabla = "saFavoritos";

        $answer = ModelProducts::mdlDeleteProductsFavorites($tabla,$data);

        echo json_encode($answer);
    }
    /*=============================================
          AGREGA PRODUCTOS A FAVORITOS
          =============================================*/
    static public function ctrAddFavorites($data){

        $tabla = "saFavoritos";

        $answer = ModelProducts::mdlAddFavorites($tabla,$data);

        echo json_encode($answer);
    }


    /*=============================================
      PRUDCTOS SEARCH
      =============================================*/
    static public function ctrGetProductsLike($obj){

        $tabla = "saArticulo";

        $respuesta = ModelProducts::mdlGetProductsLike($tabla,$obj["like"]);

        echo json_encode($respuesta);
    }

    static public function ctrTasa(){

        $respuesta = ModelProducts::mdlTasa();

        echo json_encode(
            array(
                "error" => false,
                "statusCode"=>200,
                "infoMoneda" =>$respuesta
            ));
    }

}