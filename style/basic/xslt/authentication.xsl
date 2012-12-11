<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:a="http://uri.in2isoft.com/onlinepublisher/publishing/authentication/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 exclude-result-prefixes="a p"
 >

<xsl:template match="a:authentication">
	<div class="authentication">
		<xsl:apply-templates/>
		<xsl:call-template name="a:login"/>
	</div>
</xsl:template>

<xsl:template match="a:title">
	<h1 class="common"><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template name="a:status">
	<xsl:if test="$userid>0">
	<div class="status">
		<strong>Nuværende bruger: </strong>
		<xsl:choose>
			<xsl:when test="$userid>0">
			<xsl:value-of select="$usertitle"/>
			<a href="?id={/p:page/@id}&amp;logout=true&amp;page={a:target[@type='page']/@id}">Log ud</a>
			</xsl:when>
			<xsl:otherwise>
			Ingen
			</xsl:otherwise>
		</xsl:choose>
	</div>
	</xsl:if>
</xsl:template>

<xsl:template name="a:login">
	<xsl:call-template name="a:status"/>
	<form method="post" action=".?id={/p:page/@id}">
		<xsl:call-template name="a:target"/>
		<div>
			<label for="username">Brugernavn:</label><input type="text" name="username" id="username" class="textfield"/>
		<br/>
		<label for="password">Kodeord:</label><input type="password" name="password" id="password"  class="textfield"/>
		<br/><input type="submit" value="Log ind!" class="button"/>
		</div>
	</form>
	<script type="text/javascript">
	<xsl:comment>
		document.getElementById('username').focus();
	</xsl:comment>
	</script>
</xsl:template>

<xsl:template match="a:target[@type='page']">
	<div class="message">Du har ikke på nuværende tidspunkt adgang til at tilgå den forespurgte side. Log venligst ind med en anden bruger...</div>
</xsl:template>

<xsl:template name="a:target">
	<xsl:if test="a:target/@type='page'">
		<div><input type="hidden" name="page" value="{a:target/@id}"/></div>
	</xsl:if>
</xsl:template>

<xsl:template match="a:message">
	<div class="message">Besked:<xsl:value-of select="@type"/></div>
</xsl:template>

<xsl:template match="a:message[@type='loggedin']">
	<div class="message">Du er nu logget ind som "<xsl:value-of select="$usertitle"/>"</div>
</xsl:template>

<xsl:template match="a:message[@type='loggedout']">
	<div class="message">Du er blevet logget ud.</div>
</xsl:template>

<xsl:template match="a:message[@type='nousername']">
	<div class="message">Der blev ikke angivet et brugernavn.</div>
</xsl:template>

<xsl:template match="a:message[@type='nopassword']">
	<div class="message">Der blev ikke angivet et kodeord.</div>
</xsl:template>

<xsl:template match="a:message[@type='usernotfound']">
	<div class="message">Brugeren kunne findes ikke. Prøv venligst igen.</div>
</xsl:template>

</xsl:stylesheet>