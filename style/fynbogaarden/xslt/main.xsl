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
 xmlns:hr="http://uri.in2isoft.com/onlinepublisher/part/horizontalrule/1.0/"
 xmlns:widget="http://uri.in2isoft.com/onlinepublisher/part/widget/1.0/"
 exclude-result-prefixes="p f h n o util hr widget"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>

<xsl:include href="../../basic/xslt/util.xsl"/>


<xsl:template match="p:page">
  <xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html&gt;
</xsl:text>
<html>
  <xsl:call-template name="util:html-attributes"/>
  <head>
    <title>
      <xsl:if test="not(//p:page/@id=//p:context/p:home/@page)"> 
        <xsl:value-of select="@title"/>
        <xsl:text> - </xsl:text>
      </xsl:if>
      <xsl:value-of select="f:frame/@title"/>
    </title>
    <meta name="viewport" content="user-scalable=yes, initial-scale = 1, maximum-scale = 10, minimum-scale = 0.2"/>
    <xsl:call-template name="util:metatags"/>
    <meta property="og:image" content="{$absolute-path}{$timestamp-url}style/karenslyst/gfx/front.jpg" />
    <xsl:call-template name="util:style-inline">
      <xsl:with-param name="compiled">form,p{margin:0}img{border:none}body{padding:0;margin:0;font-family:Georgia,"Book Antiqua","Palatino",Times,serif}body.font_lora{font-family:"Lora",Georgia,"Book Antiqua","Palatino",Times,serif}a{word-break:break-word}.common_link{color:#eee;color:rgba(0,0,0,0.1)}.common_link_text{color:#ff3400}.header{background:url(<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/fynbogaarden/gfx/header.jpg) 50% 50%;background:url(<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/fynbogaarden/gfx/header_straight.jpg) 50% 50%;background:url(<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/fynbogaarden/gfx/top.jpg) 50% 50%;background-size:cover;padding-bottom:43.88021%;position:relative}.header_title{position:absolute;width:50%;top:0;margin-top:2%;left:2%;font-weight:bold;padding-top:12.02703%;font-size:0;background:url(<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/fynbogaarden/gfx/logo.outline.svg) no-repeat;background-size:contain}.header_title_more{display:block;font-style:italic;font-size:18px;font-weight:normal;color:#fff;text-shadow:0 2px 4px #4C1A1E;text-align:center}@media screen and (min-width:500px){.header_title{width:250px;padding-top:60.13514px}.header_title_more{font-size:20px}.header{padding-bottom:43.88021%}}@media screen and (min-width:700px){.header_title_more{font-size:24px}}@media screen and (min-width:1000px){.header_title{left:50%;margin-left:-480px;width:25%;padding-top:6.01351%}.header_info_phone_number{font-size:40px}}.layout_content{max-width:960px;margin:20px auto;padding:0 20px;overflow:hidden}.layout_bottom{text-align:center;padding:60px 0}.layout_humanise{text-decoration:none;font-style:italic;color:#666}.language{text-align:right;position:absolute;top:0;left:0;right:10px;padding-top:43.88021%;font-size:0;margin-top:-40px}.language_link{font-size:14px;color:#635648;text-decoration:none;white-space:nowrap;margin-left:10px;display:inline-block;background:#fff;background:rgba(255,255,255,0.8);border-radius:15px;padding-right:10px;line-height:30px}.language_link:before{content:'';display:inline-block;width:30px;height:30px;vertical-align:bottom;margin-right:5px}.language_link-english:before{background:url(<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/fynbogaarden/gfx/uk.op.svg)}.language_link-danish:before{background:url(<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/fynbogaarden/gfx/dk.op.svg)}.language_link-german:before{background:url(<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/fynbogaarden/gfx/de.op.svg)}@media screen and (min-width:769px){.language{position:static;padding-top:0;margin:0;white-space:nowrap}.language_link{font-size:24px;border-radius:0;line-height:40px;padding:0}.language_link:before{width:40px;height:40px}}@media screen and (max-width:700px){div.document_column{display:block}.document_column{width:auto!important;padding-left:0!important;padding-right:0!important}div.document_row{display:block}div.document_row_body{display:block}div.document_row_container{margin:0!important}.part_image_image{max-width:100%;height:auto}}@media screen and (min-width:700px){.document_row{display:table;width:100%}div.document_row_body{display:table-row}div.document_column{display:table-cell;vertical-align:top}div.document_row_container{margin:-10px}}</xsl:with-param>
    </xsl:call-template>
    <xsl:if test="//p:page/@id=//p:context/p:home/@page">
      <xsl:call-template name="util:style-inline">
          <xsl:with-param name="file" select="'inline_front.css'"/>
            <xsl:with-param name="compiled">.layout_top{padding-bottom:50%;overflow:hidden;position:relative;-webkit-transform:translate3d(0,0,0)}.layout_top_body{top:0;position:absolute;background:50% 50% no-repeat;background-image:url(data:image/jpeg;base64,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);background-size:cover;height:110%;width:110%;-webkit-transform:scale(1.4)}.layout_top_body-loaded{-webkit-transform:scale(1);background-image:url(<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/karenslyst/gfx/front.jpg);-webkit-animation-name:effect;-webkit-animation-duration:10s;-webkit-animation-iteration-count:1;-webkit-animation-timing-function:ease-out}.title{font-family:'Playfair Display',serif;font-weight:normal;color:#fff;font-size:52pt;margin:0;position:absolute;width:960px;left:50%;margin-left:-480px;top:10px;z-index:2;line-height:1;text-rendering:geometricPrecision}.title_more{font-style:italic;font-size:.5em;font-weight:normal}@-webkit-keyframes effect{0%{-webkit-transform:scale(1.4)}100%{-webkit-transform:scale(1)}}@media only screen and (max-width:1000px){.title{margin-left:0;left:20px}}@media only screen and (max-width:768px){.title{font-size:32pt;margin-left:0;left:20px}}@media only screen and (max-width:500px){.layout_top{padding-bottom:70%}.title{text-align:center;width:auto;font-size:34pt;line-height:1;right:0;left:0;top:0}}</xsl:with-param>
        </xsl:call-template>
    </xsl:if>
    <xsl:call-template name="util:scripts-build"/>

    <xsl:call-template name="util:load-font">
      <xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic'"/>
      <xsl:with-param name="family" select="'Lora'"/>
      <xsl:with-param name="class" select="'font_lora'"/>
    </xsl:call-template>
      
    <xsl:call-template name="util:style-build">
      <xsl:with-param name="async" select="'false'"/>
    </xsl:call-template>
    <xsl:call-template name="util:style-lt-ie9"/>
  </head>
  <body>
    <div class="layout">
      <xsl:call-template name="header"/>
      <main class="layout_middle">
        <xsl:apply-templates select="p:content"/>
        <xsl:call-template name="util:userstatus"/>
      </main>
      <footer class="layout_bottom">
        <p><a href="http://www.humanise.dk/" class="layout_humanise" title="Humanise">Designet og udviklet af Humanise</a></p>
      </footer>
    </div>
    <xsl:call-template name="util:googleanalytics"/>
  </body>
</html>
</xsl:template>

<xsl:template name="header">
  <header class="header">
    <p class="header_title">Fynbogaarden <span class="header_title_more"> Bed &amp; Breakfast</span></p>
  </header>
  <!--
  <xsl:choose>
    <xsl:when test="//p:page/@id=//p:context/p:home/@page">

    </xsl:when>
    <xsl:otherwise>
    </xsl:otherwise>
  </xsl:choose>
  -->
</xsl:template>

<xsl:template match="p:content">
  <div class="layout_content">
    <xsl:apply-templates/>
    <xsl:comment/>
  </div>
</xsl:template>

<!--
<language>
  <english>Engelsk</english>
</language>
-->
<xsl:template match="widget:language">
  <div class="language">
    <xsl:if test="$language!='en' and //p:page/p:context/p:translation[@language='en']">
      <a href="{//p:page/p:context/p:translation[@language='en']/@path}" class="language_link language_link-english">English</a>
    </xsl:if>
    <xsl:if test="$language!='da' and //p:page/p:context/p:translation[@language='da']">
      <xsl:text> </xsl:text>
      <a href="{//p:page/p:context/p:translation[@language='da']/@path}" class="language_link language_link-danish">Dansk</a>
    </xsl:if>
    <xsl:if test="$language!='de' and //p:page/p:context/p:translation[@language='de']">
      <xsl:text> </xsl:text>
      <a href="{//p:page/p:context/p:translation[@language='de']/@path}" class="language_link language_link-german">Deutch</a>      
    </xsl:if>
  </div>
</xsl:template>


<xsl:template match="widget:contact">
  <div class="contact">
    <p class="contact_phone"><span class="contact_phone_prefix"><xsl:value-of select="widget:phone/@prefix"/></span><xsl:text> </xsl:text>
      <a href="tel:{widget:phone/@prefix}{widget:phone}" class="contact_phone_link"><xsl:value-of select="widget:phone"/></a></p>
    <p class="contact_mail"><xsl:value-of select="widget:email/@prefix"/>: <a href="mailto:{widget:email}" class="contact_mail_link"><xsl:value-of select="widget:email"/></a></p>
  </div>
</xsl:template>


<xsl:template match="widget:poster">
  <div class="poster poster-{@variant}">
    <div class="poster_body poster_body-{@variant}"><xsl:comment/></div>
  </div>
</xsl:template>

<xsl:template match="widget:quote">
  <blockquote class="quote">
    <div class="quote_body">
    <xsl:copy-of select="document('../gfx/curl_box_top.min.svg')" />
    <p class="quote_text"><xsl:apply-templates select="widget:text"/> <span class="quote_name">– <xsl:value-of select="widget:name"/></span></p>
    <xsl:copy-of select="document('../gfx/curl_box_bottom.min.svg')" />
    </div>
  </blockquote>
</xsl:template>

<xsl:template match="widget:quote/widget:text/widget:strong">
  <strong><xsl:apply-templates/></strong>
</xsl:template>

</xsl:stylesheet>