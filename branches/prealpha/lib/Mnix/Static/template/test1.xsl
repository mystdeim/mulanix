<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
	<xsl:template match="page">
		<p>Page test1:</p>
		<xsl:apply-templates select="texttag" />
	</xsl:template>

	<xsl:template match="texttag">
		<h4>
			<xsl:value-of select="text1" />
		</h4>
		<h4>
			<xsl:value-of select="text2" />
		</h4>
		<h4>
			<xsl:value-of select="text3" />
		</h4>
	</xsl:template>
</xsl:stylesheet>