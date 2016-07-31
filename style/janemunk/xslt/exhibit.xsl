<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 xmlns:widget="http://uri.in2isoft.com/onlinepublisher/part/widget/1.0/"
 xmlns:document="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/"
 xmlns:imagegallery="http://uri.in2isoft.com/onlinepublisher/part/imagegallery/1.0/"
 xmlns:image="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 exclude-result-prefixes="p o util widget document imagegallery image"
 >

<xsl:template match="widget:exhibition">
  <xsl:choose>
    <xsl:when test="$editor='true'">
      <div>Exhibit</div>
      <xsl:for-each select="widget:page">
        <hr/>
        <xsl:for-each select="widget:image">
          <div>#<xsl:value-of select="@id"/>: <xsl:value-of select="@width"/>x<xsl:value-of select="@height"/></div>
        </xsl:for-each>
      </xsl:for-each>
    </xsl:when>
    <xsl:otherwise>
    <div class="exhibit_wall">
      <div class="exhibit_paintings">
        <xsl:for-each select="widget:page">
        <div class="exhibit_paintings_page">
          <xsl:for-each select="widget:image">
            <div class="exhibit_painting exhibit_painting-left">
              <xsl:attribute name="class">
                <xsl:text>exhibit_painting </xsl:text>
                <xsl:choose>
                  <xsl:when test="position()=1">exhibit_painting-left</xsl:when>
                  <xsl:when test="position()=2">exhibit_painting-center</xsl:when>
                  <xsl:otherwise>exhibit_painting-right</xsl:otherwise>
                </xsl:choose>
              </xsl:attribute>
              <xsl:attribute name="style">
                <xsl:text>background-image: url('</xsl:text>
                <xsl:value-of select="$path"/>
                <xsl:text>services/images/?id=</xsl:text>
                <xsl:value-of select="@id"/>
                <xsl:text>&amp;width=311&amp;background=transparent&amp;format=png&amp;sharpen=0.7&amp;nocache=ztrue');</xsl:text>
              </xsl:attribute>
              <xsl:attribute name="data-id"><xsl:value-of select="@id"/></xsl:attribute>
              <xsl:attribute name="data-width"><xsl:value-of select="@width"/></xsl:attribute>
              <xsl:attribute name="data-height"><xsl:value-of select="@height"/></xsl:attribute>
              <xsl:attribute name="data-full">
                <xsl:value-of select="$path"/>
                <xsl:text>services/images/?id=</xsl:text>
                <xsl:value-of select="@id"/>
                <xsl:text>&amp;width=1000&amp;background=transparent&amp;format=png</xsl:text>
              </xsl:attribute>
              <xsl:comment/>
            </div>
          </xsl:for-each>
          <xsl:comment/>
        </div>
        </xsl:for-each>
      </div>
    </div>
    <div class="exhibit_viewer js-viewer"><div class="exhibit_viewer_inner js-viewer-inner"><xsl:comment/></div></div>
    <div class="exhibit_spaces exhibit_control js-spaces">
      <a href="#" class="exhibit_spaces_option js-spaces-option" data="light">Lys</a>
      <a href="#" class="exhibit_spaces_option js-spaces-option is-selected" data="concrete">Beton</a>
      <a href="#" class="exhibit_spaces_option js-spaces-option" data="simple">Simpel</a>
    </div>
    <a class="exhibit_back exhibit_control">
      <xsl:attribute name="href">
        <xsl:call-template name="util:link-url">
          <xsl:with-param name="node" select="//p:page/p:context/p:home"/>
        </xsl:call-template>
      </xsl:attribute>Tilbage</a>
    <script src="{$path}{$timestamp-url}style/janemunk/js/hammer.min.js{$timestamp-query}"><xsl:comment/></script>
    <script src="{$path}{$timestamp-url}style/janemunk/js/exhibit.js{$timestamp-query}"><xsl:comment/></script>
  </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template match="document:section[@class='artgallery']//imagegallery:imagegallery">
  <div class="exhibit_wall">
    <div class="exhibit_paintings">
    <xsl:for-each select="//o:object[position() mod 3 = 1]">
      <div class="exhibit_paintings_page">
      <xsl:for-each select=". | following-sibling::o:object[position() &lt; 3]">
        <div class="exhibit_painting exhibit_painting-left">
          <xsl:attribute name="class">
            <xsl:text>exhibit_painting </xsl:text>
            <xsl:choose>
              <xsl:when test="position()=1">exhibit_painting-left</xsl:when>
              <xsl:when test="position()=2">exhibit_painting-center</xsl:when>
              <xsl:otherwise>exhibit_painting-right</xsl:otherwise>
            </xsl:choose>
          </xsl:attribute>
          <xsl:attribute name="style">
            <xsl:text>background-image: url('</xsl:text>
            <xsl:value-of select="$path"/>
            <xsl:text>services/images/?id=</xsl:text>
            <xsl:value-of select="@id"/>
            <xsl:text>&amp;width=311&amp;background=transparent&amp;format=png&amp;sharpen=0.7&amp;nocache=ztrue');</xsl:text>
          </xsl:attribute>
          <xsl:attribute name="data-id"><xsl:value-of select="@id"/></xsl:attribute>
          <xsl:attribute name="data-full">
            <xsl:value-of select="$path"/>
            <xsl:text>services/images/?id=</xsl:text>
            <xsl:value-of select="@id"/>
            <xsl:text>&amp;width=1000&amp;background=transparent&amp;format=png</xsl:text>
          </xsl:attribute>
          <xsl:comment/>
        </div>
      </xsl:for-each>
      </div>
    </xsl:for-each>
    </div>
  </div>
  <div class="exhibit_viewer js-viewer"><div class="exhibit_viewer_inner js-viewer-inner"><xsl:comment/></div></div>
  <div class="exhibit_spaces exhibit_control js-spaces">
    <a href="#" class="exhibit_spaces_option js-spaces-option" data="light">Lys</a>
    <a href="#" class="exhibit_spaces_option js-spaces-option is-selected" data="concrete">Beton</a>
    <a href="#" class="exhibit_spaces_option js-spaces-option" data="simple">Simpel</a>
  </div>
  <a class="exhibit_back exhibit_control">
    <xsl:attribute name="href">
      <xsl:call-template name="util:link-url">
        <xsl:with-param name="node" select="//p:page/p:context/p:home"/>
      </xsl:call-template>
    </xsl:attribute>Tilbage</a>
  <script src="{$path}{$timestamp-url}style/{$design}/js/hammer.min.js{$timestamp-query}"><xsl:comment/></script>
  <script src="{$path}{$timestamp-url}style/{$design}/js/exhibit.js{$timestamp-query}"><xsl:comment/></script>
</xsl:template>

</xsl:stylesheet>