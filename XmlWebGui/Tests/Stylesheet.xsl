<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="no" encoding="ISO-8859-1"/>
<xsl:include href="../Skins/In2ition/Main.xsl"/>
<xsl:include href="../Skins/In2ition/Include/Window.xsl"/>
<xsl:include href="../Skins/In2ition/Include/Script.xsl"/>
<xsl:include href="../Skins/In2ition/Include/Graph.xsl"/>
<xsl:include href="../Skins/In2ition/Include/Form.xsl"/>

<xsl:template match="/"><xsl:apply-templates/></xsl:template>
</xsl:stylesheet>