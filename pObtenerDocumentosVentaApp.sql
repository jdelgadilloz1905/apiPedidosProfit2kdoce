
/****** Object:  StoredProcedure [dbo].[pObtenerDocumentosVenta]  PARA LA APP MOVIL   Script Date: 5/11/2021 10:03:58 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[pObtenerDocumentosVentaApp] ( @sCliente CHAR(16) )
AS 
    BEGIN

        DECLARE @bValidaPagar AS BIT

        SET @bValidaPagar = ( SELECT TOP ( 1 )
                                Cb_Canc_Comp_Ord_Pag
                              FROM
                                par_emp
                            )

        SELECT
            dc.*, dxpp.hasta1 AS Hasta1, dxpp.Hasta2 AS Hasta2, dxpp.Hasta3 AS Hasta3, dxpp.Hasta4 AS Hasta4,
            dxpp.Hasta5 AS Hasta5, dxpp.porc1 AS Porc1, dxpp.porc2 AS porc2, dxpp.porc3 AS porc3, dxpp.porc4 AS porc4,
            dxpp.porc5 AS porc5, dxpp.porc6 AS porc6, dxpp.tip_Cli AS TipoCliente, 
	
	------------
            ( CASE td.tipo_mov
                WHEN 'DE' THEN '+'
                ELSE '-'
              END ) AS signoVsTipo, CAST(( CASE pr.tipo_adi
                                             WHEN 2 THEN 1
                                             ELSE 0
                                           END ) AS BIT) AS esCasaMatriz, CAST(( CASE pr.tipo_adi
                                                                                   WHEN 2 THEN 1
                                                                                   ELSE 0
                                                                                 END ) AS BIT) AS esCasaMatriz,
            ISNULL(( dc.total_neto - dc.monto_imp ), 0) AS Monto_Obj, CAST(0 AS BIT) esPersistente,
            @bValidaPagar AS cobrarParEmp,
            CAST(( CASE WHEN dc.co_tipo_doc = 'FACT' THEN ( (SELECT
                                                                COUNT(fcr.reng_num)
                                                             FROM
                                                                saFacturaVentaReng fcr
                                                             WHERE
                                                                dc.nro_doc = fcr.doc_num)
                                                          )
                        ELSE ( (SELECT
                                    COUNT(dcr.reng_num)
                                FROM
                                    saDocumentoVentaReng dcr
                                WHERE
                                    dc.co_tipo_doc = dcr.co_tipo_doc
                                    AND dc.nro_doc = dcr.nro_doc)
                             )
                   END ) AS BIT) AS existeDocReng, td.aplica_dxpp_venta, td.aplica_riva_venta,
            [dbo].[ExisteCobroRetencionDocumentoVenta](dc.co_tipo_doc, dc.nro_doc, 1, NULL) AS ExisteCobroRetenIva, --Ya existe un Cobro para el documento en el cual se realizao una retención de IVA	
			[dbo].[ExistePagoRetenISLRDocumentoVenta] (dc.co_tipo_doc, dc.nro_doc, 1, NULL) AS ExistePagoRetenIslr  --Ya existe un pago para el documento en el cual se realizao una retención de ISLR	
        FROM
            saDocumentoVenta dc
            INNER JOIN saTipoDocumento td ON dc.co_tipo_doc = td.co_tipo_doc
            INNER JOIN saCliente pr ON pr.co_cli = @sCliente
		
		
		--INNER JOIN saCliente Cl ON C.co_cli = Cl.co_cli
            LEFT JOIN saDescProntoPago dxpp ON dxpp.tip_Cli = pr.tip_Cli
        WHERE
            td.usar_ventas = 1
            AND dc.anulado = 0
            AND dc.saldo > 0
			AND dc.co_tipo_doc <>'FACT'
            AND dc.co_cli = @sCliente
            AND ( ( @bValidaPagar = 0
                    OR td.act_prog_pago = 0
                  )
                  OR ( @bValidaPagar = 1
                       AND td.act_prog_pago = 1
                     )
                )
    END
