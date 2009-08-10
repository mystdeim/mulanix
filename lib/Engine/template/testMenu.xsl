<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="testMenu">
        <p>
            <a href="/">/</a>
            <a href="/ru">ru</a>
            <a href="/admin">admin</a>
            <a href="/ru/admin">ru/admin</a>
        </p>
    </xsl:template>
</xsl:stylesheet>