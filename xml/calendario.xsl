<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" indent="yes" />
    <xsl:param name="temporadaSeleccionada" />
    <xsl:param name="jornadaSeleccionada" select="1" />

    <xsl:template match="/">
        <div class="contenedor-calendario">
            <h1>Calendario</h1>

            <!-- Selector de temporadas -->
            <div class="temporada-jornada">
                <!-- Selector de jornadas -->
                <xsl:choose>
                    <xsl:when test="count(temporadas/temporada[nombre = $temporadaSeleccionada or (estado = 'Activa' and not($temporadaSeleccionada))]/jornadas/jornada) > 0">
                        <div class="jornada-selector">
                            <form method="POST" action="calendario">
                                <label for="jornadaSelect">Selecciona una jornada: </label>
                                <select id="jornadaSelect" name="jornada" onchange="this.form.submit()">
                                    <xsl:for-each select="temporadas/temporada[nombre = $temporadaSeleccionada or (estado = 'Activa' and not($temporadaSeleccionada))]/jornadas/jornada">
                                        <option value="{numero}">
                                            <xsl:if test="numero = $jornadaSeleccionada">
                                                <xsl:attribute name="selected">selected</xsl:attribute>
                                            </xsl:if>
                                            Jornada <xsl:value-of select="numero" />
                                        </option>
                                    </xsl:for-each>
                                </select>
                            </form>
                        </div>
                    </xsl:when>
                    <xsl:otherwise>
                        <p>No se han encontrado datos para la temporada seleccionada.</p>
                    </xsl:otherwise>
                </xsl:choose>
            </div>

            <!-- Lista de partidos -->
            <xsl:for-each select="temporadas/temporada[nombre = $temporadaSeleccionada or (estado = 'Activa' and not($temporadaSeleccionada))]/jornadas/jornada[numero = $jornadaSeleccionada]/partidos/partido">
                <div class="partido">
                    <div class="detalles-partido">
                        <p><strong>Fecha: </strong> 
                            <xsl:value-of select="fecha" />
                        </p>
                        <p><strong>Hora: </strong> 
                            <xsl:value-of select="hora" />
                        </p>
                        <p><strong>Estadio: </strong>
                            <xsl:value-of select="/temporadas/temporada[nombre = $temporadaSeleccionada]/equipos/equipo[nombre = current()/equipo1]/estadio" />
                        </p>
                    </div>
                    <div class="calendario-equipos">
                        <form method="POST" action="equipos" class="form-equipo">
                            <input type="hidden" name="equipo" value="{equipo1}" />
                            <button type="submit" class="enlace-clasificacion">
                                <img src="{rutaImagenEquipo1}" alt="{equipo1}" />
                            </button>
                        </form>
                        <form method="POST" action="equipos" class="form-equipo">
                            <input type="hidden" name="equipo" value="{equipo1}" />
                            <button type="submit" class="enlace-clasificacion">
                                <p><strong class="partido-equipos"><xsl:value-of select="equipo1" /></strong></p>
                            </button>
                        </form>
                        <p><strong class="partido-equipos">Vs.</strong></p>
                        <form method="POST" action="equipos" class="form-equipo">
                            <input type="hidden" name="equipo" value="{equipo2}" />
                            <button type="submit" class="enlace-clasificacion">
                                <p><strong class="partido-equipos"><xsl:value-of select="equipo2" /></strong></p>
                            </button>
                        </form>
                        <form method="POST" action="equipos" class="form-equipo">
                            <input type="hidden" name="equipo" value="{equipo2}" />
                            <button type="submit" class="enlace-clasificacion">
                                <img src="{rutaImagenEquipo2}" alt="{equipo2}" />
                            </button>
                        </form>
                    </div>
                </div>
            </xsl:for-each>
        </div>
    </xsl:template>
</xsl:stylesheet>
