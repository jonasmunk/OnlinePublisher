<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 xmlns:header="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"
 xmlns:text="http://uri.in2isoft.com/onlinepublisher/part/text/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:imagegallery="http://uri.in2isoft.com/onlinepublisher/part/imagegallery/1.0/"
 exclude-result-prefixes="p f h util "
 >
 
<xsl:template name="front">
	<header id="head">
		<a name="top"><xsl:comment/></a>
		<h1 id="title">Lotte Munk</h1>
		<p id="job">Skuespiller</p>
		<nav id="navigation">
			<ul>
				<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
			</ul>				
		</nav>
		<!--
		<nav>
			<ul>
				<li><a href="#about">Om mig</a></li>
				<li><a href="#photos">Fotos</a></li>
				<li><a href="#movies">Film &amp; TV</a></li>
				<li><a href="#theater">Teater</a></li>
				<li><a href="#communication">Kommunikationstræning</a></li>
			</ul>
		</nav>-->
	</header>
	
	<section id="broen">
		<div class="left broen_text">
			<h2>Broen II</h2>
			<p>Donec id elit non mi porta gravida at eget metus. Curabitur blandit tempus porttitor.</p>
			<p>DR1, søndag d. 22. september kl. 20:00</p>
			<p><a class="button" href="http://www.dr.dk/dr1/broen">Mere om Broen II &#8250;</a></p>
		</div>
		<div class="right" style="background: #eee">
			<div class="vert50  broen_photo2"><xsl:comment/></div>
			<div class="vert50  broen_photo3"><xsl:comment/></div>
		</div>
		<div class="middle broen_photo1"><xsl:comment/></div>
	</section>
	
	<section id="about">
		<a name="about"><xsl:comment/></a>
		<h2><xsl:value-of select="//header:header[1]"/></h2>
		<div class="text">
		<p class="text">
		<xsl:choose>
			<xsl:when test="//text:text[1]">
				<xsl:value-of select="//text:text[1]"/>
		  	</xsl:when>
		  	<xsl:otherwise>
				<xsl:text>
					Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id elit non mi porta gravida at eget metus.
				</xsl:text>
		  	</xsl:otherwise>
		</xsl:choose>
		</p>
		<p class="cv"><a class="button" href="{$path}cv/"><span>Se mit CV &#8250;</span></a></p>
		</div>
		<ul class="icons">
			<li><a href="http://dk.linkedin.com/pub/lotte-munk/18/473/554" class="icon-linkedin"><xsl:comment/></a></li>
			<li><a href="http://da.wikipedia.org/wiki/Lotte_Munk" class="icon-wikipedia"><xsl:comment/></a></li>
			<li><a href="https://www.facebook.com/Lottemunk69" class="icon-facebook"><xsl:comment/></a></li>
			<!--
			<li><a href="#" class="icon-twitter"><xsl:comment/></a></li>
			-->
		</ul>
		<div class="contact">
			<p class="address"><span class="icon icon-map"><xsl:comment/></span>Ny Skelgårdsvej 6<br/>2770 Kastrup, Danmark</p>
			<p class="email"><span class="icon icon-mail"><xsl:comment/></span><a href="mailto:2be@lottemunk.dk"><span>2be@lottemunk.dk</span></a></p>
			<p class="phone"><span class="icon icon-phone"><xsl:comment/></span>+45 <strong>26 36 84 12</strong></p>
		</div>
	</section>
	
	
	
	<section id="pressphotos">
		<div id="background1">
			<div><xsl:comment/></div>
		</div>
		<div class="block">
		<a name="photos"><xsl:comment/></a>
			<div class="press_left"><xsl:comment/></div>
			<article>
				<h2><xsl:value-of select="//header:header[@level=2]"/><xsl:comment/></h2>
				<p><a href="{$path}fotografier/"><span>Flere fotos &#8250;</span></a></p>
				<p><a href="javascript://"><xsl:attribute name="onclick">hui.ui.msg({text:'Kommer snart',duration:2000})</xsl:attribute><span>Hent pressekit &#8250;</span></a></p>
				<p><a href="javascript://" onclick="photoGallery.show();"><span>Lysbilleder &#8250;</span></a></p>
			</article>
			<div class="press_right"><xsl:comment/></div>
		</div>
		
		<script type="text/javascript">
			(function() {
				var images = [];
				<xsl:for-each select="//imagegallery:imagegallery//o:object">
					images.push({
						id : <xsl:value-of select="@id"/>,
						width : <xsl:value-of select="o:sub/i:image/i:width"/>,
						height : <xsl:value-of select="o:sub/i:image/i:height"/>,
						text : '<xsl:value-of select="o:note"/>'
					})
				</xsl:for-each>

				window.photoGallery = hui.ui.ImageViewer.create({
					maxWidth : 2000,
					maxHeight : 2000,
					perimeter : 40,
					sizeSnap : 10,
					images : images,
					listener : op.imageViewerDelegate
				});
			})();
		</script>
	</section>
	
	<section id="reel">
		<div class="content" id="reelContent">
		<div class="holes"><xsl:comment/></div>
		<div class="frames">
			<figure class="frame1"><xsl:comment/></figure>
			<figure class="frame2"><xsl:comment/></figure>
			<figure class="frame3"><xsl:comment/></figure>
			<figure class="frame4"><xsl:comment/></figure>
			<figure class="frame5"><xsl:comment/></figure>
			<figure class="frame6"><xsl:comment/></figure>
			<figure class="frame7"><xsl:comment/></figure>
			<figure class="frame8"><xsl:comment/></figure>
		</div>
		<div class="holes"><xsl:comment/></div>
		</div>
	</section>
	<!--
	<div id="background2">
		<div><xsl:comment/></div>
	</div>
-->
	<section id="video">
		<a name="movies"><xsl:comment/></a>
		<article>
			<h2>TV<span> · </span>reklame<span> · </span>film</h2>
			<p><a href="{$path}film/"><span>Flere filmklip &#8250;</span></a></p>
		</article>
		<div class="teaser">
			<iframe width="640" height="480" src="http://www.youtube.com/embed/9q-HBMSSbp4" frameborder="0" allowfullscreen="allowfullscreen"><xsl:comment/></iframe>
		</div>
	</section>
<!--	
	<div id="background3">
		<div><xsl:comment/></div>
	</div>
-->

	<section id="theater">
		<a name="theater"><xsl:comment/></a>
		<article>
			<h2>Teater</h2>
			<p>Kunsten kan fortælle indviklede historier om menneskets væsen og vilkår i verden, den kan drømme stort og skandaløst, folde eksistensen ud i al sin storhed og gru.</p>
			<p class="link"><a class="button" href="{$path}cv/"><span>Se mit CV &#8250;</span></a></p>
		</article>
		<ul class="theaters">
			<li class="theater1">Teamteatret</li>
			<li class="theater2">Det Kongelige Teater</li>
			<li class="theater3">Teater FÅR302</li>
			<li class="theater4">Husets Teater</li>
			<li class="theater5">Anemoneteatret</li>
			<li class="theater6">Århus Teater</li>
		</ul>
		<div class="photo"><xsl:comment/></div>
	</section>
	
	<section id="communication">
		<article>
			<div>
				<a name="communication"><xsl:comment/></a>
			<h2>Kommunikationstræning</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			<p class="link"><a class="button" href="{$path}kommunikation/"><span>Mere om kommunktionstræning &#8250;</span></a></p>
			</div>
			<figure><xsl:comment/></figure>
		</article>
	</section>
	
</xsl:template>
 
</xsl:stylesheet>