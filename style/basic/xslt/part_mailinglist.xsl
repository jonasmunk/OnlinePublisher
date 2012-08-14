<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:ml="http://uri.in2isoft.com/onlinepublisher/part/mailinglist/1.0/"
	xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
	exclude-result-prefixes="ml p">
	
	<xsl:template match="ml:mailinglist">
		<div class="part_mailinglist common_font">
			<xsl:apply-templates/>
		</div>
	</xsl:template>
	
	<xsl:template match="ml:mailinglist/ml:subscribe">
		<xsl:variable name="lang_title"><xsl:choose>
			<xsl:when test="$language='en'">Subscribe to newsletter</xsl:when>
			<xsl:otherwise>Tilmeld nyhedsbrev</xsl:otherwise>
		</xsl:choose></xsl:variable>
		<xsl:variable name="lang_success"><xsl:choose>
			<xsl:when test="$language='en'">You have now subscribed to the newsletter</xsl:when>
			<xsl:otherwise>Du er nu tilmeldt postlisten</xsl:otherwise>
		</xsl:choose></xsl:variable>
		<xsl:variable name="lang_name"><xsl:choose>
			<xsl:when test="$language='en'">Name</xsl:when>
			<xsl:otherwise>Navn</xsl:otherwise>
		</xsl:choose></xsl:variable>
		<xsl:variable name="lang_subscribe"><xsl:choose>
			<xsl:when test="$language='en'">Subscribe</xsl:when>
			<xsl:otherwise>Tilmeld</xsl:otherwise>
		</xsl:choose></xsl:variable>
		<xsl:copy-of select="child::*|child::text()"/>
		<div class="part_mailinglist_box part_mailinglist_subscribe">
			<div class="part_mailinglist_box_top"><div><div><xsl:comment/></div></div></div>
			<div class="part_mailinglist_box_middle"><div class="part_mailinglist_box_middle">
				<xsl:choose>
					<xsl:when test="$editor='true'">
						<div>
						<input type="hidden" name="{../../../@id}_action" value="subscribe"/>
						<h2 class="common"><xsl:value-of select="$lang_title"/></h2>
						<xsl:if test="ml:error">
							<p class="error"><xsl:apply-templates select="ml:error"/></p>
						</xsl:if>
						<xsl:if test="ml:success">
							<p class="success"><xsl:value-of select="$lang_success"/></p>
						</xsl:if>
						<p>
							<label><xsl:value-of select="$lang_name"/>:</label>
							<span class="common_field"><span><span><input class="text" name="{../../../@id}_name" value="{ml:value[@key='name']/@value}"/></span></span></span>
						</p>
						<p>
							<label>E-mail:</label>
							<span class="common_field"><span><span><input class="text" name="{../../../@id}_email" value="{ml:value[@key='email']/@value}"/></span></span></span>
						</p>
						<p class="buttons">
							<input type="submit" class="submit" value="{$lang_subscribe}"/>
						</p>
						</div>
					</xsl:when>
					<xsl:otherwise>
						<form action="./?id={//p:page/@id}" method="post" accept-charset="UTF-8">
						<div>
						<input type="hidden" name="{../../../@id}_action" value="subscribe"/>
						<h2 class="common"><xsl:value-of select="$lang_title"/></h2>
						<xsl:if test="ml:error">
							<p class="error"><xsl:apply-templates select="ml:error"/></p>
						</xsl:if>
						<xsl:if test="ml:success">
							<p class="success"><xsl:value-of select="$lang_success"/></p>
						</xsl:if>
						<p>
							<label><xsl:value-of select="$lang_name"/>:</label>
							<span class="common_field"><span><span><input class="text" name="{../../../@id}_name" value="{ml:value[@key='name']/@value}"/></span></span></span>
						</p>
						<p>
							<label>E-mail:</label>
							<span class="common_field"><span><span><input class="text" name="{../../../@id}_email" value="{ml:value[@key='email']/@value}"/></span></span></span>
						</p>
						<p class="buttons">
							<input type="submit" class="submit" value="{$lang_subscribe}"/>
						</p>
						</div>
						</form>
					</xsl:otherwise>
				</xsl:choose>
			</div></div>
			<div class="part_mailinglist_box_bottom"><div><div><xsl:comment/></div></div></div>
		</div>
	</xsl:template>
	
	<xsl:template match="ml:mailinglist/ml:unsubscribe">
		<xsl:variable name="lang_title"><xsl:choose>
			<xsl:when test="$language='en'">Unsubscribe from newsletter</xsl:when>
			<xsl:otherwise>Frameld nyhedsbrev</xsl:otherwise>
		</xsl:choose></xsl:variable>
		<xsl:variable name="lang_success"><xsl:choose>
			<xsl:when test="$language='en'">You have now unsubscribed from the newsletter</xsl:when>
			<xsl:otherwise>Du er nu frameldt postlisten</xsl:otherwise>
		</xsl:choose></xsl:variable>
		<xsl:variable name="lang_unsubscribe"><xsl:choose>
			<xsl:when test="$language='en'">Unsubscribe</xsl:when>
			<xsl:otherwise>Frameld</xsl:otherwise>
		</xsl:choose></xsl:variable>
		<div class="part_mailinglist_box part_mailinglist_unsubscribe">
			<div class="part_mailinglist_box_top"><div><div><xsl:comment/></div></div></div>
			<div class="part_mailinglist_box_middle"><div class="part_mailinglist_box_middle">
				<xsl:choose>
					<xsl:when test="$editor='true'">
						<div>
						<input type="hidden" name="{../../../@id}_action" value="unsubscribe"/>
						<h2 class="common"><xsl:value-of select="$lang_title"/></h2>
						<xsl:if test="ml:error">
							<p class="error"><xsl:apply-templates select="ml:error"/></p>
						</xsl:if>
						<xsl:if test="ml:success">
							<p class="success"><xsl:value-of select="$lang_success"/></p>
						</xsl:if>
						<p>
							<label>E-mail:</label>
							<span class="common_field"><span><span><input class="text" name="{../../../@id}_email" value="{ml:value[@key='email']/@value}"/></span></span></span>
						</p>
						<p class="buttons"><input type="submit" class="submit" value="{$lang_unsubscribe}"/></p>
						</div>
					</xsl:when>
					<xsl:otherwise>
						<form action="./?id={//p:page/@id}" method="post" accept-charset="UTF-8">
							<div>
							<input type="hidden" name="{../../../@id}_action" value="unsubscribe"/>
							<h2 class="common"><xsl:value-of select="$lang_title"/></h2>
							<xsl:if test="ml:error">
								<p class="error"><xsl:apply-templates select="ml:error"/></p>
							</xsl:if>
							<xsl:if test="ml:success">
								<p class="success"><xsl:value-of select="$lang_success"/></p>
							</xsl:if>
							<p>
								<label>E-mail:</label>
								<span class="common_field"><span><span><input class="text" name="{../../../@id}_email" value="{ml:value[@key='email']/@value}"/></span></span></span>
							</p>
							<p class="buttons"><input type="submit" class="submit" value="{$lang_unsubscribe}"/></p>
							</div>
						</form>
					</xsl:otherwise>
				</xsl:choose>
			</div></div>
			<div class="part_mailinglist_box_bottom"><div><div><xsl:comment/></div></div></div>
		</div>
	</xsl:template>
	
	<xsl:template match="ml:error[@key='noname']">
		<xsl:choose>
			<xsl:when test="$language='en'">Name must be provided</xsl:when>
			<xsl:otherwise>Navn skal udfyldes</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template match="ml:error[@key='noemail']">
		<xsl:choose>
			<xsl:when test="$language='en'">E-mail must be provided</xsl:when>
			<xsl:otherwise>E-mail-adressen skal udfyldes</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template match="ml:error[@key='invalidemail']">
		<xsl:choose>
			<xsl:when test="$language='en'">The e-mail address is not valid</xsl:when>
			<xsl:otherwise>E-mail-adressen er ikke valid</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template match="ml:error[@key='notsubscribed']">
		<xsl:choose>
			<xsl:when test="$language='en'">The e-mail address was not found</xsl:when>
			<xsl:otherwise>E-mail-adressen blev ikke fundet</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
</xsl:stylesheet>
