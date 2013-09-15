<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p f h util "
 >
 
<xsl:template name="front">
	<header id="head">
		<h1 id="title">Lotte Munk</h1>
		<p>Skuespiller</p>
	</header>
	
	<section id="broen">
		<div class="left broen_text">
			<h2>Broen II</h2>
			<p>Donec id elit non mi porta gravida at eget metus. Curabitur blandit tempus porttitor.</p>
			<p>DR1, søndag d. 22. september kl. 20:00</p>
			<p><a href="http://www.dr.dk/DR1/Broen/Artikler_med_billeder/201307072112564545.htm">Mere om Broen II »</a></p>
		</div>
		<div class="right" style="background: #eee">
			<div class="vert50  broen_photo2"><xsl:comment/></div>
			<div class="vert50  broen_photo3"><xsl:comment/></div>
		</div>
		<div class="middle broen_photo1"><xsl:comment/></div>
	</section>
	
	<section id="about">
		<h2>Om mig</h2>
		<p>Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id elit non mi porta gravida at eget metus.</p>
		<p class="cv"><a href="{$path}cv/"><span>Curriculum Vitae</span></a></p>
		<ul class="icons">
			<li><a href="http://www.linkedin.com/pub/dir/Lotte/Munk" class="icon-linkedin"><xsl:comment/></a></li>
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
		<div class="press_left"><xsl:comment/></div>
		<article>
			<h2>Fotografier</h2>
			<p><a href="javascript://" class="cv"><span>Flere fotos</span></a></p>
			<p class="links"><a href="javascript://" class="cv"><span>Hent pressekit</span></a></p>
			<p><a href="javascript://" class="cv"><span>Lysbilleder</span></a></p>
		</article>
		<div class="press_right"><xsl:comment/></div>
	</section>

	<section id="video">
		<article>
			<h2>TV<span> · </span>reklame<span> · </span>film</h2>
		</article>
		<div class="teaser">
			<iframe width="640" height="480" src="http://www.youtube.com/embed/9q-HBMSSbp4" frameborder="0" allowfullscreen="allowfullscreen"><xsl:comment/></iframe>
		</div>
	</section>


	<section id="theater">
		<h2>Theater</h2>
		<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Curabitur blandit tempus porttitor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
		<ul class="theaters">
			<li class="theater1">Teamteatret</li>
			<li class="theater2">Anemoneteatret</li>
			<li class="theater3">Teater FÅR302</li>
			<li class="theater4">Husets Teater</li>
			<li class="theater5">Århus Teater</li>
		</ul>
		<div class="photo"><xsl:comment/></div>
	</section>
	
	
	
</xsl:template>
 
</xsl:stylesheet>