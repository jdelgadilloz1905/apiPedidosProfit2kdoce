USE [MasterProfit]
GO
/****** Object:  Table [dbo].[usuarios]    Script Date: 21/10/2021 15:30:47 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[usuarios](
	[id] [int] NOT NULL,
	[email] [varchar](50) NOT NULL,
	[usuario] [varchar](50) NOT NULL,
	[nombre] [varchar](50) NOT NULL,
	[apellido] [varchar](50) NOT NULL,
	[password] [varchar](150) NOT NULL,
	[foto] [varchar](100) NOT NULL,
	[estado] [int] NOT NULL,
	[fecha_creacion] [smalldatetime] NOT NULL,
	[modo] [varchar](10) NOT NULL,
	[ultimo_login] [datetime] NOT NULL,
	[co_ven] [varchar](10) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT ((space((1))) collate SQL_Latin1_General_CP1_CI_AS) FOR [email]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT ((space((1))) collate SQL_Latin1_General_CP1_CI_AS) FOR [usuario]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT ((space((1))) collate SQL_Latin1_General_CP1_CI_AS) FOR [nombre]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT ((space((1))) collate SQL_Latin1_General_CP1_CI_AS) FOR [apellido]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT ((space((1))) collate SQL_Latin1_General_CP1_CI_AS) FOR [password]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT ((space((1))) collate SQL_Latin1_General_CP1_CI_AS) FOR [foto]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT ((1)) FOR [estado]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT (CONVERT([varchar](10),getdate(),(104))) FOR [fecha_creacion]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT ((space((1))) collate SQL_Latin1_General_CP1_CI_AS) FOR [modo]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT (dateadd(millisecond, -datepart(millisecond,getdate()),getdate())) FOR [ultimo_login]
GO
ALTER TABLE [dbo].[usuarios] ADD  DEFAULT ((space((1))) collate SQL_Latin1_General_CP1_CI_AS) FOR [co_ven]
GO
