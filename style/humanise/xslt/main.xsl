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
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>
<xsl:include href="../../basic/xslt/util.xsl"/>

<xsl:template match="p:page">
<xsl:call-template name="util:doctype"/>
<html>
  <xsl:call-template name="util:html-attributes"/>
  <head>
    <title>
      <xsl:choose>
        <xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id]">
          <xsl:text>Humanise - </xsl:text>
          <xsl:choose>
            <xsl:when test="//p:page/p:meta/p:language='en'"><xsl:text>Software for humans</xsl:text></xsl:when>
            <xsl:otherwise><xsl:text>Software til mennesker</xsl:text></xsl:otherwise>
          </xsl:choose>
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="@title"/><xsl:text> - </xsl:text><xsl:value-of select="f:frame/@title"/>
        </xsl:otherwise>
      </xsl:choose>
    </title>
    <meta name="viewport" content="user-scalable=yes, initial-scale = 1, maximum-scale = 10, minimum-scale = 0.2"/>
    <link rel="shortcut icon" href="{$path}style/humanise/gfx/favicon.ico" type="image/x-icon" />
    <xsl:call-template name="util:metatags"/>
    <xsl:call-template name="util:js"/>

    <xsl:call-template name="util:css">
      <xsl:with-param name="async" select="'true'"/>
      <xsl:with-param name="inline" select="'true'"/>
      <xsl:with-param name="ie-lt-9" select="'true'"/>
      <xsl:with-param name="ie-lt-8" select="'true'"/>
    </xsl:call-template>

    <xsl:if test="//widget:hero">
      <xsl:call-template name="util:inline-css">
        <xsl:with-param name="file" select="'style/humanise/css/hero.css'"/>
      </xsl:call-template>
    </xsl:if>

    <xsl:call-template name="util:load-font">
      <xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Lato:300,400,700,900'"/>
      <xsl:with-param name="family" select="'Lato'"/>
      <xsl:with-param name="weights" select="'300,400,700'"/>
    </xsl:call-template>
  </head>
  <body>
    <div class="layout">
      <div>
        <xsl:attribute name="class">
          <xsl:text>layout_head</xsl:text>
          <xsl:if test="//widget:hero"><xsl:text> layout_head-hero</xsl:text></xsl:if>
        </xsl:attribute>
        <div class="layout_head_body">
          <ul class="layout_menu">
            <xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
          </ul>
          <xsl:call-template name="search"/>
        </div>
      </div>
      <div class="layout_middle">
        <div class="layout_middle_top">
          <xsl:call-template name="secondlevel"/>
          <xsl:call-template name="util:languages"/>
          <xsl:comment/>
        </div>
        <div class="layout_body">
          <!--
          <xsl:if test="//p:page/@id=//p:context/p:home/@page">
            <div id="poster">
              <div id="poster_loader">0%</div>
              <div id="poster_left"><xsl:comment/></div>
              <div id="poster_right"><xsl:comment/></div>
            </div>
            <script type="text/javascript">require(['Poster'],function() {new Poster();});</script>
          </xsl:if>
            -->
          <xsl:apply-templates select="p:content"/>
        </div>
      </div>

      <div class="layout_base">
        <div class="layout_info">
          <div class="layout_info_about">
            <xsl:choose>
              <xsl:when test="//p:page/p:meta/p:language='en'">
                <h2 class="layout_info_header">About Humanise</h2>
                <p>We focus on user experience and design. We seek out the most simple and essential solution. We believe that machines should work for people. We think that knowledge should be free and accessible to all. We hope you agree :-)</p>
                <p class="layout_info_more">
                  <a href="{$path}om/" class="common_link"><span class="common_link_text">More about Humanise »</span></a>
                </p>
              </xsl:when>
              <xsl:otherwise>
                <xsl:call-template name="util:parameter">
                    <xsl:with-param name="name" select="'about'"/>
                    <xsl:with-param name="default">
                      <h2 class="layout_info_header">Om Humanise</h2>
                      <p>Vores focus er på brugeroplevelse og design. Vi leder altid efter den mest enkle og essentielle løsning. Vi tror på at maskinen skal arbejde for mennesket. Vi mener at viden bør være fri og tilgængelig for alle. Vi håber du er enig :-)</p>
                    </xsl:with-param>
                </xsl:call-template>
                <p class="layout_info_more">
                  <a href="{$path}om/" class="common_link"><span class="common_link_text">Mere om Humanise »</span></a>
                </p>
              </xsl:otherwise>
            </xsl:choose>
          </div>
          <div class="layout_info_contact">
            <xsl:choose>
              <xsl:when test="//p:page/p:meta/p:language='en'"><h2>Contact</h2></xsl:when>
              <xsl:otherwise><h2 class="layout_info_header">Kontakt</h2></xsl:otherwise>
            </xsl:choose>
            <xsl:call-template name="util:parameter">
                <xsl:with-param name="name" select="'contact'"/>
                <xsl:with-param name="default">
                  <p><strong class="layout_info_name">Jonas Brinkmann Munk</strong></p>
                  <p><a class="common_link" href="mailto:jonasmunk@mac.com"><span class="common_link_text">jonasmunk@mac.com</span></a> · <a href="tel:004528776365" class="layout_info_phone">+45 28 77 63 65</a></p>
                  <p><strong class="layout_info_name">Kenni Graversen</strong></p>
                  <p><a class="common_link" href="mailto:gr@versen.dk"><span class="common_link_text">gr@versen.dk</span></a> · <a href="tel:004522486153" class="layout_info_phone">+45 22 48 61 53</a></p>
                </xsl:with-param>
            </xsl:call-template>
          </div>
        </div>
      </div>
    </div>
    <div class="layout_footer">
      <a class="layout_design">Designet og udviklet af Humanise</a>
      <!--
        <xsl:apply-templates select="f:frame/f:text/f:bottom"/>
        <xsl:apply-templates select="f:frame/f:links/f:bottom"/>
        -->
    </div>
    <xsl:call-template name="util:googleanalytics"/>
  </body>
