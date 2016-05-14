<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:m="http://uri.in2isoft.com/onlinepublisher/part/menu/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="m util"
 >

<xsl:template match="m:menu">
    <div>
        <xsl:attribute name="class">
            <xsl:text>part_menu</xsl:text>
            <xsl:if test="@variant!=''">
                <xsl:text> part_menu-</xsl:text><xsl:value-of select="@variant"/>
            </xsl:if>
        </xsl:attribute>
        <xsl:apply-templates/>
    </div>
</xsl:template>

<xsl:template match="m:header">
    <h2 class="part_menu_header"><xsl:value-of select="."/></h2>
</xsl:template>

<xsl:template match="m:items">
    <ul>
        <xsl:attribute name="class">
            <xsl:text>part_menu_level</xsl:text>
            <xsl:if test="../@variant!=''">
                <xsl:text> part_menu_level-</xsl:text><xsl:value-of select="../@variant"/>
            </xsl:if>
        </xsl:attribute>
        <xsl:apply-templates/>
    </ul>
</xsl:template>

<xsl:template match="m:item">
  <xsl:variable name="variant" select="../../@variant" />
  <li class="part_menu_item">
    <xsl:attribute name="class">
      <xsl:text>part_menu_item</xsl:text>
      <xsl:if test="$variant!=''">
        <xsl:text> part_menu_item-</xsl:text>
        <xsl:value-of select="$variant"/>
      </xsl:if>
			<xsl:if test="//p:page/@id=@page">
        <xsl:text> part_menu_item-selected</xsl:text>
        <xsl:if test="$variant!=''">
          <xsl:text> part_menu_item-</xsl:text>
          <xsl:value-of select="$variant"/>
          <xsl:text>-selected</xsl:text>
        </xsl:if>
      </xsl:if>
    </xsl:attribute>
    <a>
      <xsl:attribute name="class">
        <xsl:text>part_menu_link</xsl:text>
        <xsl:if test="$variant!=''">
          <xsl:text> part_menu_link-</xsl:text>
          <xsl:value-of select="$variant"/>
        </xsl:if>
				<xsl:if test="//p:page/@id=@page">
          <xsl:text> part_menu_link-selected</xsl:text>
          <xsl:if test="$variant!=''">
            <xsl:text> part_menu_link-</xsl:text>
            <xsl:value-of select="$variant"/>
            <xsl:text>-selected</xsl:text>
          </xsl:if>
        </xsl:if>
        <xsl:if test="position()=1">
          <xsl:text> part_menu_link-first</xsl:text>
          <xsl:if test="$variant!=''">
            <xsl:text> part_menu_link-</xsl:text>
            <xsl:value-of select="$variant"/>
            <xsl:text>-first</xsl:text>
          </xsl:if>
        </xsl:if>
        <xsl:if test="position()=count(../m:item)">
          <xsl:text> part_menu_link-last</xsl:text>
          <xsl:if test="$variant!=''">
            <xsl:text> part_menu_link-</xsl:text>
            <xsl:value-of select="$variant"/>
            <xsl:text>-last</xsl:text>
          </xsl:if>
        </xsl:if>
      </xsl:attribute>
      <xsl:call-template name="util:link"/>
      <span class="part_menu_link_text">
        <xsl:value-of select="@title"/>
      </span>
    </a>
    <xsl:if test="m:item">
      <ul class="part_menu_level part_menu_level_sub">
        <xsl:apply-templates/>
      </ul>
    </xsl:if>
  </li>
</xsl:template>

</xsl:stylesheet>