<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:m="http://uri.in2isoft.com/onlinepublisher/part/menu/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="m util"
 >

<xsl:template match="m:menu">
    <div>
        <xsl:attribute name="class">
            <xsl:text>part_menu</xsl:text>
            <xsl:if test="@variant!=''">
                <xsl:text> part_menu_</xsl:text><xsl:value-of select="@variant"/>
            </xsl:if>
        </xsl:attribute>
        <xsl:apply-templates/>
    </div>
</xsl:template>

<xsl:template match="m:items">
    <ul class="part_menu_level"><xsl:apply-templates/></ul>
</xsl:template>

<xsl:template match="m:item">
    <li class="part_menu_item">
        <a class="part_menu_link">
            <xsl:call-template name="util:link"/>
            <span class="part_menu_link_text">
                <xsl:value-of select="@title"/>
            </span>
        </a>
        <xsl:if test="m:item">
            <ul class="part_menu_level">
                <xsl:apply-templates/>
            </ul>
        </xsl:if>
    </li>
</xsl:template>

</xsl:stylesheet>