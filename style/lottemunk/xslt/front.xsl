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


<xsl:template match="widget:about">
  <section id="about" class="about block">
    <h2 class="block_title about_title"><xsl:value-of select="widget:title"/><xsl:comment/></h2>
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
      <li class="about_icons_item">
        <a class="about_icon about_icon-youtube" href="https://www.youtube.com/channel/UCkyzyp5M68jcfZ-phZdPX4Q/videos">
          <span class="about_icon_text">YouTube</span>
        </a>
      </li>
      <li class="about_icons_item">
        <a class="about_icon about_icon-linkedin" href="http://dk.linkedin.com/pub/lotte-munk/18/473/554">
          <span class="about_icon_text">LinkedIn</span>
        </a>
      </li>
      <xsl:choose>
        <xsl:when test="//p:page/p:meta/p:language='en'">
          <li class="about_icons_item"><a class="about_icon about_icon-wikipedia" href="http://en.wikipedia.org/wiki/Lotte_Munk">
            <span class="about_icon_text">Wikipedia</span>
        </a></li>
        </xsl:when>
        <xsl:otherwise>
          <li class="about_icons_item"><a class="about_icon about_icon-wikipedia" href="http://da.wikipedia.org/wiki/Lotte_Munk">
            <span class="about_icon_text">Wikipedia</span>
          </a></li>
        </xsl:otherwise>
      </xsl:choose>
      <li class="about_icons_item">
        <a class="about_icon about_icon-facebook" href="https://www.facebook.com/Lottemunk69">
          <span class="about_icon_text">Facebook</span>
        </a>
      </li>
    </ul>
    <div class="about_contact">
      <p class="about_contact_item">
        <a class="about_contact_link about_email" href="mailto:2be@lottemunk.dk"><span>2be@lottemunk.dk</span></a>
      </p>
      <p class="about_contact_item">
        <a class="about_contact_link about_phone" href="tel:004526368412"><span>+45 <strong>26 36 84 12</strong></span></a>
      </p>
      <p class="about_contact_item">
        <a class="about_contact_link about_address" href="http://maps.apple.com/?q=55.639482,12.616404&amp;sspn=0.000774,0.001983&amp;sll=55.639542,12.616527">
          <span>Ny Skelgårdsvej 6<br/>2770 Kastrup, Danmark</span>
        </a>
      </p>
    </div>
  </section>
</xsl:template>