</html>
</xsl:template>

<xsl:template match="p:content">
<div>
  <xsl:choose>
    <xsl:when test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
      <xsl:attribute name="class">layout_content layout_content-sidebar</xsl:attribute>
    </xsl:when>
    <xsl:otherwise>
      <xsl:attribute name="class">layout_content</xsl:attribute>
    </xsl:otherwise>
  </xsl:choose>
  <xsl:if test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
    <div class="layout_sidebar">
      <xsl:call-template name="thirdlevel"/>
      <xsl:apply-templates select="../f:frame/f:newsblock"/>
      <xsl:comment/>
    </div>
  </xsl:if>
  <div>
    <xsl:choose>
      <xsl:when test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
        <xsl:attribute name="class">layout_inner_content layout_inner_content-sidebar</xsl:attribute>
      </xsl:when>
      <xsl:otherwise>
        <xsl:attribute name="class">layout_inner_content</xsl:attribute>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:if test="//p:context/p:translation">
      <p class="layout_translation">
      <xsl:for-each select="//p:context/p:translation">
        <a class="common">
          <xsl:call-template name="util:link"/>
          <xsl:choose>
            <xsl:when test="@language='da'">
              <span><xsl:text>Denne side på dansk</xsl:text></span>
            </xsl:when>
            <xsl:when test="@language='en'">
              <span><xsl:text>This page in english</xsl:text></span>
            </xsl:when>
            <xsl:otherwise>
              <span>This page in <xsl:value-of select="@language"/></span>
            </xsl:otherwise>
          </xsl:choose>
        </a>
      </xsl:for-each>
      </p>
    </xsl:if>
    <xsl:apply-templates/>
    <xsl:comment/>
  </div>
</div>
</xsl:template>


<!--            User status                 -->



