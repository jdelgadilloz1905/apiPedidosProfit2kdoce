<?php
require_once "conexion.php";

class ModelClients{

    static public function mdlShowClients($tabla,$data){

        if($data["co_ven"] == 99999){
            $stmt = Conexion::conectar()->prepare(" SELECT  c.co_cli, c.cli_des,c.direc1, c.telefonos, c.rif, c.tip_cli, c.cond_pag, (select cond_des from saCondicionPago where co_cond = c.cond_pag) as cond_des,
                                                            (select top 1 co_precio from saTipoCliente where tip_cli = c.tip_cli ) tipo_precio
                                                            from $tabla c 
                                                                where c.inactivo =0
                                                                order by c.co_cli desc");


        }else{

            $stmt = Conexion::conectar()->prepare(" SELECT  c.co_cli, c.cli_des,c.direc1, c.telefonos, c.rif, c.tip_cli, c.cond_pag, (select cond_des from saCondicionPago where co_cond = c.cond_pag) as cond_des,
                                                            (select top 1 co_precio from saTipoCliente where tip_cli = c.tip_cli ) tipo_precio
                                                            from $tabla c 
                                                                where c.inactivo =0 and c.co_ven = :co_ven
                                                                order by c.co_cli desc");

            $stmt -> bindParam(":co_ven", $data["co_ven"], PDO::PARAM_STR);
        }



        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
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

        $stmt = Conexion::conectar()->prepare("SELECT  c.cli_des, f.doc_num,CONVERT(VARCHAR,f.fec_emis, 101) AS fec_emis,CONVERT(VARCHAR,f.fec_venc, 101) AS fec_venc, 
                                                            FORMAT(f.saldo/(case when ISNULL(f.campo1,0) <> '0' then replace(f.campo1,',','.')  else case when f.tasa > 1 then f.tasa else (select tasa_v from saTasa where CONVERT(VARCHAR,fecha, 101) = CONVERT(VARCHAR,f.fec_emis, 101)) end  end),'##,###.00') saldo,
                                                            case when ISNULL(f.campo1,0) <> '0' then replace(f.campo1,',','.')  else case when f.tasa > 1 then f.tasa else (select tasa_v from saTasa where CONVERT(VARCHAR,fecha, 101) = CONVERT(VARCHAR,f.fec_emis, 101)) end  end  as tasa
                                                            FROM saFacturaVenta f 
                                                            LEFT JOIN saCliente c 
                                                            ON c.co_cli = f.co_cli WHERE f.saldo>0 AND f.co_cli = :co_cli ORDER BY f.co_cli DESC ");

        $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlCuentaXCobrarVendedor($data){

        if($data["co_ven"] == 99999){

            $stmt = Conexion::conectar()->prepare("SELECT dc.co_cli,pr.cli_des,pr.direc1, pr.telefonos, pr.rif, pr.tip_cli, 
                                                            (select top 1 co_precio from saTipoCliente where tip_cli = pr.tip_cli ) tipo_precio 
                                                            FROM
                                                                saDocumentoVenta dc
                                                                INNER JOIN saTipoDocumento td ON dc.co_tipo_doc = td.co_tipo_doc
                                                                INNER JOIN saCliente pr ON pr.co_cli = dc.co_cli
                                                                LEFT JOIN saDescProntoPago dxpp ON dxpp.tip_Cli = pr.tip_Cli
                                                            WHERE
                                                                td.usar_ventas = 1
                                                                AND dc.anulado = 0
                                                                AND dc.saldo > 0
                                                                AND pr.inactivo =0
                                                            GROUP BY 
                                                                dc.co_cli,pr.cli_des,pr.direc1, pr.telefonos, pr.rif, pr.tip_cli 
                                                            ORDER BY 
                                                                dc.co_cli");


        }else{
            $stmt = Conexion::conectar()->prepare("SELECT dc.co_cli,pr.cli_des,pr.direc1, pr.telefonos, pr.rif, pr.tip_cli, 
                                                            (select top 1 co_precio from saTipoCliente where tip_cli = pr.tip_cli ) tipo_precio 
                                                            FROM
                                                                saDocumentoVenta dc
                                                                INNER JOIN saTipoDocumento td ON dc.co_tipo_doc = td.co_tipo_doc
                                                                INNER JOIN saCliente pr ON pr.co_cli = dc.co_cli
                                                                LEFT JOIN saDescProntoPago dxpp ON dxpp.tip_Cli = pr.tip_Cli
                                                            WHERE
                                                                td.usar_ventas = 1
                                                                AND dc.anulado = 0
                                                                AND dc.saldo > 0
                                                                AND dc.co_ven = :co_ven
                                                                AND pr.inactivo =0
                                                            GROUP BY 
                                                                dc.co_cli,pr.cli_des,pr.direc1, pr.telefonos, pr.rif, pr.tip_cli 
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

        $stmt = Conexion::conectar()->prepare("SELECT c.cli_des, v.doc_num, CONVERT(VARCHAR,v.fec_emis, 101) AS fec_emis,CONVERT(VARCHAR,v.fec_venc, 101) AS fec_venc,
                                                            FORMAT(v.total_neto/(case when ISNULL(v.campo1,0) <> '0' then replace(v.campo1,',','.')  else case when v.tasa > 1 then v.tasa else (select tasa_v from saTasa where CONVERT(VARCHAR,fecha, 101) = CONVERT(VARCHAR,v.fec_emis, 101)) end  end) ,'##,###.00') saldo,
                                                            case when ISNULL(v.campo1,0) <> '0' then replace(v.campo1,',','.')  else case when v.tasa > 1 then v.tasa else (select tasa_v from saTasa where CONVERT(VARCHAR,fecha, 101) = CONVERT(VARCHAR,v.fec_emis, 101)) end  end  as tasa
                                                            FROM saNotaEntregaVenta v
                                                                INNER JOIN saCliente c ON v.co_cli = c.co_cli
                                                                WHERE v.anulado = 0 AND v.co_cli = :co_cli AND v.status <>2 
                                                                ORDER BY v.doc_num desc ");

        $stmt -> bindParam(":co_cli", $data["co_cli"], PDO::PARAM_STR);


        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $stmt -> close();

        $stmt = null;
    }

    static public function mdlObtenerDocumentos($data){

        try {

            //$sql ="EXEC pObtenerDocumentosVenta @sCliente=N'$data[co_cli]'";

            $sql ="EXEC pObtenerDocumentosVentaApp @sCliente=N'$data[co_cli]'";

            $stmt = Conexion::conectar()->query($sql);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);


        }catch (PDOException $pe) {

            die("Error occurred:" . $pe->getMessage());

        }

        return $stmt -> fetchAll();

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