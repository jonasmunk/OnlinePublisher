<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:pl="http://uri.in2isoft.com/onlinepublisher/publishing/productlisting/1.0/"
 xmlns:pg="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/class/product/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 exclude-result-prefixes="o p pl i"
 >

<xsl:decimal-format name="danish" decimal-separator=","/>

<xsl:template match="pl:productlisting">
<script type="text/javascript" src="{$path}In2iGui/js/ImageViewer.js"><xsl:comment/></script>
<script type="text/javascript" src="{$path}style/basic/js/ProductListing.js"><xsl:comment/></script>
<script type="text/javascript">
	with (OP.ProductListing.get()) {
		<xsl:for-each select="//o:object[@type='image']">
			addImage({id:'<xsl:value-of select="@id"/>'});
		</xsl:for-each>
	}
</script>
<div class="productlisting">
<xsl:apply-templates select="pl:list | pl:make-offer"/>
</div>
</xsl:template>

<xsl:template match="pl:title">
<h1 class="common"><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="pl:text">
<p class="common"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="pl:break | o:break | p:break">
<br/>
</xsl:template>

<xsl:template match="pl:list">
<div class="list">
<xsl:apply-templates select="../pl:title"/>
<xsl:apply-templates select="../pl:text"/>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="pl:list/o:object[@type='product']">
<div class="product">
	<xsl:call-template name="pl:product"/>
	<xsl:if test="o:sub/p:product/p:allow-offer/.='true'">
		<a href="?id={//pg:page/@id}&amp;makeOffer={@id}" onclick="document.getElementById('{generate-id()}_offer').style.display='block';this.style.display='none';return false;">Afgiv bud</a>
	</xsl:if>
</div>
</xsl:template>


<xsl:template match="pl:make-offer">
	<xsl:apply-templates select="o:object"/>
	<form action="{$path}services/productoffer/" class="offer" method="post">
		<h2 class="common">Afgiv bud</h2>
		<xsl:apply-templates select="pl:error | pl:success"/>
		<input type="hidden" name="productId" value="{o:object/@id}"/>
		<input type="hidden" name="pageId" value="{//pg:page/@id}"/>
		<table>
			<tr>
				<th><label>Navn:</label></th>
				<td><input name="name" class="text" value="{pl:value[@key='name']/@value}"/></td>
			</tr>
			<tr>
				<th><label>E-mail:</label></th>
				<td><input name="email" class="text" value="{pl:value[@key='email']/@value}"/></td>
			</tr>
			<tr>
				<th><label>Bud (kr):</label></th>
				<td><input name="offer" class="text" value="{pl:value[@key='offer']/@value}"/></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" class="submit" value="Afgiv bud"/></td>
			</tr>
		</table>
	</form>
	<p class="common">
		<a href="?id={//pg:page/@id}" class="common">Tilbage</a>
	</p>
</xsl:template>

<xsl:template match="pl:make-offer/o:object[@type='product']">
<div class="product">
	<xsl:call-template name="pl:product"/>
</div>
</xsl:template>

<xsl:template match="pl:error[@key='noName']">
	<div class="error">Navn er ikke udfyldt</div>
</xsl:template>

<xsl:template match="pl:error[@key='noEmail']">
	<div class="error">E-mailadressen er ikke udfyldt</div>
</xsl:template>

<xsl:template match="pl:error[@key='invalidEmail']">
	<div class="error">E-mailadressen er ikke valid</div>
</xsl:template>

<xsl:template match="pl:error[@key='noOffer']">
	<div class="error">Bud er ikke udfyldt</div>
</xsl:template>

<xsl:template match="pl:success">
	<div class="success">Dit bud er registreret</div>
</xsl:template>

<xsl:template name="pl:product">
	<xsl:apply-templates select="o:sub/p:product//i:image"/>
	<strong><xsl:value-of select="o:title"/></strong> (<xsl:value-of select="o:sub/p:product/p:number"/>)<br/>
	<p><xsl:apply-templates select="o:note"/></p>
	<xsl:apply-templates select="o:sub/p:product/p:attributes"/>
	<xsl:apply-templates select="o:sub/p:product/p:prices"/>
</xsl:template>

<xsl:template match="i:image">
<a href="{$path}services/images/?id={../../@id}" onclick="OP.ProductListing.get().showImage({../../@id}); return false;"><img src="{$path}services/images/?id={../../@id}&amp;width=100&amp;height=130" style="float:right;"/></a>
</xsl:template>

<xsl:template match="p:attributes">
<dl><xsl:apply-templates/></dl>
</xsl:template>

<xsl:template match="p:attribute">
<dt><xsl:value-of select="@name"/>:</dt>
<dd><xsl:apply-templates/></dd>
</xsl:template>


<xsl:template match="p:prices">
<dl><xsl:apply-templates/></dl>
</xsl:template>

<xsl:template match="p:price">
<dt><xsl:value-of select="@amount"/><xsl:text> </xsl:text><xsl:call-template name="pricetype"/>:</dt>
<dd><xsl:value-of select="format-number(@price,'#,00','danish')"/><xsl:text> </xsl:text><xsl:value-of select="@currency"/></dd>
</xsl:template>

<xsl:template name="pricetype">
<xsl:choose>
<xsl:when test="@type='unit'">stk.</xsl:when>
<xsl:when test="@type='meter'">m.</xsl:when>
<xsl:when test="@type='squaremeter'">m<sup>2</sup>.</xsl:when>
<xsl:when test="@type='cubicmeter'">m<sup>3</sup>.</xsl:when>
<xsl:otherwise><xsl:value-of select="@type"/></xsl:otherwise>
</xsl:choose>
</xsl:template>
</xsl:stylesheet>