<xsl:template match="widget:photography">
  
  <div class="photography block">
    <a name="photos"><xsl:comment/></a>
    <h2 class="block_title photography_title">Fotografier</h2>
    <span class="photography_item">
      <span class="photography_photo photography_photo-left js-photo" data-id="{widget:photo[1]/@id}">
        <span class="photography_effect photography_effect-alt js-photo-effect"><xsl:comment/></span>
        <img class="photography_photo_img" src="{$path}services/images/?id={widget:photo[1]/@id}&amp;width=300&amp;height=480&amp;method=crop&amp;sharpen=true&amp;format=jpg"/>
      </span>
    </span>


    <span class="photography_item photography_item-center">
      <span class="photography_photo photography_photo-center js-photo" data-id="{widget:photo[2]/@id}">
        <span class="photography_effect js-photo-effect"><xsl:comment/></span>
        <img class="photography_photo_img" src="{$path}services/images/?id={widget:photo[2]/@id}&amp;width=418&amp;height=626&amp;method=crop&amp;sharpen=true&amp;format=jpg"/>
      </span>
    </span>


    <span class="photography_item">
      <span class="photography_photo photography_photo-right js-photo" data-id="{widget:photo[3]/@id}">
        <span class="photography_effect photography_effect-alt js-photo-effect"><xsl:comment/></span>
        <img class="photography_photo_img" src="{$path}services/images/?id={widget:photo[3]/@id}&amp;width=300&amp;height=480&amp;method=crop&amp;sharpen=true&amp;format=jpg"/>
      </span>
    </span>

    <p class="photography_actions">
      <xsl:choose>
        <xsl:when test="//p:page/p:meta/p:language='en'">
          <a class="photography_action button" href="javascript://" onclick="hui.ui.get('photoGallery').show();">Slide show</a>
          <a class="photography_action button button-right" href="{$path}en/photos/">More photos</a>
        </xsl:when>
        <xsl:otherwise>
          <a class="photography_action button" href="javascript://" onclick="hui.ui.get('photoGallery').show();">Lysbilleder</a>
          <a class="photography_action button button-right" href="{$path}fotografier/">Flere fotos</a>
        </xsl:otherwise>
      </xsl:choose>
    </p>
  </div>
  <script type="text/javascript">
      require(['hui.ui.ImageViewer','op'],function() {
          var images = [];
          <xsl:for-each select="//imagegallery:imagegallery//o:object">
              images.push({
                  id : <xsl:value-of select="@id"/>,
                  width : <xsl:value-of select="o:sub/i:image/i:width"/>,
                  height : <xsl:value-of select="o:sub/i:image/i:height"/>,
                  text : '<xsl:value-of select="o:note"/>'
              })
          </xsl:for-each>

          hui.ui.ImageViewer.create({
            name : 'photoGallery',
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

<xsl:template match="widget:movies">
  <div class="movies block">
    <h2 class="block_title movies_title"><xsl:value-of select="widget:title"/></h2>
    <p class="movies_text"><xsl:apply-templates select="widget:text"/></p>
    <div class="movies_body">
      <div class="movies_more">
        <xsl:for-each select="widget:movie">
        <div class="movies_item">
          <div data-video="{@video}">
            <xsl:attribute name="class">
              <xsl:text>movies_video js-movie-poster</xsl:text>
              <xsl:text> movies_video-</xsl:text><xsl:value-of select="@key"/>
              <xsl:if test="position()=1">
                <xsl:text> movies_video-highlighted</xsl:text>
              </xsl:if>
            </xsl:attribute>
            <span class="movies_video_title"><xsl:value-of select="@title"/></span>
          </div>
        </div>
        </xsl:for-each>
      </div>
    </div>
    <p class="movies_actions">
      <a class="button movies_button" href="https://www.youtube.com/channel/UCkyzyp5M68jcfZ-phZdPX4Q/videos">YouTube-kanal</a>
      <xsl:for-each select="widget:button">
        <a class="button button-right movies_button" href="{$path}{@path}"><xsl:value-of select="."/></a>
      </xsl:for-each>
    </p>
  </div>
</xsl:template>

<xsl:template match="widget:movies//widget:br"><br/></xsl:template>


<xsl:template match="widget:theater">
  <div class="theater js-theater" id="theater">
      <a name="theater"><xsl:comment/></a>
      <article class="theater_body">
          <xsl:choose>
              <xsl:when test="//p:page/p:meta/p:language='en'">
                  <h2 class="theater_title">Theater</h2>
                  <p class="theater_text">Art can express complicated stories concerning the essence of humanity, and of our terms of life on earth, art can dream big and scandalously, unfold existence in all its grandeur and horror.</p>
              </xsl:when>
              <xsl:otherwise>
                  <h2 class="theater_title">Teater</h2>
                  <p class="theater_text">Kunsten kan fortælle indviklede historier om menneskets væsen og vilkår i verden, den kan drømme stort og skandaløst, folde eksistensen ud i al sin storhed og gru.</p>
              </xsl:otherwise>
          </xsl:choose>
      </article>
      <ul class="theater_stages">
        <li class="theater_stage theater_stage-1">Teamteatret</li>
        <li class="theater_stage theater_stage-2">Det Kongelige Teater</li>
        <li class="theater_stage theater_stage-3">Teater FÅR302</li>
        <li class="theater_stage theater_stage-4">Husets Teater</li>
        <li class="theater_stage theater_stage-5">Anemoneteatret</li>
        <li class="theater_stage theater_stage-6">Århus Teater</li>
      </ul>
      <div class="theater_photo js-theater-photo"><xsl:comment/></div>
      <p class="theater_actions">
        <xsl:choose>
          <xsl:when test="//p:page/p:meta/p:language='en'">
            <a class="button button-dark button-right" href="{$path}en/cv/">View my CV</a>
          </xsl:when>
          <xsl:otherwise>
            <a class="button button-dark button-right" href="{$path}cv/">Se mit CV</a>
          </xsl:otherwise>
        </xsl:choose>
      </p>
  </div>
</xsl:template>

<xsl:template match="widget:communication">
  <section id="communication" class="block communication">
      <article>
        <a name="communication"><xsl:comment/></a>
        <div>
            <xsl:choose>
                <xsl:when test="//p:page/p:meta/p:language='en'">
                    <h2 class="block_title communication_title">Coaching</h2>
                    <p class="communication_text">I also use the technique of acting in my work as communication coach, where I utilise the <strong>tools of theatrical work</strong> to give participants the opportunity to <strong>learn by doing</strong>. It is always my goal to bring forward personal insights for the individual person, and an awareness of their own means of communication. Giving very personal and <strong>constructive feedback</strong> is of very high priority for me.</p>
                    <p class="communication_actions"><a class="button button-right" href="{$path}en/communication-training/"><span>About Coaching</span></a></p>
                </xsl:when>
                <xsl:otherwise>
                    <h2 class="block_title communication_title">Kommunikations<span class="communication_title_part">træning</span></h2>
                    <p class="communication_text">Skuespillerteknikken anvender jeg også som kommunikationsrådgiver, hvor jeg bruger <strong>teaterets redskaber</strong> til at give en <strong>oplevelsesbaseret læring</strong>.  Jeg prøver altid  at formidle en indsigt i det enkelte menneskes måde at kommunikere på. At give en meget personlig og <strong>konstruktiv feedback</strong>, er noget jeg vægter meget højt.</p>
                    <p class="communication_actions"><a class="button button-dense button-right" href="{$path}kommunikation/"><span>Mere om kommunikationstræning</span></a></p>
                </xsl:otherwise>
            </xsl:choose>              
          </div>
          <figure class="communication_image"><xsl:comment/></figure>
      </article>
  </section>
</xsl:template>

</xsl:stylesheet>