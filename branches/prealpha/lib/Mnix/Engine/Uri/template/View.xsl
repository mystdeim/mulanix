<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="Mnix_Engine_Uri_View">
        <ul class="uri_tree">
            <xsl:apply-templates select="uri[@parent=0]" />
        </ul>
    </xsl:template>

    <xsl:template match="uri">
        <xsl:variable name="id" select="@id"/>
        <li>
            <h3>
                url: "
                <xsl:value-of select="@name"/>" -> page: "
                <xsl:value-of select="page/@name"/>"
                <p>
                    <ul class="uri_region">
                        <xsl:apply-templates select="page/region" />
                    </ul>
                </p>
            </h3>
            <xsl:if test="../uri[@parent=$id]">
                <ul class="uri_tree">
                    <xsl:apply-templates select="../uri[@parent=$id]"/>
                </ul>
            </xsl:if>
        </li>

    </xsl:template>

    <xsl:template match="region">
        <li>
            <xsl:value-of select="@name"/>
            <ul class="uri_template">
                <xsl:apply-templates select="template" />
            </ul>
        </li>
    </xsl:template>

    <xsl:template match="template">
        <li>
            <xsl:value-of select="@name"/>
        </li>
    </xsl:template>

</xsl:stylesheet>