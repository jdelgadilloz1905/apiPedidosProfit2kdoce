
/****** Object:  Table [dbo].[saFavoritos]    Script Date: 15/10/2021 14:31:47 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[saFavoritos](
	[co_user] [varchar](10) NOT NULL,
	[co_art] [varchar](20) NOT NULL,
	[fecha_reg] [smalldatetime] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[saPedidoVentaApp]    Script Date: 15/10/2021 14:31:47 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[saPedidoVentaApp](
	[fact_num] [varchar](20) NULL,
	[co_cli] [char](10)  NULL,
	[co_user] [char](10)  NULL,
	[fecha_reg] [datetime]  NULL,
	[tot_neto] [decimal](18, 2)  NULL,
	[cli_des] [varchar](50)  NULL,
	[direc1] [text] NULL,
	[rif] [varchar](50)  NULL,
	[telefonos] [varchar](50) NULL,
	[co_tran] [char](10)  NULL,
	[co_sucu] [char](10)  NULL,
	[forma_pag] [char](10)  NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
ALTER TABLE [dbo].[saPedidoVentaApp] ADD  CONSTRAINT [DF_saPedidoVentaApp_fecha_reg]  DEFAULT (getdate()) FOR [fecha_reg]
go
/****** Object:  Table [dbo].[saPedidoVentaRengApp]    Script Date: 15/10/2021 14:31:47 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[saPedidoVentaRengApp](
	[reng_doc] [int] NOT NULL,
	[fact_num] [varchar](20) NULL,
	[co_art] [varchar](30) NOT NULL,
	[total_art] [decimal](18, 5) NOT NULL,
	[prec_vta] [decimal](18, 5) NOT NULL,
	[total] [decimal](18, 5) NOT NULL,
	[fecha_reg] [datetime] NOT NULL,
	[art_des] [varchar](200) NOT NULL,
	[descuento] [decimal](18, 5) NOT NULL,
	[stock_prev] [decimal](18, 5) NOT NULL,
	[stock_act] [decimal](18, 5) NOT NULL
) ON [PRIMARY]
GO
ALTER TABLE [dbo].[saFavoritos] ADD  CONSTRAINT [DF_saFavoritos_fecha_reg]  DEFAULT (CONVERT([varchar](10),getdate(),(104))) FOR [fecha_reg]
GO
ALTER TABLE [dbo].[saPedidoVentaApp] ADD  CONSTRAINT [DF_saPedidoVentaApp_fecha_reg]  DEFAULT (getdate()) FOR [fecha_reg]
GO
ALTER TABLE [dbo].[saPedidoVentaRengApp] ADD  CONSTRAINT [DF_saPedidoVentaRengApp_fecha_reg]  DEFAULT (getdate()) FOR [fecha_reg]
GO
