<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
	<xsl:template match="page">
		<p>Page :</p>
		<xsl:apply-templates select="texttag" />
	</xsl:template>

	<xsl:template match="texttag">
		<h1>
			<xsl:value-of select="text1" />
		</h1>
		<h1>
			<xsl:value-of select="text2" />
		</h1>
		<h1>
			<xsl:value-of select="text3" />
		</h1>
	</xsl:template>
</xsl:stylesheet>