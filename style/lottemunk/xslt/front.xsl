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
 xmlns:part="http://uri.in2isoft.com/onlinepublisher/part/1.0/"
 xmlns:imagegallery="http://uri.in2isoft.com/onlinepublisher/part/imagegallery/1.0/"
 xmlns:widget="http://uri.in2isoft.com/onlinepublisher/part/widget/1.0/"
 exclude-result-prefixes="p f h util widget imagegallery o i text header"
 >
 
<xsl:template name="front">

  <xsl:apply-templates select="p:content//widget:widget"/>
      
  <section id="humanise" class="humanise">
    <div class="humanise_body">
      <p>This site has been carefully crafted<br/>
      analysed and executed<br/>
      from the engineering inside<br/>
      to the design on the outside<br/>
      by hand and mind<br/>
      - by Humanise</p>
      <p><a href="http://www.humanise.dk/"><span>Visit Humanise »</span></a></p>
    </div>
  </section>
    
</xsl:template>


<xsl:template match="widget:broen">
  <section id="broen" class="broen js_broen">
    <div class="broen_left broen_text">
      <h2 class="broen_title"><xsl:value-of select="widget:title"/><xsl:comment/></h2>
      <xsl:for-each select="widget:p">
        <p><xsl:value-of select="."/></p>
      </xsl:for-each>
      <p><a class="button" href="{widget:link/@url}"><xsl:value-of select="widget:link"/></a></p>   
    </div>
    <div class="broen_right" style="background: #eee">
      <div class="broen_right_photo broen_photo broen_photo-2"><xsl:comment/></div>
      <div class="broen_right_photo broen_photo broen_photo-3"><xsl:comment/></div>
    </div>
    <div class="broen_middle broen_photo broen_photo-1"><xsl:comment/></div>
  </section>
</xsl:template>


<xsl:template match="widget:about">
  <section id="about" class="about">
    <a name="about"><xsl:comment/></a>
    <h2 class="about_title"><xsl:value-of select="widget:title"/><xsl:comment/></h2>
    <div class="about_body">
      <p class="about_text"><xsl:value-of select="widget:text"/><xsl:comment/></p>
      <div>
        <xsl:choose>
          <xsl:when test="//p:page/p:meta/p:language='en'">
            <a class="button button-right about_button" href="{$path}en/cv/">View my CV</a>
          </xsl:when>
          <xsl:otherwise>
            <a class="button button-right about_button" href="{$path}cv/">Se mit CV</a>
          </xsl:otherwise>
        </xsl:choose>
      </div>
    </div>
    <ul class="about_icons">
      <li class="about_icons_item"><a class="about_icon about_icon-linkedin" href="http://dk.linkedin.com/pub/lotte-munk/18/473/554"><xsl:comment/></a></li>
      <xsl:choose>
        <xsl:when test="//p:page/p:meta/p:language='en'">
          <li class="about_icons_item"><a class="about_icon about_icon-wikipedia" href="http://en.wikipedia.org/wiki/Lotte_Munk"><xsl:comment/></a></li>
        </xsl:when>
        <xsl:otherwise>
          <li class="about_icons_item"><a class="about_icon about_icon-wikipedia" href="http://da.wikipedia.org/wiki/Lotte_Munk"><xsl:comment/></a></li>
        </xsl:otherwise>
      </xsl:choose>
      <li class="about_icons_item"><a class="about_icon about_icon-facebook" href="https://www.facebook.com/Lottemunk69"><xsl:comment/></a></li>
    </ul>
    <div class="about_contact">
      <p class="about_contact_item email">
        <a href="mailto:2be@lottemunk.dk"><span>2be@lottemunk.dk</span></a>
      </p>
      <p class="about_contact_item phone">
        <a href="tel:004526368412"><span>+45 <strong>26 36 84 12</strong></span></a>
      </p>
      <p class="about_contact_item address">
        <a href="http://maps.apple.com/?q=55.639482,12.616404&amp;sspn=0.000774,0.001983&amp;sll=55.639542,12.616527">
          <span>Ny Skelgårdsvej 6<br/>2770 Kastrup, Danmark</span>
        </a>
      </p>
    </div>
  </section>
</xsl:template>

