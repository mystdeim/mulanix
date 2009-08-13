<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" indent="yes"
		doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
		doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" />
    <xsl:template match="root">
        <html>
            <head>
                <link href="/theme/Default/css/main.css" rel="stylesheet" type="text/css" />
                <title>
                    <xsl:apply-templates select="head/title" />
                </title>
            </head>
            <body>
                <div id="top">
                    <xsl:apply-templates select="body/top" />
                    <hr />
                </div>
                <div id="left">
                    <xsl:apply-templates select="body/left" />
                </div>
                <div id="main">
                    <xsl:apply-templates select="body/main" />
                </div>
                <div id="bottom">
                    <hr />
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>