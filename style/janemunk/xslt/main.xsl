<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 xmlns:widget="http://uri.in2isoft.com/onlinepublisher/part/widget/1.0/"
 exclude-result-prefixes="p f h n o util widget"
 >
<xsl:output encoding="UTF-8" method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:include href="../../basic/xslt/util.xsl"/>


<xsl:template match="p:page">
  <html>
    <xsl:call-template name="util:html-attributes"/>
    <head>
      <title><xsl:value-of select="@title"/> - <xsl:value-of select="f:frame/@title"/></title>
      <meta name="viewport" content="user-scalable=yes, initial-scale = 1, maximum-scale = 10, minimum-scale = 0.2"/>
      <xsl:call-template name="util:metatags"/>
      <xsl:call-template name="util:style"/>
      <xsl:call-template name="util:style-ie6"/>
      <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,600|Amatic+SC|Poiret+One' rel='stylesheet' type='text/css'/>
      <xsl:call-template name="util:scripts"/>
    </head>
    <body>
      <xsl:choose>
        <xsl:when test="//widget:exhibition">
          <xsl:attribute name="class">exhibit exhibit-concrete</xsl:attribute>
          <xsl:apply-templates select="//widget:widget"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:call-template name="new"/>          
        </xsl:otherwise>
      </xsl:choose>
      <xsl:call-template name="util:googleanalytics"/>
    </body>
  </html>
</xsl:template>

<xsl:template name="new">

  <xsl:choose>
    <xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id]">
      <div class="layout_navigation is-front">
        <h1 class="layout_title is-front">Jane Munk</h1>
        <ul class="layout_menu is-front">
          <xsl:for-each select="f:frame/h:hierarchy/h:item[not(@hidden='true')]">
            <xsl:variable name="style">
              <xsl:choose>
                <xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
                <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
                <xsl:otherwise><xsl:text>normal</xsl:text></xsl:otherwise>
              </xsl:choose>
            </xsl:variable>
            <li class="layout_menu_item layout_menu_item-{$style} is-front">
              <a class="layout_menu_link layout_menu_link-{$style} is-front">
                <xsl:call-template name="util:link"/>
                <xsl:value-of select="@title"/>
              </a>
            </li>
          </xsl:for-each>
        </ul>
      </div>
      <div class="painting painting-1"><div class="painting_body painting_body-1"><xsl:comment/></div></div>
      <div class="painting painting-2"><div class="painting_body painting_body-2"><xsl:comment/></div></div>
      <div class="painting painting-3"><div class="painting_body painting_body-3"><xsl:comment/></div></div>
      <div class="painting painting-4"><div class="painting_body painting_body-4"><xsl:comment/></div></div>
    </xsl:when>
    <xsl:otherwise>
      <div class="layout">
        <div class="layout_navigation">
          <p class="layout_title">Jane Munk</p>
          <ul class="layout_menu">
            <xsl:for-each select="f:frame/h:hierarchy/h:item[not(@hidden='true')]">
              <xsl:variable name="style">
                <xsl:choose>
                  <xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
                  <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
                  <xsl:otherwise><xsl:text>normal</xsl:text></xsl:otherwise>
                </xsl:choose>
              </xsl:variable>
              <li class="layout_menu_item layout_menu_item-{$style}">
                <a class="layout_menu_link layout_menu_link-{$style}">
                  <xsl:call-template name="util:link"/>
                  <xsl:value-of select="@title"/>
                </a>
              </li>
            </xsl:for-each>
          </ul>
        </div>
        <div class="layout_content">
          <xsl:apply-templates select="p:content/*"/>
        </div>
      </div>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="legacy">
  <div class="case">
    <xsl:choose>
    <xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id]">
      <div class="case_front">
        <div id="paintings" class="easel">
          <div class="easel_painting easel_painting-1"><xsl:comment/></div>
          <div class="easel_painting easel_painting-2"><xsl:comment/></div>
          <div class="easel_painting easel_painting-3"><xsl:comment/></div>
          <div class="easel_painting easel_painting-4"><xsl:comment/></div>
          <div class="easel_painting easel_painting-5"><xsl:comment/></div>
          <div class="easel_painting easel_painting-6"><xsl:comment/></div>
          <div class="easel_painting easel_painting-7"><xsl:comment/></div>
          <div class="easel_painting easel_painting-8"><xsl:comment/></div>
          <div class="easel_painting easel_painting-9"><xsl:comment/></div>
        </div>
        <div class="case_front_content">
          <xsl:apply-templates select="p:content"/>
        </div>
      </div>
      <script type="text/javascript">
        new op.Dissolver({elements:hui.get('paintings').getElementsByTagName('div'),wait:3000,transition:1000,delay:0});
      </script>
    </xsl:when>
    <xsl:otherwise>
      <div class="case_head">
        <h1>Jane Munk</h1>
        <ul class="case_navigation"><xsl:apply-templates select="f:frame/h:hierarchy/h:item"/></ul>
      </div>
      <xsl:call-template name="secondlevel"/><xsl:comment/>
      <div class="case_body">
        <div class="case_sidebar">
          <div class="case_contact">
            <h2>Jane Brinkmann Munk</h2>
            <p>Apotekervænget 16, 9370 Hals</p>
            <p><em>Tlf:</em> 98 25 18 83</p>
            <p><em>E-post: </em> <a href="mailto:janemunk@stofanet.dk"><span>janemunk@stofanet.dk</span></a></p>
          </div>
          <xsl:call-template name="thirdlevel"/>
          <xsl:comment/>
        </div>
        <div class="case_sidebar_content">
        <xsl:apply-templates select="p:content"/>
        </div>
      </div>
      <div class="case_footer"><xsl:comment/></div>
    </xsl:otherwise>
    </xsl:choose>
  </div>
</xsl:template>

<xsl:template match="widget:exhibition">
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
  <script src="{$path}{$timestamp-url}style/{$design}/js/hammer.min.js{$timestamp-query}"><xsl:comment/></script>
  <script src="{$path}{$timestamp-url}style/{$design}/js/exhibit.js{$timestamp-query}"><xsl:comment/></script>
</xsl:template>

</xsl:stylesheet>