<xsl:template match="widget:photography">
  <div class="photography">
    <h2 class="photography_title">Fotografier</h2>
    <span class="photography_item">
    <span class="photography_photo photography_photo-left js-photo">
      <span class="photography_effect photography_effect-alt js-photo-effect"><xsl:comment/></span>
      <img class="photography_photo_img" src="{$path}services/images/?id=735&amp;width=300&amp;height=480&amp;method=crop&amp;format=jpg"/>
    </span>
    </span>


    <span class="photography_item photography_item-center">
    <span class="photography_photo photography_photo-center js-photo">
      <span class="photography_effect js-photo-effect"><xsl:comment/></span>
      <img class="photography_photo_img" src="{$path}services/images/?id=734&amp;width=418&amp;height=626&amp;method=crop&amp;format=jpg"/>
    </span>
    </span>


    <span class="photography_item">
    <span class="photography_photo photography_photo-right js-photo">
      <span class="photography_effect photography_effect-alt js-photo-effect"><xsl:comment/></span>
      <img class="photography_photo_img" src="{$path}services/images/?id=736&amp;width=300&amp;height=480&amp;method=crop&amp;format=jpg"/>
    </span>
    </span>

    <p class="photography_actions">
      <xsl:choose>
        <xsl:when test="//p:page/p:meta/p:language='en'">
          <a class="photography_action button" href="{$path}en/photos/">More photos</a>
          <a class="photography_action button" href="javascript://" onclick="photoGallery.show();">Slide show</a>
        </xsl:when>
        <xsl:otherwise>
          <a class="photography_action button" href="{$path}fotografier/">Flere fotos</a>
          <a class="photography_action button" href="javascript://" onclick="photoGallery.show();">Lysbilleder</a>
        </xsl:otherwise>
      </xsl:choose>
    </p>
  </div>
  <script type="text/javascript">
      require(['hui.ui.ImageViewer'],function() {
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
</xsl:template>
 
<xsl:template match="widget:photos">
  <div id="pressphotos" class="photos">
      <a name="photos"><xsl:comment/></a>
      <div class="photos_photo photos_photo-left"><xsl:comment/></div>
      <div class="photos_middle">
        <h2 class="photos_title"><xsl:value-of select="widget:title"/><xsl:comment/></h2>
        <ul class="photos_items">
        <xsl:choose>
          <xsl:when test="//p:page/p:meta/p:language='en'">
            <li class="photos_item"><a class="photos_link" href="{$path}en/photos/">More photos</a></li>
            <li class="photos_item"><a class="photos_link" href="javascript://" onclick="photoGallery.show();">Slide show</a></li>
          </xsl:when>
          <xsl:otherwise>
            <li class="photos_item"><a class="photos_link" href="{$path}fotografier/">Flere fotos</a></li>
            <li class="photos_item"><a class="photos_link" href="javascript://" onclick="photoGallery.show();">Lysbilleder</a></li>
          </xsl:otherwise>
        </xsl:choose>
        </ul>
      </div>
      <div class="photos_photo photos_photo-right"><xsl:comment/></div>
      
    <script type="text/javascript">
        require(['hui.ui.ImageViewer'],function() {
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
  </div>
</xsl:template> 

<xsl:template match="widget:reel">
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
</xsl:template>

<xsl:template match="widget:movies">
  <div class="movies">
    <h2 class="movies_title"><xsl:value-of select="widget:title"/></h2>
    <p class="movies_text"><xsl:apply-templates select="widget:text"/></p>
    <div class="movies_body">
      <div class="movies_more">
        <xsl:for-each select="widget:movie">
        <div class="movies_item">
          <div class="movies_video js-movie-poster" data-key="{@key}">
            <span class="movies_video_title"><xsl:value-of select="@title"/></span>
          </div>
        </div>
        </xsl:for-each>
      </div>
    </div>
    <p class="movies_actions">
      <xsl:for-each select="widget:button">
        <a class="button button-right movies_button" href="{$path}{@path}"><xsl:value-of select="."/></a>
      </xsl:for-each>
    </p>
  </div>
</xsl:template>

<xsl:template match="widget:movies//widget:br"><br/></xsl:template>

<xsl:template match="widget:video">
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
                  <p>Clip from <span>The Bridge II</span></p>
              </xsl:when>
              <xsl:otherwise>
                  <p>Klip fra <span>Broen II</span></p>
              </xsl:otherwise>
          </xsl:choose>
      </div>
  </section>
</xsl:template>

<xsl:template match="widget:theater">
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
</xsl:template>

<xsl:template match="widget:communication">
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
</xsl:template>

</xsl:stylesheet>