<xsl:template match="f:userstatus">
  <xsl:choose>
    <xsl:when test="$userid>0">
      <span class="userstatus">Bruger: <strong><xsl:value-of select="$usertitle"/></strong></span>
      <xsl:text> · </xsl:text>
      <a href="./?id={@page}&amp;logout=true" class="common">Log ud</a>
    </xsl:when>
    <xsl:otherwise>
      <a href="./?id={@page}" class="common">Log ind</a>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>



<xsl:template match="h:hierarchy/h:item">
  <xsl:if test="not(@hidden='true')">
    <li>
      <xsl:attribute name="class">
        <xsl:text>layout_menu_item</xsl:text>
      <xsl:choose>
        <xsl:when test="position()>1 and //p:page/@id=@page"> layout_menu_item-selected</xsl:when>
        <xsl:when test="position()>1 and descendant-or-self::*/@page=//p:page/@id"> layout_menu_item-highlighted</xsl:when>
        <xsl:when test="position()=1"> layout_menu_item-first</xsl:when>
      </xsl:choose>
      </xsl:attribute>
      <a>
      <xsl:attribute name="class">
        <xsl:text>layout_menu_link</xsl:text>
      <xsl:choose>
        <xsl:when test="position()>1 and //p:page/@id=@page"> layout_menu_link-selected</xsl:when>
        <xsl:when test="position()>1 and descendant-or-self::*/@page=//p:page/@id"> layout_menu_link-highlighted</xsl:when>
        <xsl:when test="position()=1"> layout_menu_link-first</xsl:when>
      </xsl:choose>
      </xsl:attribute>
        <xsl:call-template name="util:link"/>
        <span><xsl:value-of select="@title"/></span>
      </a>
    </li>
  </xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
  <xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[not(@hidden='true')]">
    <ul>
      <xsl:attribute name="class">
        <xsl:text>submenu</xsl:text>
      </xsl:attribute>
      <xsl:apply-templates select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
    </ul>
  </xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item">
  <xsl:variable name="class">
    <xsl:text>submenu_link</xsl:text>
    <xsl:choose>
      <xsl:when test="//p:page/@id=@page"><xsl:text> submenu_link-selected</xsl:text></xsl:when>
      <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> submenu_link-highlighted</xsl:text></xsl:when>
    </xsl:choose>
  </xsl:variable>
  <xsl:if test="not(@hidden='true')">
    <li class="submenu_item">
    <a class="{$class}">
      <xsl:call-template name="util:link"/>
      <xsl:value-of select="@title"/>
    </a>
    </li>
  </xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item//h:item[not(@hidden='true')]">
  <xsl:variable name="class">
    <xsl:text>sidemenu_link</xsl:text>
    <xsl:choose>
      <xsl:when test="//p:page/@id=@page"><xsl:text> sidemenu_link-selected</xsl:text></xsl:when>
      <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> sidemenu_link-highlighted</xsl:text></xsl:when>
    </xsl:choose>
  </xsl:variable>
  <li class="sidemenu_item">
    <a class="{$class}">
      <xsl:call-template name="util:link"/>
      <xsl:value-of select="@title"/>
    </a>
    <xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
      <ul class="sidemenu_sub"><xsl:apply-templates/></ul>
    </xsl:if>
  </li>
</xsl:template>

<xsl:template name="thirdlevel">
<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
  <ul>
    <xsl:attribute name="class">
      <xsl:text>sidemenu</xsl:text>
    </xsl:attribute>
    <xsl:apply-templates select="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
  </ul>
</xsl:if>
</xsl:template>

<xsl:template match="h:item">
  <xsl:variable name="style">
    <xsl:choose>
      <xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
      <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
      <xsl:otherwise>standard</xsl:otherwise>
    </xsl:choose>
  </xsl:variable>
  <xsl:if test="not(@hidden='true')">
    <li>
      <a class="{$style}">
        <xsl:call-template name="util:link"/>
        <span><xsl:value-of select="@title"/></span>
      </a>
    <xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
      <ul><xsl:apply-templates/></ul>
    </xsl:if>
    </li>
  </xsl:if>
