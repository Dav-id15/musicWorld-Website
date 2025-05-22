<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" indent="yes" />

    <xsl:template match="/raiz">
        <html>
            <head>
                <title>Mis pedidos</title> 
                <link rel="stylesheet" type="text/css" href="/css/estilos.css"/>
            </head>
            <body>
                <div class="contenedor-calendario">
                    <h1>Mis pedidos:</h1>

                    <xsl:if test="count(pedidos/pedido) = 0">
                        <h2>No has realizado ningún pedido.</h2>
                    </xsl:if>

                    <xsl:for-each select="pedidos/pedido">
                        <xsl:variable name="cod" select="normalize-space(cod_pedido)"/>
                        <div class="partido">
                            <a>
                                <xsl:attribute name="href">
                                    <xsl:text>detallePedido.php?cod=</xsl:text>
                                    <xsl:value-of select="cod_pedido"/>
                                </xsl:attribute>
                                
                                <p><strong>Artículo:</strong> <xsl:value-of select="articulo" /></p>
                                <p><strong>Fecha:</strong> <xsl:value-of select="fecha" /></p>
                                <p><strong>Total pagado:</strong> <xsl:value-of select="pagado" /> €</p>
                                <p><strong>Correo:</strong> <xsl:value-of select="correo" /></p>

                                <xsl:if test="rutaImagenPedido">
                                    <img class="pedido-img" src="{rutaImagenPedido}" alt="Imagen del pedido" />
                                </xsl:if>

                                <p><em>Haz clic para ver más detalles</em></p>
                            </a>
                        </div>
                    </xsl:for-each>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
