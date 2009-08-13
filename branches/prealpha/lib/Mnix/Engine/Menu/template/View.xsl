<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="Mnix_Engine_Menu_View">
        <p>AdminMenu</p>
        <ul>
            <xsl:apply-templates select="menu" />
        </ul>
    </xsl:template>
    <xsl:template match="menu">
        <li>
            <a href="/{@link}">
            <xsl:value-of select="@name" />
            </a>
        </li>
    </xsl:template>
</xsl:stylesheet>