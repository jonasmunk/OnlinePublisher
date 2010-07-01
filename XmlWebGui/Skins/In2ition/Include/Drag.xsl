<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:d="uri:Drag"
    version="1.0"
    exclude-result-prefixes="d"
    >

<xsl:template match="d:config">
<img src="{$iconset}{@proxy}Standard2.gif" width="32" height="32" id="DragProxy" border="0" style="display: none; position: absolute; top: 0px; left: 0px; z-index: 100; opacity:.50;"/>
<script language="JavaScript" src="{$root}Scripts/Drag.js"></script>
<script language="JavaScript" src="{$root}Scripts/Library.js"></script>
<xsl:if test="d:frame">
<script language="JavaScript">
<xsl:for-each select="d:frame">
registerFrame('<xsl:value-of select="@name"/>');
</xsl:for-each>
</script>
</xsl:if>
<form name="DragForm" action="{@action}" method="{@method}" target="{@target}" style="margin: 0px;">
<input type="hidden" name="dragId"/>
<input type="hidden" name="dropId"/>
</form>
<!--<input id="debug"/>-->
</xsl:template>

<xsl:template match="d:drag">
<div id="{@id}" style="position: relative; display: inline;">
<xsl:apply-templates/>
<script language="javascript">
	registerDragObject("<xsl:value-of select="@id"/>");
</script>
</div>
</xsl:template>

<xsl:template match="d:drop">
<div id="{@id}" style="position: relative;">
<xsl:apply-templates/>
<script language="javascript">
	registerDropArea("<xsl:value-of select="@id"/>");
</script>
</div>
</xsl:template>

<xsl:template match="d:dragdrop">
<div id="{@id}" style="position: relative;">
<xsl:apply-templates/>
<script language="javascript">
	registerDragObject("<xsl:value-of select="@id"/>");
	registerDropArea("<xsl:value-of select="@id"/>");
</script>
</div>

</xsl:template>

</xsl:stylesheet>