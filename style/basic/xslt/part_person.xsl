<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:pp="http://uri.in2isoft.com/onlinepublisher/part/person/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/class/person/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 exclude-result-prefixes="pp p o i"
 >

<xsl:template match="pp:person">
	<div class="part_person common_font">
		<xsl:apply-templates select="o:object/o:sub/p:person"/>
	</div>
</xsl:template>

<xsl:template match="pp:person//p:person">
<table>
	<xsl:if test="../../../pp:style/@align">
		<xsl:attribute name="align"><xsl:value-of select="../../../pp:style/@align"/></xsl:attribute>
	</xsl:if>
	<tr>
		
		<xsl:if test="../../../pp:display/@image='true'">
			<td class="part_person_image">
				<a>
					<xsl:if test="p:image">
					<xsl:attribute name="href">
						<xsl:value-of select="$path"/><xsl:text>services/images/?id=</xsl:text><xsl:value-of select="p:image/o:object/@id"/>
					</xsl:attribute>
					<img src="{$path}services/images/?id={p:image/o:object/@id}&amp;width=60&amp;height=80&amp;method=crop" alt="" id="{generate-id(p:image/o:object)}"/>
			<script type="text/javascript">
				try {
					op.registerImageViewer('<xsl:value-of select="generate-id(p:image/o:object)"/>',{
						id : <xsl:value-of select="p:image/o:object/@id"/>,
						width : <xsl:value-of select="p:image/o:object/o:sub/i:image/i:width"/>,
						height : <xsl:value-of select="p:image/o:object/o:sub/i:image/i:height"/>
					});
					hui.get('<xsl:value-of select="generate-id(p:image/o:object)"/>').onerror=function() {this.style.display='none'}
				} catch (ignore) {}
			</script>
					</xsl:if>
				</a>
			</td>
		</xsl:if>
		<td>
		<div class="vcard">
			<div class="fn">
				<xsl:if test="../../../pp:display/@firstname='true'">
					<xsl:value-of select="p:firstname"/>
				</xsl:if>
				<xsl:text> </xsl:text>
				<xsl:if test="../../../pp:display/@middlename='true'">
					<xsl:value-of select="p:middlename"/>
				</xsl:if>
				<xsl:text> </xsl:text>
				<xsl:if test="../../../pp:display/@surname='true'">
					<xsl:value-of select="p:surname"/>
				</xsl:if>
			</div>
			<xsl:if test="(../../../pp:display/@initials='true' and p:initials!='') or (../../../pp:display/@nickname='true' and p:nickname!='')">
				<div>
					<xsl:if test="../../../pp:display/@initials='true'">
						<xsl:value-of select="p:initials"/>
					</xsl:if>
					<xsl:if test="(../../../pp:display/@initials='true' and p:initials!='') and (../../../pp:display/@nickname='true' and p:nickname!='')">
						/
					</xsl:if>
					<xsl:if test="../../../pp:display/@nickname='true'">
						<xsl:value-of select="p:nickname"/>
					</xsl:if>
				</div>
			</xsl:if>
			<xsl:if test="../../../pp:display/@jobtitle='true' and p:jobtitle!=''">
				<div class="title"><xsl:value-of select="p:jobtitle"/></div>
			</xsl:if>
			<xsl:if test="../../../pp:display/@sex='true'">
				<div>
					<span class="part_person_label">K&#248;n: </span>
					<xsl:choose>
						<xsl:when test="p:sex='male'">Mand</xsl:when>
						<xsl:otherwise>Kvinde</xsl:otherwise>
					</xsl:choose>
				</div>
			</xsl:if>
			<xsl:if test="(../../../pp:display/@streetname='true' and p:streetname!='') or (../../../pp:display/@zipcode='true' and p:zipcode!='') or (../../../pp:display/@city='true' and p:city!='') or (../../../pp:display/@country='true' and p:country!='')">
				<div class="adr">
					<xsl:if test="../../../pp:display/@streetname='true' and p:streetname!=''">
						<span class="street-address"><xsl:value-of select="p:streetname"/></span><br/>
					</xsl:if>
					<xsl:if test="../../../pp:display/@zipcode='true' or ../../../pp:display/@city='true' or ../../../pp:display/@country='true'">
						<xsl:if test="../../../pp:display/@zipcode='true'">
							<span class="postal-code"><xsl:value-of select="p:zipcode"/></span>
						</xsl:if>
						<xsl:text> </xsl:text>
						<xsl:if test="../../../pp:display/@city='true'">
							<span class="locality"><xsl:value-of select="p:city"/></span>
						</xsl:if>
						<xsl:if test="p:country">
							<xsl:text> </xsl:text>
							<span class="country-name"><xsl:value-of select="p:country"/></span>
						</xsl:if>
					</xsl:if>
				</div>
			</xsl:if>
			<xsl:if test="../../../pp:display/@phone_private='true' and p:phone[@context='private']!=''">
				<div><span class="part_person_label">Tlf.: </span>
				<span class="tel"><span class="value"><xsl:value-of select="p:phone[@context='private']"/></span> <span class="type" style="display: none;">home</span></span>
				<span class="part_person_badge"> (privat)</span>
				</div>
			</xsl:if>
			<xsl:if test="../../../pp:display/@phone_private='true'">
				<xsl:for-each select="p:phone[not(@context='private') and not(@context='job')]">
					<div><span class="part_person_label">Tlf.: </span>
					<span class="tel"><span class="value"><xsl:value-of select="."/></span></span>
					<xsl:if test="@context!=''">
						<span class="part_person_badge"> (<xsl:value-of select="@context"/>)</span>
					</xsl:if>
					</div>
				</xsl:for-each>
			</xsl:if>
			<xsl:if test="../../../pp:display/@phone_job='true' and p:phone[@context='job']!=''">
				<div>
					<span class="part_person_label">Tlf.: </span>
					<span class="tel"><span class="value"><xsl:value-of select="p:phone[@context='job']"/></span> <span class="type" style="display: none;">work</span></span>
					<span class="part_person_badge"> (arbejde)</span>
				</div>
			</xsl:if>
			<xsl:if test="../../../pp:display/@email_private='true' and p:email[@context='private']!=''">
				<div class="email">
					<span class="part_person_label">E-mail: </span>
					<a class="common value" href="mailto:{p:email[@context='private']}"><span><xsl:value-of select="p:email[@context='private']"/></span></a>
					<span class="type" style="display: none;">home</span>
					<span class="part_person_badge"> (privat)</span>
				</div>
			</xsl:if>
			<xsl:if test="../../../pp:display/@email_job='true' and p:email[@context='job']!=''">
				<div class="email">
					<span class="part_person_label">E-mail: </span>
					<a class="common value" href="mailto:{p:email[@context='job']}"><span><xsl:value-of select="p:email[@context='job']"/></span></a>
					<span class="type" style="display: none;">work</span>
					<span class="part_person_badge"> (arbejde)</span>
				</div>
			</xsl:if>
			<xsl:if test="../../../pp:display/@email_private='true'">
				<xsl:for-each select="p:email[not(@context)]">
					<div class="email">
					<span class="part_person_label">E-mail: </span>
					<a class="common value" href="mailto:{.}"><span><xsl:value-of select="."/></span></a>
					</div>
				</xsl:for-each>
			</xsl:if>
			<xsl:if test="../../../pp:display/@webaddress='true' and p:webaddress!=''">
				<div>
					<span class="part_person_label">Web: </span>
					<a class="common url" href="{p:webaddress}"><span><xsl:value-of select="p:webaddress"/></span></a>
				</div>
			</xsl:if>
		</div>
		</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="p:image">
	
</xsl:template>

</xsl:stylesheet>