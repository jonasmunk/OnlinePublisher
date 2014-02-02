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
		<p id="job">
			<xsl:choose>
				<xsl:when test="//p:page/p:meta/p:language='en'"><xsl:text>Actress</xsl:text></xsl:when>
				<xsl:otherwise><xsl:text>Skuespiller</xsl:text></xsl:otherwise>
			</xsl:choose>
		</p>
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

			<xsl:choose>
				<xsl:when test="//p:page/p:meta/p:language='en'">
					<h2>The Bridge II</h2>
					<p>I play Caroline Brandstrup in episodes 3 through 10</p>
					<p>Can be seen on BBC Four from 4 January 2014</p>
					<p><a class="button" href="http://www.bbc.co.uk/programmes/b03bnc34">More about The Bridge II &#8250;</a></p>									
				</xsl:when>
				<xsl:otherwise>
					<h2>Broen II</h2>
					<p>Aktuel i Broen II - spiller Caroline Brandstrup i afsnittene 3-10</p>
					<p>Starter på DR1, søndag d. 22. september kl. 20:00</p>
					<p><a class="button" href="http://www.dr.dk/dr1/broen">Mere om Broen II &#8250;</a></p>					
				</xsl:otherwise>
			</xsl:choose>
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
		<p class="cv">
			<xsl:choose>
				<xsl:when test="//p:page/p:meta/p:language='en'">
					<a class="button" href="{$path}en/cv/"><span>View my CV &#8250;</span></a>
				</xsl:when>
				<xsl:otherwise>
					<a class="button" href="{$path}cv/"><span>Se mit CV &#8250;</span></a>
				</xsl:otherwise>
			</xsl:choose>
		</p>
		</div>
		<ul class="icons">
			<li><a href="http://dk.linkedin.com/pub/lotte-munk/18/473/554" class="icon-linkedin"><xsl:comment/></a></li>
			<li><a href="http://da.wikipedia.org/wiki/Lotte_Munk" class="icon-wikipedia"><xsl:comment/></a></li>
			<li><a href="https://www.facebook.com/Lottemunk69" class="icon-facebook"><xsl:comment/></a></li>
		</ul>
		<div class="contact">
			<p class="address"><span class="icon icon-map"><xsl:comment/></span><a href="http://maps.apple.com/?q=55.639482,12.616404&amp;sspn=0.000774,0.001983&amp;sll=55.639542,12.616527"><span>Ny Skelgårdsvej 6<br/>2770 Kastrup, Danmark</span></a></p>
			<p class="email"><span class="icon icon-mail"><xsl:comment/></span><a href="mailto:2be@lottemunk.dk"><span>2be@lottemunk.dk</span></a></p>
			<p class="phone"><span class="icon icon-phone"><xsl:comment/></span><a href="tel:004526368412"><span>+45 <strong>26 36 84 12</strong></span></a></p>
		</div>
	</section>
	
	
	
	<section id="pressphotos">
		<!--
		<div id="background1">
			<div><xsl:comment/></div>
		</div>
			-->
		<div class="block">
		<a name="photos"><xsl:comment/></a>
			<div class="press_left"><xsl:comment/></div>
			<article>
				<h2><xsl:value-of select="//header:header[@level=2]"/><xsl:comment/></h2>
				
				<xsl:choose>
					<xsl:when test="//p:page/p:meta/p:language='en'">
						<p><a href="{$path}en/photos/"><span>More photos &#8250;</span></a></p>
						<p><a href="javascript://" onclick="photoGallery.show();"><span>Slide show &#8250;</span></a></p>
					</xsl:when>
					<xsl:otherwise>
						<p><a href="{$path}fotografier/"><span>Flere fotos &#8250;</span></a></p>
						<p><a href="javascript://" onclick="photoGallery.show();"><span>Lysbilleder &#8250;</span></a></p>
					</xsl:otherwise>
				</xsl:choose>
			</article>
			<div class="press_right"><xsl:comment/></div>
		</div>
		
		<script type="text/javascript">
			hui.onReady(function() {
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
			});
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
			<xsl:choose>
				<xsl:when test="//p:page/p:meta/p:language='en'">
					<h2>TV<span> · </span>Adds<span> · </span>Movies</h2>
					<p><a href="{$path}en/movie-clips/"><span>More clips &#8250;</span></a></p>
				</xsl:when>
				<xsl:otherwise>
					<h2>TV<span> · </span>reklame<span> · </span>film</h2>
					<p><a href="{$path}film/"><span>Flere filmklip &#8250;</span></a></p>
				</xsl:otherwise>
			</xsl:choose>
		</article>
		<div class="teaser" id="video_poster">
			<a class="icon-play"><xsl:comment/></a>
			<xsl:choose>
				<xsl:when test="//p:page/p:meta/p:language='en'">
					<p>Clip from The Killing II</p>
				</xsl:when>
				<xsl:otherwise>
					<p>Klip fra Forbrydelsen II</p>
				</xsl:otherwise>
			</xsl:choose>
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
			<xsl:choose>
				<xsl:when test="//p:page/p:meta/p:language='en'">
					<h2>Theater</h2>
					<p>Art can express complicated stories concerning the essence of humanity, and of our terms of life on earth, art can dream big and scandalously, unfold existence in all its grandeur and horror.</p>
				</xsl:when>
				<xsl:otherwise>
					<h2>Teater</h2>
					<p>Kunsten kan fortælle indviklede historier om menneskets væsen og vilkår i verden, den kan drømme stort og skandaløst, folde eksistensen ud i al sin storhed og gru.</p>
				</xsl:otherwise>
			</xsl:choose>
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
		<xsl:choose>
			<xsl:when test="//p:page/p:meta/p:language='en'">
				<p class="link"><a class="button" href="{$path}en/cv/"><span>View my CV &#8250;</span></a></p>
			</xsl:when>
			<xsl:otherwise>
				<p class="link"><a class="button" href="{$path}cv/"><span>Se mit CV &#8250;</span></a></p>
			</xsl:otherwise>
		</xsl:choose>
	</section>
	
	<section id="communication">
		<article>
				<a name="communication"><xsl:comment/></a>
			<div>

				<xsl:choose>
					<xsl:when test="//p:page/p:meta/p:language='en'">
						<h2>Coaching</h2>
						<p>I also use the technique of acting in my work as communication coach, where I utilise the <strong>tools of theatrical work</strong> to give participants the opportunity to <strong>learn by doing</strong>. It is always my goal to bring forward personal insights for the individual person, and an awareness of their own means of communication. Giving very personal and <strong>constructive feedback</strong> is of very high priority for me.</p>
						<p class="link"><a class="button" href="{$path}en/communication-training/"><span>About Coaching &#8250;</span></a></p>
					</xsl:when>
					<xsl:otherwise>
						<h2>Kommunikations<span>træning</span></h2>
						<p>Skuespillerteknikken anvender jeg også som kommunikationsrådgiver, hvor jeg bruger <strong>teaterets redskaber</strong> til at give en <strong>oplevelsesbaseret læring</strong>.  Jeg prøver altid  at formidle en indsigt i det enkelte menneskes måde at kommunikere på. At give en meget personlig og <strong>konstruktiv feedback</strong>, er noget jeg vægter meget højt.</p>
						<p class="link"><a class="button" href="{$path}kommunikation/"><span>Mere om kommunikationstræning &#8250;</span></a></p>
					</xsl:otherwise>
				</xsl:choose>
				
				
			</div>
			<figure><xsl:comment/></figure>
		</article>
	</section>
	
	<section id="humanise">
		<article>
			<p>This site has been carefully crafted<br/>
			analysed and executed<br/>
			from the engineering inside<br/>
			to the design on the outside<br/>
			by hand and mind<br/>
			- by Humanise</p>
			<p><a href="http://www.humanise.dk/"><span>Visit Humanise »</span></a></p>
		</article>
	</section>
	
	
</xsl:template>
 
</xsl:stylesheet>