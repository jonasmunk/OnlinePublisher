<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Script"
    version="1.0"
    exclude-result-prefixes="xwg"
    >

<xsl:template match="xwg:script">
<script>
<xsl:if test="@source"><xsl:attribute name="src"><xsl:value-of select="@source"/></xsl:attribute></xsl:if>
<xsl:attribute name="language">
<xsl:choose>
<xsl:when test="not(@language) or @language=''">JavaScript</xsl:when>
<xsl:otherwise><xsl:value-of select="@language"/></xsl:otherwise>
</xsl:choose>
</xsl:attribute>
<xsl:attribute name="type">
<xsl:choose>
<xsl:when test="not(@type) or @type=''">text/javascript</xsl:when>
<xsl:otherwise><xsl:value-of select="@type"/></xsl:otherwise>
</xsl:choose>
</xsl:attribute>
<xsl:value-of select="."/>
</script>
</xsl:template>

<xsl:template match="xwg:internalscript">
<script src="{$root}Scripts/{@source}" type="text/javascript" language="JavaScript"></script>
</xsl:template>

<xsl:template match="xwg:validation">
<script type="text/javascript" language="JavaScript" src="{$root}Scripts/Validation.js"></script>
</xsl:template>

<xsl:template match="xwg:sniffer">
<script language="JavaScript"><xsl:attribute name="src"><xsl:value-of select="$root"/>Scripts/Sniffer.js</xsl:attribute></script>
<script language="JavaScript">
var <xsl:value-of select="@name"/> = new browserSniffer();
</script>
</xsl:template>

<xsl:template match="xwg:refresh">
<script language="JavaScript" type="text/javascript" src="{$root}Scripts/In2iXML.js"></script>
<script language="JavaScript" type="text/javascript" src="{$root}Scripts/RefreshRequest.js"></script>
<script type="text/javascript">
new In2iGui.RefreshRequest(<xsl:value-of select="@interval"/>,'<xsl:value-of select="@source"/>');
</script>
</xsl:template>

<xsl:template match="xwg:poller">
<script type="text/javascript">
var g_remoteServer = '<xsl:value-of select="@source"/>';
var g_intervalID;

function callServer() {
        var head = document.getElementsByTagName('head').item(0);
        var old  = document.getElementById('lastLoadedCmds');
        if (old) head.removeChild(old);

        script = document.createElement('script');
        if (g_remoteServer.indexOf('?')!=-1) {
        script.src = g_remoteServer+'&amp;random='+Math.random()+''+Math.random();
        }
        else {
        script.src = g_remoteServer+'?random='+Math.random()+''+Math.random();
        }
        script.type = 'text/javascript';
        script.defer = true;
        script.id = 'lastLoadedCmds';
        void(head.appendChild(script));
}

g_intervalID = setInterval(callServer, <xsl:value-of select="@interval"/>);
</script>
</xsl:template>

<xsl:template match="xwg:objectdetection">
<script type="text/javascript">
<xsl:if test="@positive">
<xsl:value-of select="@positive"/>=getPositive('<xsl:value-of select="."/>');
</xsl:if>
<xsl:if test="@negative">
<xsl:value-of select="@negative"/>=getNegative('<xsl:value-of select="."/>');
</xsl:if>

<![CDATA[
function getPositive(what) {
  var output='';
  var testArr=what.split(',')
  for (i=0; i<testArr.length; i++) {
    if (String(eval(testArr[i]))!='undefined') {
      if (output.length>0) output+=',';
      output+= testArr[i];
    }
  }
  return output;
}

function getNegative(what) {
  var output='';
  var testArr=what.split(',')
  for (i=0; i<testArr.length; i++) {
    if (String(eval(testArr[i]))=='undefined') {
      if (output.length>0) output+=',';
      output+= testArr[i];
    }
  }
  return output;
}

]]>
</script>
</xsl:template>
</xsl:stylesheet>