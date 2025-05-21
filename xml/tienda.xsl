<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" indent="yes" />

    <xsl:template match="/raiz"> 
        <div class="contenedor-equipos">
            <div class="temporada-header">
                <h1>Todo nuestros Artículos:</h1>
                <h2>Lo mejor en productos musicales de lata gama</h2>
            </div>

            <!-- Lista de artículos -->
            <div class="equipos" id="equipos">
                <xsl:choose>
                    <xsl:when test="count(articulos/articulo) > 0">
                        <xsl:for-each select="articulos/articulo">
                            <div class="equipo">
                                <img class="logo" src="{rutaImagenArticulo}" alt="Imagen del artículo" />
                                <strong><xsl:value-of select="nombre" /></strong>
                                <strong><xsl:value-of select="precio" />€/u</strong>
                                <strong>Stock:<xsl:value-of select="stock" /></strong>
                            </div>
                        </xsl:for-each>
                    </xsl:when>
                    <xsl:otherwise>
                        <p>No hay artículos disponibles.</p>
                    </xsl:otherwise>
                </xsl:choose>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>
