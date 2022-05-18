USE [AQUILA_A]
GO
/****** Object:  StoredProcedure [dbo].[pObtenerDocumentosVenta2]    Script Date: 17/5/2022 13:20:25 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[pObtenerDocumentosVenta2] --(@sCliente CHAR(16))
AS 
    BEGIN

        DECLARE @bValidaPagar AS BIT
        SET @bValidaPagar = (SELECT TOP (1) Cb_Canc_Comp_Ord_Pag FROM par_emp)
        SELECT dc.co_tipo_doc,dc.nro_doc,dc.fec_emis,dc.fec_venc, dc.total_neto, dc.saldo, dc.co_cli,dc.co_ven,
            CAST(( CASE WHEN dc.co_tipo_doc = 'FACT' THEN ((SELECT COUNT(fcr.reng_num)
                                                             FROM  saFacturaVentaReng fcr
                                                             WHERE dc.nro_doc = fcr.doc_num))
                        WHEN dc.co_tipo_doc = 'NENT' THEN ((SELECT COUNT(ncr.reng_num)
                                                             FROM saNotaEntregaVentaReng ncr
                                                             WHERE dc.nro_doc = ncr.doc_num))
						ELSE ((SELECT COUNT(dcr.reng_num)
                                FROM saDocumentoVentaReng dcr
                                WHERE dc.co_tipo_doc = dcr.co_tipo_doc AND dc.nro_doc = dcr.nro_doc))
                   END) AS BIT) AS existeDocReng
        FROM
            saDocumentoVenta dc
            INNER JOIN saTipoDocumento td ON dc.co_tipo_doc = td.co_tipo_doc
            INNER JOIN saCliente pr ON pr.co_cli = dc.co_cli		
		--INNER JOIN saCliente Cl ON C.co_cli = Cl.co_cli
            LEFT JOIN saDescProntoPago dxpp ON dxpp.tip_Cli = pr.tip_Cli
        WHERE
            td.usar_ventas = 1
            AND dc.anulado = 0
            AND dc.saldo > 0
            --AND dc.co_cli = @sCliente
            AND ((@bValidaPagar = 0 OR td.act_prog_pago = 0) OR (@bValidaPagar = 1 AND td.act_prog_pago = 1 ))
    END