</xsl:template>





<!--            Links              -->


<xsl:template match="f:links/f:top">
  <div class="links_top">
    <div>
      <xsl:apply-templates select="//f:frame/f:userstatus"/> ·
      <a title="Udskriv siden" class="common" href="?id={//p:page/@id}&amp;print=true">Udskriv</a>
      <xsl:apply-templates/>
    </div>
  </div>
</xsl:template>

<xsl:template match="f:links/f:bottom">
  <div class="layout_links">
    <xsl:apply-templates/>
    <xsl:if test="f:link"><span>&#160;&#183;&#160;</span></xsl:if>
    <a title="XHTML 1.1" class="common" href="http://validator.w3.org/check?uri=referer"><span>XHTML 1.1</span></a>
  </div>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
  <xsl:if test="position()>1"><span>&#160;&#183;&#160;</span></xsl:if>
  <a title="{@alternative}" class="common">
    <xsl:call-template name="util:link"/>
    <span><xsl:value-of select="@title"/></span>
  </a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
  <span>&#160;&#183;&#160;</span>
  <a title="{@alternative}" class="common">
    <xsl:call-template name="util:link"/>
    <span><xsl:value-of select="@title"/></span>
  </a>
</xsl:template>



<!--            Text              -->





<xsl:template match="f:text/f:bottom">
  <span class="text">
    <xsl:comment/>
  <xsl:apply-templates/>
  </span>
</xsl:template>

<xsl:template match="f:text/f:bottom/f:break">
  <br/>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
  <a title="{@alternative}" class="common">
    <xsl:call-template name="util:link"/>
    <span><xsl:apply-templates/></span>
  </a>
</xsl:template>




<!--            News              -->





<xsl:template match="f:newsblock">
  <div class="layout_news">
    <h2><xsl:value-of select="@title"/></h2>
    <xsl:apply-templates/>
  </div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
  <div class="layout_news_item">
    <h3><xsl:value-of select="o:title"/></h3>
    <p class="layout_news_text">
      <xsl:apply-templates select="o:note"/>
    </p>
    <xsl:apply-templates select="o:sub/n:news/n:startdate"/>
    <xsl:apply-templates select="o:links"/>
  </div>
</xsl:template>

<xsl:template match="f:newsblock//o:links">
  <p class="layout_news_links">
    <xsl:apply-templates/>
  </p>
</xsl:template>

<xsl:template match="f:newsblock//o:note">
  <xsl:apply-templates/>
</xsl:template>

<xsl:template match="f:newsblock//o:break">
  <br/>
</xsl:template>

<xsl:template match="f:newsblock//n:startdate">
  <p class="layout_news_date"> <xsl:value-of select="@day"/>/<xsl:value-of select="@month"/><!--/<xsl:value-of select="substring(@year,3,2)"/>--></p>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
  <xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
  <a title="{@alternative}" class="common">
    <xsl:call-template name="util:link"/>
    <span>
      <xsl:value-of select="@title"/>
    </span>
  </a>
</xsl:template>



<!--                  Search                     -->


<xsl:template name="search">
  <xsl:if test="f:frame/f:search">
    <form action="{$path}" method="get" class="layout_search" accept-charset="UTF-8">
      <svg class="layout_search_icon" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M19.129,18.164l-4.518-4.52c1.152-1.373,1.852-3.143,1.852-5.077c0-4.361-3.535-7.896-7.896-7.896 c-4.361,0-7.896,3.535-7.896,7.896s3.535,7.896,7.896,7.896c1.934,0,3.705-0.698,5.078-1.853l4.52,4.519 c0.266,0.268,0.699,0.268,0.965,0C19.396,18.863,19.396,18.431,19.129,18.164z M8.567,15.028c-3.568,0-6.461-2.893-6.461-6.461 s2.893-6.461,6.461-6.461c3.568,0,6.46,2.893,6.46,6.461S12.135,15.028,8.567,15.028z"></path></svg>
      <label class="layout_search_label" for="layout_search">Søgning</label>
      <span class="layout_search_body">
        <input type="text" class="layout_search_text" id="layout_search" name="query" placeholder="Search here"/>
        <input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
        <xsl:for-each select="f:frame/f:search/f:types/f:type">
        <input type="hidden" name="{@unique}" value="on"/>
        <button type="reset" class="layout_search_reset" tabindex="-1">Nulstil</button>
        </xsl:for-each>
        <input type="submit" class="layout_search_submit" value="Søg" tabindex="-1"/>
      </span>
    </form>
  </xsl:if>
