<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">
    <xsl:output method="xml" indent="yes"
		doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
		doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" />
    <xsl:template match="root">
        <html>
            <head>
                <link href="/Mulanix/theme/default/css/main.css" rel="stylesheet" type="text/css" />
                <title>
                    <xsl:apply-templates select="page/title" />
                </title>
            </head>
            <body>
                <div style="float: left">
                    <xsl:apply-templates select="main" />
                </div>
                <div style="float: right">
                    <xsl:apply-templates select="left" />
                </div>
                <div style="clear: right">
                    <hr />
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>