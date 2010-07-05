<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/part/formula/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/class/file/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 exclude-result-prefixes="f p o"
 >

<xsl:template match="p:formula">
	<xsl:if test="$editor='true'">
		<div class="part_formula">
			<xsl:call-template name="p:content"/>
		</div>
	</xsl:if>
	<xsl:if test="$editor!='true'">
		<form class="part_formula" id="{generate-id()}">
			<xsl:call-template name="p:content"/>
		</form>
		<script type="text/javascript">
			new op.part.Formula({element:'<xsl:value-of select="generate-id()"/>',id:<xsl:value-of select="../../@id"/>});
		</script>
	</xsl:if>
</xsl:template>

<xsl:template name="p:content">
		<p class="part_formula_label"><label>Navn:</label></p>
		<p><input class="part_formula_text" name="name"/></p>
		<p class="part_formula_label"><label>E-mail:</label></p>
		<p><input class="part_formula_text" name="email"/></p>
		<p class="part_formula_label"><label>Besked:</label></p>
		<p><textarea class="part_formula_text" name="message"><xsl:text> </xsl:text></textarea></p>
		<xsl:if test="$editor='true'">
			<p><input type="submit" value="Afsend" onclick="return false;"/></p>
		</xsl:if>
		<xsl:if test="$editor!='true'">
			<p><input type="submit" value="Afsend"/></p>
		</xsl:if>
</xsl:template>

</xsl:stylesheet>