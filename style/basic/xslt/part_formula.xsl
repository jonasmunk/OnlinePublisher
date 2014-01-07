<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/part/formula/1.0/"
 exclude-result-prefixes="p"
 >

<xsl:template match="p:formula">
		<div class="part_formula common_font">
	<xsl:if test="$editor='true'">
			<xsl:call-template name="p:content"/>
	</xsl:if>
	<xsl:if test="$editor!='true'">
		<form id="{generate-id()}" onsubmit="return false">
			<xsl:comment/>
			<xsl:call-template name="p:content"/>
		</form>
		<script type="text/javascript">
			_op[_op.length] = function() {
				var inputs = [];
				<xsl:for-each select="descendant::p:input">
					inputs.push({
						id : '<xsl:value-of select="generate-id()"/>',
						label : '<xsl:value-of select="../@label"/>',
						validation : {
							message : '<xsl:value-of select="p:validation/@message"/>'
							<xsl:if test="p:validation/@required='true'">
								,required : true
							</xsl:if>
							<xsl:if test="p:validation/@syntax">
								,syntax : '<xsl:value-of select="p:validation/@syntax"/>'
							</xsl:if>
						}
					})
				</xsl:for-each>
				new op.part.Formula({
					element : '<xsl:value-of select="generate-id()"/>',
					id : <xsl:value-of select="../../@id"/>,
					inputs : inputs
				});
			}
		</script>
	</xsl:if>
		</div>
</xsl:template>

<xsl:template name="p:content">
	<xsl:choose>
		<xsl:when test="p:recipe">
			<xsl:apply-templates/>
		</xsl:when>
		<xsl:when test="p:invalid">
			<p>INVALID</p>
		</xsl:when>
		<xsl:otherwise>
			<div class="part_formula_field">
				<p class="part_formula_label"><label>Navn:</label></p>
				<p><input class="part_formula_text" name="name"/></p>
			</div>
			<div class="part_formula_field">
				<p class="part_formula_label"><label>E-mail:</label></p>
				<p><input class="part_formula_text" name="email"/></p>
			</div>
			<div class="part_formula_field">
				<p class="part_formula_label"><label>Besked:</label></p>
				<p><textarea class="part_formula_text" name="message"><xsl:text> </xsl:text></textarea></p>
			</div>
			<p><input type="submit" value="Afsend">
				<xsl:if test="$editor='true'">
					<xsl:attribute name="disabled">disabled</xsl:attribute>
				</xsl:if>
			</input></p>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template match="p:recipe">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="p:form">
	<div>
		<xsl:apply-templates/>
		<p class="part_formula_buttons">
			<input type="submit">
				<xsl:if test="p:submit[@text]">
					<xsl:attribute name="value">
						<xsl:value-of select="p:submit/@text"/>
					</xsl:attribute>
				</xsl:if>
				<xsl:if test="$editor='true'">
					<xsl:attribute name="disabled">disabled</xsl:attribute>
				</xsl:if>
			</input>
		</p>
	</div>
</xsl:template>

<xsl:template match="p:fieldset">
	<div>
		<xsl:attribute name="class">
			<xsl:text>part_formula_fieldset</xsl:text>
			<xsl:if test="@variant">
				<xsl:text> part_formula_fieldset_</xsl:text><xsl:value-of select="@variant"/>
			</xsl:if>
		</xsl:attribute>
		<xsl:if test="@legend">
			<p class="part_formula_fieldset_legend"><xsl:value-of select="@legend"/></p>
		</xsl:if>
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="p:field">
	<div class="part_formula_field">
		<p class="part_formula_label">
			<label><xsl:value-of select="@label"/></label>
			<xsl:if test="descendant::p:validation[@required='true']">
				<xsl:text> </xsl:text><span class="part_formula_required">*</span>
			</xsl:if>
		</p>
		<p class="part_formula_input">
			<xsl:if test="@prefix">
				<span class="part_formula_prefix"><span><xsl:value-of select="@prefix"/></span></span>
			</xsl:if>
			<xsl:apply-templates/>
		</p>
		<xsl:if test="@hint">
			<p class="part_formula_hint"><xsl:value-of select="@hint"/></p>
		</xsl:if>
	</div>
</xsl:template>

<xsl:template match="p:input">
	<input class="part_formula_input" type="text" id="{generate-id()}">
		<xsl:if test="@name">
			<xsl:attribute name="name">
				<xsl:value-of select="@name"/>
			</xsl:attribute>
		</xsl:if>
	</input>
</xsl:template>

<xsl:template match="p:input[@line-breaks='true']">
	<textarea class="part_formula_input" id="{generate-id()}">
		<xsl:if test="@name">
			<xsl:attribute name="name">
				<xsl:value-of select="@name"/>
			</xsl:attribute>
		</xsl:if>
		<xsl:text> </xsl:text>
	</textarea>
</xsl:template>

<xsl:template match="p:number">
	<input class="part_formula_text" type="text" name="{@name}" data-label="{../@label}"/>
</xsl:template>

<xsl:template match="p:space[@height]">
	<div style="font-size: 0; height: {@height}px"><xsl:comment/></div>
</xsl:template>

<xsl:template match="p:columns">
	<table>
		<tr>
			<xsl:apply-templates select="p:column"/>
		</tr>
	</table>
</xsl:template>

<xsl:template match="p:column">
	<td>
		<xsl:attribute name="class">
			<xsl:text>part_formula_column</xsl:text>
			<xsl:if test="position()=1">
				<xsl:text> part_formula_column_first</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:if test="@width">
			<xsl:attribute name="style">
				<xsl:text>width:</xsl:text><xsl:value-of select="@width"/><xsl:text>;</xsl:text>
			</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
	</td>
</xsl:template>

</xsl:stylesheet>