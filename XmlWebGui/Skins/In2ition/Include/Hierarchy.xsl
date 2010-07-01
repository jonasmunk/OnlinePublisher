<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Hierarchy"
    version="1.0"
    exclude-result-prefixes="xwg"
    >

<xsl:template match="xwg:hierarchy">
<div class="Hierarchy">
<script>var xwgGraphics='<xsl:value-of select="$graphics"/>'</script>
<script>var xwgIconset='<xsl:value-of select="$iconset"/>'</script>
<script type="text/javascript" src="{$root}Scripts/Hierarchy.js"></script>
<script type="text/javascript" src="{$root}Scripts/In2iXML.js"></script>
<script>
<xsl:if test="@persistence='true'">
HierarchyConfig.usePersistence=true;
HierarchyHandler.idPrefix='<xsl:value-of select="@unique"/>';
</xsl:if>
var <xsl:value-of select="generate-id()"/> = new Hierarchy();
<xsl:value-of select="generate-id()"/>.setBehavior('classic');
<xsl:apply-templates select="xwg:element"/>
<xsl:if test="@selection">
HierarchyHandler.selection = true;
HierarchyHandler.setSelection('<xsl:value-of select="@selection"/>');
</xsl:if>
document.write(<xsl:value-of select="generate-id()"/>);
<xsl:if test="@selection-refresh-url">
<xsl:value-of select="generate-id()"/>.initSelectionRefresh('<xsl:value-of select="@selection-refresh-url"/>');
</xsl:if>
</script>
</div>
</xsl:template>

<xsl:template match="xwg:element">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
var <xsl:value-of select="generate-id()"/> = new HierarchyItem("<xsl:value-of select="translate(@title,'&#x22;','')"/>");
<xsl:value-of select="generate-id()"/>.style='<xsl:value-of select="$style"/>';
<xsl:if test="@link">
<xsl:value-of select="generate-id()"/>.action='<xsl:value-of select="@link"/>';
<xsl:if test="@target">
<xsl:value-of select="generate-id()"/>.target='<xsl:value-of select="@target"/>';
</xsl:if>
</xsl:if>
<xsl:if test="@drop">
<xsl:value-of select="generate-id()"/>.dropId='<xsl:value-of select="@drop"/>';
</xsl:if>
<xsl:if test="@open='true'">
<xsl:value-of select="generate-id()"/>.open=true;
</xsl:if>
<xsl:if test="@icon">
<xsl:value-of select="generate-id()"/>.icon='<xsl:if test="not(@iconset)"><xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>1.gif</xsl:if><xsl:if test="@iconset"><xsl:value-of select="$iconsetroot"/><xsl:value-of select="@iconset"/>/<xsl:value-of select="@icon"/><xsl:value-of select="$style"/>1.gif</xsl:if>';
<xsl:value-of select="generate-id()"/>.openIcon='<xsl:if test="not(@iconset)"><xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>1.gif</xsl:if><xsl:if test="@iconset"><xsl:value-of select="$iconsetroot"/><xsl:value-of select="@iconset"/>/<xsl:value-of select="@icon"/><xsl:value-of select="$style"/>1.gif</xsl:if>';
</xsl:if>
<xsl:if test="@unique">
<xsl:value-of select="generate-id()"/>.unique='<xsl:value-of select="@unique"/>';
</xsl:if>
<xsl:if test="@info">
<xsl:value-of select="generate-id()"/>.info=<xsl:value-of select="@info"/>;
</xsl:if>
<xsl:value-of select="generate-id(parent::node())"/>.add(<xsl:value-of select="generate-id()"/>);
<xsl:apply-templates/>
<xsl:if test="@drop">
registerDropArea("<xsl:value-of select="@drop"/>");
</xsl:if>
</xsl:template>

</xsl:stylesheet>