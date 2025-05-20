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

                    <div class="temporada-jornada">
                        <xsl:if test="count(pedidos/pedido) = 0">
                            <h2>No has realizado ningún pedido.</h2>
                        </xsl:if>
                    </div>

                    <xsl:for-each select="pedidos/pedido">
                        <div class="partido">
                            <div class="detkalles-partido">
                                <p><strong>Código:</strong> <xsl:value-of select="codigo" /></p>
                                <p><strong>Artículo:</strong> <xsl:value-of select="articulo" /></p>
                                <p><strong>Dirección:</strong> <xsl:value-of select="direccion" /></p>
                                <p><strong>Fecha:</strong> <xsl:value-of select="fecha" /></p>
                                <p><strong>Hora:</strong> <xsl:value-of select="hora" /></p>
                                <p><strong>Cantidad:</strong> <xsl:value-of select="cantidad" /></p>
                                <p><strong>Precio unitario:</strong> <xsl:value-of select="precio" /></p>
                                <p><strong>Total pagado:</strong> <xsl:value-of select="pagado" /></p>
                                <p><strong>Usuario:</strong> <xsl:value-of select="usuario" /></p>
                            </div>

                            <xsl:if test="rutaImagenPedido">
                                <div class="calendario-equipos">
                                    <div class="calendario-equipo">
                                        <img src="{rutaImagenPedido}" alt="Imagen del pedido" />
                                        <p><strong class="partido-equipos">Artículo: <xsl:value-of select="articulo" /></strong></p>
                                    </div>
                                </div>
                            </xsl:if>
                        </div>
                    </xsl:for-each>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
