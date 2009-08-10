<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" indent="yes"
		doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
		doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" />
    <xsl:template match="root">
        <html>
            <head>
                <link href="/Mulanix/theme/Default/css/main.css" rel="stylesheet" type="text/css" />
                <title>
                    <xsl:apply-templates select="head/title" />
                </title>
            </head>
            <body>
                <div>
                    <xsl:apply-templates select="body/left" />
                </div>
                <div>
                    <xsl:apply-templates select="body/main" />
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>