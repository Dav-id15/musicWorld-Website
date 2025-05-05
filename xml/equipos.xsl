<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" indent="yes" />
    <xsl:param name="temporadaSeleccionada" />
    <xsl:param name="equipoSeleccionado" />
    <xsl:param name="rolUsuario" />

    <xsl:template match="/">

        <div class="contenedor-equipos">
            <div class="temporada-header">
                <h1>Temporada <xsl:value-of select="$temporadaSeleccionada" /></h1>
            </div>

            <!-- Lista de equipos -->
            <div class="equipos" id="equipos">
                <xsl:choose>
                    <xsl:when test="count(temporadas/temporada[nombre = $temporadaSeleccionada]/equipos/equipo) > 0">
                        <xsl:for-each select="temporadas/temporada[nombre = $temporadaSeleccionada]/equipos/equipo">
                            <div class="equipo" tabindex="0">
                                <form method="POST" action="equipos" class="form-equipo">
                                    <input type="hidden" name="equipo" value="{nombre}" />
                                    <button type="submit" class="enlace-equipo">
                                        <img class="logo" src="{rutaImagen}" alt="Logo del equipo" />
                                        <strong><xsl:value-of select="nombre" /></strong>
                                    </button>
                                </form>
                            </div>
                        </xsl:for-each>
                    </xsl:when>
                    <xsl:otherwise>
                        <p>No hay equipos disponibles.</p>
                    </xsl:otherwise>
                </xsl:choose>
            </div>

            <!-- Detalles de los equipos -->
            <xsl:for-each select="temporadas/temporada[nombre = $temporadaSeleccionada]/equipos/equipo">
                <div class="detalles-equipo" id="detalle-{nombre}">
                    <div class="equipo-header">
                        <div class="equipo-logo">
                            <img src="{rutaImagen}" alt="Logo del equipo" />
                            <h1><xsl:value-of select="nombre" /></h1>
                        </div>
                        <div class="equipo-info">
                            <p><strong>Temporada: </strong> <xsl:value-of select="$temporadaSeleccionada" /></p>
                            <p><strong>Entrenador: </strong> <xsl:value-of select="entrenador" /></p>
                            <p><strong>Estadio: </strong> <xsl:value-of select="estadio" /></p>
                        </div>
                    </div>

                    <div class="jugadores">
                        <h2>Plantilla</h2>
                        <div class="jugadores-grid">
                            <xsl:for-each select="jugadores/jugador">
                                <div class="jugador">
                                    <img class="jugador-foto" src="{rutaImagen}" alt="{nombre}" />
                                    <div class="jugador-info">
                                        <p><strong><xsl:value-of select="concat(nombre, ' ', apellidos)" /></strong></p>
                                        <p>Dorsal: <xsl:value-of select="dorsal" /></p>
                                        <p>Posición: <xsl:value-of select="posicion" /></p>
                                    </div>
                                </div>
                            </xsl:for-each>
                        </div>
                    </div>
                </div>
            </xsl:for-each>
        </div>
    </xsl:template>
</xsl:stylesheet>