</xsl:template>



<!--                    Widgets                 -->

<xsl:template match="widget:hero">
  <div class="hero">
    <div class="hero_title"><span class="hero_title_prefix">Humanise</span> <span class="hero_title_suffix">Editor</span></div>
    <div class="hero_info">
      <div class="hero_text">Simpel redigering af websites</div>
      <a class="hero_button" href="/produkter/onlinepublisher/">Lær mere</a>
    </div>
    <div class="hero_rays"><xsl:comment/></div>
    <div class="hero_pencil"><xsl:comment/></div>
  </div>
</xsl:template>

<xsl:template match="widget:happy-xmas">
  <div class="happy-xmas">
    <h1 style="color: red;"><xsl:value-of select="widget:title"/></h1>
  </div>
</xsl:template>

<xsl:template match="widget:call-to-action">
  <div class="call-to-action">
    <p class="call-to-action-text"><xsl:value-of select="widget:text"/></p>
    <xsl:apply-templates select="widget:button"/>
  </div>
</xsl:template>

<xsl:template match="widget:call-to-action/widget:button">
  <a class="call-to-action-button">
    <xsl:call-template name="util:link-href"/>
    <xsl:value-of select="."/>
  </a>
</xsl:template>

<xsl:template match="widget:placards">
  <ul class="layout_placards">
    <li class="layout_placards_item">
      <a href="{$path}produkter/onlinepublisher/" class="layout_placards_link">
        <strong>Humanise <em>Editor</em></strong> - <span class="layout_placards_text layout_placards_text_editor">Simpelt værktøj til opbygning og redigering af hjemmesider</span>
      </a>
    </li>
    <li class="layout_placards_item">
      <a href="{$path}produkter/onlineobjects/" class="layout_placards_link">
        <strong>Online<em>Objects</em></strong> - <span class="layout_placards_text layout_placards_text_objects">Fleksibelt grundsystem til web-applikationer</span>
      </a>
    </li>
    <li class="layout_placards_item">
      <a href="{$path}teknologi/hui/" class="layout_placards_link layout_placards_link_last">
        <strong>Humanise <em>UI</em></strong> - <span class="layout_placards_text layout_placards_text_hui">Intuitiv, avanceret og effektiv brugerflade</span>
      </a>
    </li>
  </ul>
</xsl:template>


<!--
<xsl:call-template name="util:style-build">
  <xsl:with-param name="async" select="'true'"/>
</xsl:call-template>
  -->
<!--
<xsl:call-template name="util:load-font">
  <xsl:with-param name="href" select="'/style/basic/fonts/Lato/LatoLatin/latolatinfonts_weights.css'"/>
  <xsl:with-param name="family" select="'Lato'"/>
  <xsl:with-param name="weights" select="'300,400,700,900'"/>
</xsl:call-template>-->
<!--
<xsl:call-template name="util:load-font">
  <xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Heebo:100,300,400,500'"/>
  <xsl:with-param name="family" select="'Heebo'"/>
  <xsl:with-param name="weights" select="'100,300,400,500'"/>
</xsl:call-template>
-->
  <!--
    MYRIAD PRO...
  <script>
    <xsl:comment>
    <![CDATA[
    (function(d) {
      var config = {
        kitId: 'gpd5jdr',
        scriptTimeout: 3000,
        async: true
      },
      h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
    })(document);
    ]]>
    </xsl:comment>
  </script>
  -->

</xsl:stylesheet>