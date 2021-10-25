<?php

class ControllerProducts{

    /*=============================================
    MOSTRAR LOS PRODUCTOS
    =============================================*/
    static public function ctrShowProducts($data){

        $item = null;

        $valor = null;

        $respuesta = ModelProducts::mdlShowProducts($item,$valor);

        echo json_encode(array(
            "statusCode" => 200,
            "error" => false,
            "total" =>count($respuesta),
            "infoProduct" =>$respuesta,
            "mensaje" =>""
        ));

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

        $item = null;

        $valor = null;

        $respuesta = ModelProducts::mdlConsultarPreciosArticulo($item,$valor);

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