<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="Mnix_Engine_Uri_View">
        <p>UriView!</p>
        <ul>
            <xsl:apply-templates select="page" />
        </ul>
    </xsl:template>
    <xsl:template match="page">
        <li>
            <xsl:value-of select="@name" />
        </li>
    </xsl:template>
</xsl:stylesheet>