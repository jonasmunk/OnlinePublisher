<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
xmlns="http://www.w3.org/1999/xhtml"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:l="http://uri.in2isoft.com/onlinepublisher/part/list/1.0/"
xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
exclude-result-prefixes="l"
>

<xsl:template match="l:list">
	<div class="part_list common_font" id="part_list_{../../@id}">
		<div class="part_list_box">
		<xsl:apply-templates/>
		<xsl:if test="not(l:item)">
			<p class="part_list_nodata">				
				<xsl:choose>
					<xsl:when test="$language='en'">
						<xsl:text>There are currently no events</xsl:text>
					</xsl:when>
					<xsl:otherwise>
						<xsl:text>Der findes pt. ingen begivenheder</xsl:text>
					</xsl:otherwise>
				</xsl:choose>
			</p>
		</xsl:if>
		<xsl:comment/>
		</div>
	</div>
	<xsl:if test="@dirty='true' and $editor!='true'">
		<script>
			require(['hui','op'],function() {
				var id = <xsl:value-of select="../../@id"/>;
				var node = hui.get('part_list_'+id);
				hui.cls.add(node,'part_list_busy');
				hui.log('Updating list ('+id+')');
				hui.request({
					url:'<xsl:value-of select="$path"/>services/parts/render/',
					parameters:{type:'list',id:id,synchronize:'true',pageId:op.page.id},
					$success : function(t) {
						node.parentNode.innerHTML = t.responseText;
						hui.log('Finished list ('+id+')');
					}
				})
			});
		</script>
	</xsl:if>
</xsl:template>

<xsl:template match="l:list/l:title">
	<h2><xsl:apply-templates/></h2>
</xsl:template>

<xsl:template match="l:item">
	<div class="part_list_item">
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="l:item/l:title">
	<h3><xsl:apply-templates/><xsl:value-of select="../l:url"/></h3>
</xsl:template>

<xsl:template match="l:item[l:url]/l:title">
	<h3><a href="{../l:url}" class="common"><span><xsl:apply-templates/></span></a></h3>
</xsl:template>

<xsl:template match="l:item/l:text">
	<p class="part_list_text"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="l:break"><br/></xsl:template>

<xsl:template match="l:item/l:source">
	<p class="part_list_source"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="l:item/l:url"></xsl:template>

<xsl:template match="l:item/l:date">
	<p class="part_list_date">
		<xsl:call-template name="util:long-date-time"><xsl:with-param name="node" select="."/></xsl:call-template>
		<xsl:if test="../../l:settings/@show-timezone='true'">
			(<xsl:value-of select="@timezone"/>)
		</xsl:if>
	</p>
</xsl:template>

<xsl:template match="l:item/l:start-date">
	<p class="part_list_date">
		<xsl:choose>
			<xsl:when test="$language='da'"><xsl:text>Fra: </xsl:text></xsl:when>
			<xsl:otherwise><xsl:text>From: </xsl:text></xsl:otherwise>
		</xsl:choose>
		<xsl:call-template name="util:long-date-time"><xsl:with-param name="node" select="."/></xsl:call-template>
		<xsl:if test="../../l:settings/@show-timezone='true'">
			(<xsl:value-of select="@timezone"/>)
		</xsl:if>
	</p>
</xsl:template>

<xsl:template match="l:item/l:end-date">
	<p class="part_list_date">
		<xsl:choose>
			<xsl:when test="$language='da'"><xsl:text>Til: </xsl:text></xsl:when>
			<xsl:otherwise><xsl:text>To: </xsl:text></xsl:otherwise>
		</xsl:choose>
		<xsl:call-template name="util:long-date-time"><xsl:with-param name="node" select="."/></xsl:call-template>
		<xsl:if test="../../l:settings/@show-timezone='true'">
			(<xsl:value-of select="@timezone"/>)
		</xsl:if>
	</p>
</xsl:template>

</xsl:stylesheet>