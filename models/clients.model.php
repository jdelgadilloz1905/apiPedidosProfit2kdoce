<?php
require_once "conexion.php";

class ModelClients{

    static public function mdlShowClients($tabla,$data){

        if($data["co_ven"] == 99999){
            $stmt = Conexion::conectar()->prepare(" SELECT * from $tabla order by co_cli desc");
        }else{
            $stmt = Conexion::conectar()->prepare(" SELECT * from $tabla where co_ven = :co_ven order by co_cli desc");
            $stmt -> bindParam(":co_ven", $data["co_ven"], PDO::PARAM_STR);
        }
        $stmt -> execute();
        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $stmt -> close();
        $stmt = null;
    }
    
    static public function mdlShowFileApp($table, $item, $valor)
    {
        $stmt = Conexion::conectar()->prepare(" SELECT * from $table where $item = :$item");

        $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlShowDocumentosApp($table, $item, $valor,$item1, $valor1)
    {
        $stmt = Conexion::conectar()->prepare(" SELECT * from $table where $item = :$item and $item1 = :$item1");

        $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    /*=============================================
    REGISTRAR DATOS  
    =============================================*/
    static public function mdlRegisterFile($table, $data){

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

    static public function mdlUpdateCliente($table,$data){

        try {
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET cli_des = :cli_des, direc1 = :direc1, rif = :rif, co_ven = :co_ven,
                                                                     telefonos = :telefonos, tip_cli = :tip_cli, cond_pag = :cond_pag,
                                                                     cond_des = :cond_des,tipo_precio = :tipo_precio WHERE co_cli = :co_cli");

            $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);
            $stmt -> bindParam(":cli_des", $data["cli_des"], PDO::PARAM_STR);
            $stmt -> bindParam(":direc1", $data["direc1"], PDO::PARAM_STR);
            $stmt -> bindParam(":rif", $data["rif"], PDO::PARAM_STR);
            $stmt -> bindParam(":co_ven", $data["co_ven"], PDO::PARAM_STR);
            $stmt -> bindParam(":telefonos", $data["telefonos"], PDO::PARAM_STR);
            $stmt -> bindParam(":tip_cli", $data["tip_cli"], PDO::PARAM_STR);
            $stmt -> bindParam(":cond_pag", $data["cond_pag"], PDO::PARAM_STR);
            $stmt -> bindParam(":cond_des", $data["cond_des"], PDO::PARAM_STR);
            $stmt -> bindParam(":tipo_precio", $data["tipo_precio"], PDO::PARAM_STR);
            
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

        } catch (Exception $e) {

            return $response = array(
                "status"=>500,
                "result"=>$e->getMessage(),
                "comment" => "Fallo el proceso"
            );
        }
    }

    static public function mdlUpdateTransporte($table,$data){

        try {
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET des_tran = :des_tran WHERE co_tran = :co_tran");

            $stmt -> bindParam(":co_tran", $data["co_tran"], PDO::PARAM_STR);
            $stmt -> bindParam(":des_tran", $data["des_tran"], PDO::PARAM_STR);


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

    static public function mdlUpdateCondicio($table,$data){

        try {
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET cond_des = :cond_des, dias_cred = :dias_cred WHERE co_cond = :co_cond");

            $stmt -> bindParam(":co_cond", $data["co_cond"], PDO::PARAM_STR);
            $stmt -> bindParam(":cond_des", $data["cond_des"], PDO::PARAM_STR);
            $stmt -> bindParam(":dias_cred", $data["dias_cred"], PDO::PARAM_STR);


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

    static public function mdlUpdateFacturaPendiente($table, $data){

        try {
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET co_cli = :co_cli, co_ven = :co_ven, cli_des = :cli_des, fec_emis = :fec_emis, fec_venc = :fec_venc,
                                                              saldo = :saldo,tasa = :tasa WHERE doc_num = :doc_num");

            $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);
            $stmt -> bindParam(":co_ven", $data["co_ven"], PDO::PARAM_STR);
            $stmt -> bindParam(":cli_des", $data["cli_des"], PDO::PARAM_STR);
            $stmt -> bindParam(":doc_num", $data["doc_num"], PDO::PARAM_STR);
            $stmt -> bindParam(":fec_emis", $data["fec_emis"], PDO::PARAM_STR);
            $stmt -> bindParam(":fec_venc", $data["fec_venc"], PDO::PARAM_STR);
            $stmt -> bindParam(":saldo", $data["saldo"], PDO::PARAM_STR);
            $stmt -> bindParam(":tasa", $data["tasa"], PDO::PARAM_STR);


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

    static public function mdlUpdateDocumentos($table, $data){

        try {
            $stmt = Conexion::conectar()->prepare("UPDATE $table SET co_cli = :co_cli, co_ven = :co_ven, 
                                                                              fec_emis = :fec_emis, fec_venc = :fec_venc, total_neto = :total_neto,
                                                                              saldo = :saldo, existeDocReng = :existeDocReng WHERE nro_doc = :nro_doc and co_tipo_doc = :co_tipo_doc ");

            $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);
            $stmt -> bindParam(":co_ven", $data["co_ven"], PDO::PARAM_STR);
            $stmt -> bindParam(":co_tipo_doc", $data["co_tipo_doc"], PDO::PARAM_STR);
            $stmt -> bindParam(":nro_doc", $data["nro_doc"], PDO::PARAM_STR);
            $stmt -> bindParam(":fec_emis", $data["fec_emis"], PDO::PARAM_STR);
            $stmt -> bindParam(":fec_venc", $data["fec_venc"], PDO::PARAM_STR);
            $stmt -> bindParam(":saldo", $data["saldo"], PDO::PARAM_STR);
            $stmt -> bindParam(":total_neto", $data["total_neto"], PDO::PARAM_STR);
            $stmt -> bindParam(":existeDocReng", $data["existeDocReng"], PDO::PARAM_STR);


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

    static public function mdlGetClientsLike($tabla,$likess){

        $stmt = Conexion::conectar()->query("SELECT  c.co_cli, c.cli_des,c.direc1, c.telefonos, c.rif,c.tip_cli, c.cond_pag, (select cond_des from saCondicionPago where co_cond = c.cond_pag) as cond_des,
                                                          (select top 1 co_precio from saTipoCliente where tip_cli = c.tip_cli ) tipo_precio
                                                          from $tabla c where (c.co_cli like '%$likess%' or c.cli_des like '%$likess%' or c.direc1 like '%$likess%' or c.rif like '%$likess%') and c.inactivo =0 
                                                            order by c.co_cli desc ");

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlGetCuentaXCobrar($tabla,$data){

        $stmt = Conexion::conectar()->prepare("SELECT  * from  $tabla where co_cli = :co_cli ORDER BY co_cli DESC ");

        $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlCuentaXCobrarVendedor($data){

        if($data["co_ven"] == 99999){

            $stmt = Conexion::conectar()->prepare("SELECT dc.co_cli,pr.cli_des,pr.direc1, pr.telefonos, pr.rif, pr.tip_cli,pr.tipo_precio 
                                                            FROM
                                                                documentos dc
                                                                INNER JOIN clientes pr ON pr.co_cli = dc.co_cli
                                                            GROUP BY 
                                                                dc.co_cli,pr.cli_des,pr.direc1, pr.telefonos, pr.rif, pr.tip_cli,pr.tipo_precio  
                                                            ORDER BY 
                                                                dc.co_cli");


        }else{
            $stmt = Conexion::conectar()->prepare("SELECT dc.co_cli,pr.cli_des,pr.direc1, pr.telefonos, pr.rif, pr.tip_cli,pr.tipo_precio 
                                                            FROM
                                                                documentos dc
                                                                INNER JOIN clientes pr ON pr.co_cli = dc.co_cli
                                                            WHERE
                                                                dc.co_ven = :co_ven
                                                            GROUP BY 
                                                                dc.co_cli,pr.cli_des,pr.direc1, pr.telefonos, pr.rif, pr.tip_cli,pr.tipo_precio 
                                                            ORDER BY 
                                                                dc.co_cli");

            $stmt -> bindParam(":co_ven", $data["co_ven"], PDO::PARAM_STR);
        }

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlObtenerNotasEntregaXCliente($data){

        $stmt = Conexion::conectar()->prepare("SELECT * from notas_entregas where co_cli = :co_cli ORDER BY doc_num desc ");

        $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);


        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlObtenerDocumentos($data){


        $stmt = Conexion::conectar()->prepare("SELECT * from documentos where co_cli = :co_cli ");

        $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlListOptionCliente($tabla,$item1,$item2){

        $stmt = Conexion::conectar()->query("SELECT LTRIM(RTRIM($item1)) as value,$item2 as label FROM $tabla  ");

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlProximoNumero(){

        try {

            $sql ="EXEC pObtenerProximoNumero @sTabla=N'saCliente', @sCampo=N'co_cli' ,@sPrefijo=''";

            $stmt = Conexion::conectar()->query($sql);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);


        }catch (PDOException $pe) {

            die("Error occurred:" . $pe->getMessage());

        }

        return $stmt -> fetch();

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlregistrarCliente($datos){

        try {

            $sql="exec pInsertarCliente @sCo_Cli='$datos[sCo_Cli]',@sCli_Des='$datos[sCli_Des]',@sCo_Seg='$datos[sCo_Seg]',@sCo_Zon='$datos[sCo_Zon]',@sSalesTax=$datos[sSalesTax],@sLogin=$datos[sLogin],@binactivo=$datos[binactivo],@blunes=$datos[blunes],
            @bmartes=$datos[bmartes],@bmiercoles=$datos[bmiercoles],@bjueves=$datos[bjueves],@bviernes=$datos[bviernes], @bsabado=$datos[bsabado],@bdomingo=$datos[bdomingo],@bcontrib=$datos[bcontrib],@bvalido=$datos[bvalido],@bsincredito=$datos[bsincredito],@sDirec1='$datos[sDirec1]',
            @sDirec2=$datos[sDirec2],@stelefonos='$datos[stelefonos]',@sfax=$datos[sfax],@sRespons='$datos[sRespons]',@sdfecha_reg='$datos[sdfecha_reg]',@stip_cli='$datos[stip_cli]',@demont_cre=$datos[demont_cre],@iplaz_pag=$datos[iplaz_pag],@iId=$datos[iId], @iPuntaje=$datos[iPuntaje],@dedesc_ppago=$datos[dedesc_ppago],@dedesc_glob=$datos[dedesc_glob],
            @srif='$datos[srif]',@sdis_cen=$datos[sdis_cen],@snit=$datos[snit],@sco_cta_ingr_egr='$datos[sco_cta_ingr_egr]',@scomentario='$datos[scomentario]',@bjuridico=$datos[bjuridico],@itipo_adi=$datos[itipo_adi],@smatriz=$datos[smatriz],@sco_tab=$datos[sco_tab],@stipo_per=$datos[stipo_per],@sco_pais='$datos[sco_pais]',
            @sciudad='$datos[sciudad]',@szip=$datos[szip],@sWebSite=$datos[sWebSite],@bcontribu_e=$datos[bcontribu_e],@brete_regis_doc=$datos[brete_regis_doc],@deporc_esp=$datos[deporc_esp],@spassword=$datos[spassword],@sestado=$datos[sestado],@sserialp=$datos[sserialp],@semail='$datos[semail]',@sdir_ent2='$datos[sdir_ent2]',@sfrecu_vist=$datos[sfrecu_vist],
            @shorar_caja=$datos[shorar_caja],@sco_ven='$datos[sco_ven]', @sco_mone='$datos[sco_mone]',@scond_pag='$datos[scond_pag]',@sTComp=$datos[sTComp],@sN_db=$datos[sN_db],@sN_cr=$datos[sN_cr],@semail_alterno=$datos[semail_alterno],@sCampo1=$datos[sCampo1],@sCampo2=$datos[sCampo2],@sCampo3=$datos[sCampo3],@sCampo4=$datos[sCampo4],
            @sCampo5=$datos[sCampo5],@sCampo6=$datos[sCampo6],@sCampo7=$datos[sCampo7],@sCampo8=$datos[sCampo8],@sRevisado=$datos[sRevisado], @sTrasnfe=$datos[sTrasnfe],@sco_sucu_in='$datos[sco_sucu_in]',@sco_us_in='$datos[sco_us_in]',@sMaquina='$datos[sMaquina]'";


            $stmt = Conexion::conectar()->query($sql);

            return "ok";

            $stmt->close();

            $stmt = null;


        }catch (PDOException $pe) {

            die("Error occurred:" . $pe->getMessage());

        }

        return $stmt -> fetch();

        $stmt -> close();

        $stmt = null;
    }


}