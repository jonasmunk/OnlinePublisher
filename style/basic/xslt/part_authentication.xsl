<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:a="http://uri.in2isoft.com/onlinepublisher/part/authentication/1.0/"
 exclude-result-prefixes="a"
 >

<xsl:template match="a:authentication">
	<div class="part_authentication" id="part_authentication_{generate-id()}">
		<xsl:if test="$userid>0">
            <p>
                <strong><xsl:text>Logget ind som: </xsl:text></strong><xsl:value-of select="$usertitle"/>
                <xsl:text> </xsl:text>
                <a href="?logout=true" class="common common_link"><span class="common_link_text">log ud</span></a>
            </p>
		</xsl:if>
        <form class="part_authentication_form" action="{$path}services/authentication">
            <p class="part_authentication_field">
                <label class="part_authentication_label">Brugernavn</label>
                <input class="part_authentication_input part_authentication_username common_input" name="username"/>
            </p>
            <p class="part_authentication_field">
                <label class="part_authentication_label">Kodeord</label>
                <input class="part_authentication_input part_authentication_password common_input" type="password" name="password"/>
            </p>
            <p class="part_authentication_actions">
                <button class="part_authentication_login common_button">Log ind</button>
            </p>
        </form>
    </div>
    
	<script type="text/javascript">_editor.loadPart({
        name : 'Authentication',$ready : function() {
            new op.part.Authentication({element : 'part_authentication_<xsl:value-of select="generate-id()"/>'});
        }
    });
    </script>
</xsl:template>

</xsl:stylesheet>