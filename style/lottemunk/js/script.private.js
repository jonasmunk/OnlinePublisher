if(!op){var op={}}op.preview=false;op.page={id:null,path:null,template:null};op.ignite=function(){if(!this.preview){document.onkeydown=function(b){b=hui.event(b);if(b.returnKey&&b.shiftKey){b.stop();var a;a=function(c){c=hui.event(c);if(c.returnKey){hui.unListen(document,"keyup",a);if(!hui.browser.msie&&!op.user.internal){c.stop();op.showLogin()}else{window.location=(op.page.path+"Editor/index.php?page="+op.page.id)}}};hui.listen(document,"keyup",a)}return true};hui.request({url:op.context+"services/statistics/",parameters:{page:op.page.id,referrer:document.referrer,uri:document.location.href}})}if(hui.browser.msie7){hui.onReady(function(){hui.cls.add(document.body.parentNode,"msie7")})}};op.showLogin=function(){if(!this.loginBox){if(this.loadingLogin){hui.log("Aborting, the box is loading");return}this.loadingLogin=true;hui.ui.showMessage({text:{en:"Loading...",da:"Indlæser..."},busy:true,delay:300});hui.ui.require(["Formula","Button","TextField"],function(){hui.ui.hideMessage();var h=this.loginBox=hui.ui.Box.create({width:300,title:{en:"Access control",da:"Adgangskontrol"},modal:true,absolute:true,closable:true,curtainCloses:true,padding:10});this.loginBox.addToDocument();var f=this.loginForm=hui.ui.Formula.create();f.listen({$submit:function(){if(!h.isVisible()){return}var b=f.getValues();op.login(b.username,b.password)}});var e=f.buildGroup(null,[{type:"TextField",options:{label:{en:"Username",da:"Brugernavn"},key:"username"}},{type:"TextField",options:{label:{en:"Password",da:"Kodeord"},key:"password",secret:true}}]);var a=e.createButtons();var c=hui.ui.Button.create({text:{en:"Forgot password?",da:"Glemt kode?"}});c.listen({$click:function(){document.location=op.context+"Editor/Authentication.php?forgot=true"}});a.add(c);var d=hui.ui.Button.create({text:{en:"Cancel",da:"Annuller"}});d.listen({$click:function(){f.reset();h.hide();document.body.focus()}});a.add(d);a.add(hui.ui.Button.create({text:{en:"Log in",da:"Log ind"},highlighted:true,submit:true}));this.loginBox.add(f);this.loginBox.show();window.setTimeout(function(){f.focus()},100);this.loadingLogin=false;op.startListening();var i=new hui.Preloader({context:hui.ui.context+"hui/icons/"});i.addImages("common/success24.png");i.load()}.bind(this))}else{this.loginBox.show();this.loginForm.focus()}};op.startListening=function(){hui.listen(window,"keyup",function(c){c=hui.event(c);if(c.escapeKey&&this.loginBox.isVisible()){this.loginBox.hide();var b=hui.get.firstByTag(document.body,"a");if(b){b.focus();b.blur()}document.body.blur()}}.bind(this))};op.login=function(b,a){if(hui.isBlank(b)||hui.isBlank(a)){hui.ui.showMessage({text:{en:"Please fill in both fields",da:"Udfyld venligst begge felter"},duration:3000});this.loginForm.focus();return}hui.ui.request({message:{start:{en:"Logging in...",da:"Logger ind..."},delay:300},url:op.context+"Editor/Services/Core/Authentication.php",parameters:{username:b,password:a},onJSON:function(c){if(c.success){hui.ui.showMessage({text:{en:"You are now logged in",da:"Du er nu logget ind"},icon:"common/success",duration:4000});op.igniteEditor()}else{hui.ui.showMessage({text:{en:"The user was not found",da:"Brugeren blev ikke fundet"},icon:"common/warning",duration:4000})}},onFailure:function(){hui.ui.showMessage({text:{en:"An internal error occurred",da:"Der skete en fejl internt i systemet"},icon:"common/warning",duration:4000})}})};op.igniteEditor=function(){window.location=(op.page.path+"Editor/index.php?page="+op.page.id)};op.showImage=function(a){if(!this.imageViewer){this.imageViewer=hui.ui.ImageViewer.create({maxWidth:2000,maxHeight:2000,perimeter:40,sizeSnap:10});this.imageViewer.listen(op.imageViewerDelegate)}this.imageViewer.clearImages();this.imageViewer.addImage(a);this.imageViewer.show()};op.registerImageViewer=function(b,a){hui.get(b).onclick=function(){op.showImage(a);return false}};op.imageViewerDelegate={$resolveImageUrl:function(c,e,a){var b=c.width?Math.min(e,c.width):e;var d=c.height?Math.min(a,c.height):a;return op.page.path+"services/images/?id="+c.id+"&width="+b+"&height="+d+"&format=jpg&quality=100"}};op.showVideo=function(a){if(!this.videoViewer){this.videoViewer=new op.VideoViewer()}this.videoViewer.show(a)};op.VideoViewer=function(){this.box=hui.ui.Box.create(null,{absolute:true,modal:true});this.box.addToDocument();this.content=new Element("div").setStyle({width:"400px",height:"200px"});this.box.add(this.content);this.box.listen(this)};op.VideoViewer.prototype={show:function(a){this.content.setStyle({width:a.width+"px",height:a.height+"px"});this.content.insert(this.buildQuickTime(a));this.box.show()},buildFlash:function(){var a=new SWFObject("player.swf","ply","328","200","9","#FFFFFF");a.addParam("allowfullscreen","true");a.addParam("allowscriptaccess","always");a.addParam("flashvars","file=video.flv&image=preview.jpg");a.write("container")},buildQuickTime:function(a){var b=new Element("object",{width:a.width,height:a.height,type:"video/quicktime",data:a.file});return b},boxCurtainWasClicked:function(){this.box.hide();this.content.update()}};if(op.part===undefined){op.part={}}op.feedback=function(b){hui.require(op.page.path+"style/basic/js/Feedback.js",function(){op.feedback.Controller.init(b)})};op.part.ImageGallery=function(a){this.options=a;this.element=hui.get(a.element);this.images=[]};op.part.ImageGallery.prototype={registerImage:function(b,c){b=hui.get(b);if(this.options.editor){return}this.images.push(c);var a=this;b.onclick=function(d){hui.stop(d);a.showImage(c.id);return false}},showImage:function(a){if(!this.imageViewer){this.imageViewer=hui.ui.ImageViewer.create({maxWidth:2000,maxHeight:2000,perimeter:40,sizeSnap:10});this.imageViewer.listen(op.imageViewerDelegate)}this.imageViewer.clearImages();this.imageViewer.addImages(this.images);this.imageViewer.showById(a)},init:function(){if(this.options.variant=="changing"){op.part.ImageGallery.changing.init(this.element)}}};op.part.ImageGallery.changing={init:function(d){var a=hui.get.byClass(d,"part_imagegallery_item");if(a.length==0){return}d.style.height=d.clientHeight+"px";for(var c=0;c<a.length;c++){a[c].style.position="absolute"}var g;var b=-1;var f=1;var e=true;g=function(){if(b>-1){hui.animate(a[b],"opacity",0,200,{hideOnComplete:true,delay:800})}b++;if(b>a.length-1){b=0}if(!e){hui.style.set(a[b],{opacity:0,zIndex:f,display:"block"});hui.animate(a[b],"opacity",1,1000,{ease:hui.ease.slowFastSlow})}window.setTimeout(g,3000);f++;e=false};g()}};op.part.Formula=function(a){this.element=hui.get(a.element);this.id=a.id;this.inputs=a.inputs;hui.listen(this.element,"submit",this._send.bind(this))};op.part.Formula.prototype={_send:function(h){hui.stop(h);var g=[];for(var f=0;f<this.inputs.length;f++){var b=this.inputs[f];var j=hui.get(b.id);var c=b.validation;if(c.required){if(hui.isBlank(j.value)){hui.ui.showMessage({text:c.message,duration:2000});j.focus();return}}if(c.syntax=="email"&&!hui.isBlank(j.value)){var k=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\\n".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA\n-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;if(!k.test(j.value)){hui.ui.showMessage({text:c.message,duration:2000});j.focus();return}}g.push({label:b.label,value:j.value})}var a=op.page.path+"services/parts/formula/";var d={id:this.id,fields:g};hui.ui.showMessage({text:"Sender besked...",busy:true});hui.ui.request({url:a,json:{data:d},onSuccess:this._onSuccess.bind(this),onFailure:this._onFailure.bind(this)})},_onSuccess:function(){hui.ui.showMessage({text:"Beskeden er nu sendt",icon:"common/success",duration:2000});this.element.reset()},_onFailure:function(){hui.ui.showMessage({text:"Beskeden kunne desværre ikke afleveres",duration:5000})}};op.part.Image=function(b){var a=this.element=hui.get(b.element);var e=a.src;var d=a.parentNode;d.style.position="relative";d.style.display="block";var c=hui.build("img",{src:a.src+"&contrast=-20&brightness=80&blur=30",style:"position: absolute; left: 0; top: 0;",parent:d});hui.animate({node:c,duration:1000,delay:1000,ease:hui.ease.flicker,css:{opacity:0}});hui.listen(c,"mouseover",function(){hui.animate({node:c,duration:500,delay:0,ease:hui.ease.fastSlow,css:{opacity:1}})});hui.listen(c,"mouseout",function(){hui.animate({node:c,duration:1000,delay:1000,ease:hui.ease.flicker,css:{opacity:0}})})};op.part.Poster=function(a){this.options=hui.override({duration:1500,interval:5000,delay:0},a);this.name=a.name;this.element=hui.get(a.element);this.container=hui.get.firstByClass(this.element,"part_poster_pages");this.pages=hui.get.byClass(this.element,"part_poster_page");this.index=0;this.indicators=[];this._buildNavigator();if(!this.options.editmode){window.setTimeout(this._callNext.bind(this),this.options.delay)}hui.listen(this.element,"click",this._onClick.bind(this));hui.ui.extend(this)};op.part.Poster.prototype={_buildNavigator:function(){this.navigator=hui.build("div",{"class":"part_poster_navigator",parent:this.element});for(var a=0;a<this.pages.length;a++){this.indicators.push(hui.build("a",{parent:this.navigator,data:a,href:"javascript://","class":a==0?"part_poster_current":""}))}},next:function(){var a=this.index+1;if(a>=this.pages.length){a=0}this.goToPage(a)},previous:function(){var a=this.index-1;if(a<0){a=this.pages.length-1}this.goToPage(a)},setPage:function(a){if(a===null||a===undefined||a==this.index||this.pages.length-1<a){return}this.pages[this.index].style.display="none";this.pages[a].style.display="block";this.index=a;for(var b=0;b<this.indicators.length;b++){hui.cls.set(this.indicators[b],"part_poster_current",b==a)}},goToPage:function(a){if(a==this.index){return}window.clearTimeout(this.timer);var b={container:this.container,duration:this.options.duration};b.hide={element:this.pages[this.index],effect:"slideLeft"};hui.cls.remove(this.indicators[this.index],"part_poster_current");this.index=a;b.show={element:this.pages[this.index],effect:"slideRight"};hui.cls.add(this.indicators[this.index],"part_poster_current");hui.transition(b);if(!this.options.editmode){this._callNext()}this.fire("pageChanged",a)},_callNext:function(){this.timer=window.setTimeout(this.next.bind(this),this.options.interval)},_onClick:function(c){c=hui.event(c);var b=c.findByTag("a");if(b&&hui.cls.has(b.parentNode,"part_poster_navigator")){this.goToPage(parseInt(b.getAttribute("data")))}}};op.part.Map=function(a){this.options=hui.override({maptype:"roadmap",zoom:8},a);this.container=hui.get(a.element);hui.ui.onReady(this.initialize.bind(this))};op.part.Map.defered=[];op.part.Map.onReady=function(a){hui.log("onReady... loaded:"+this.loaded);if(this.loaded){a()}else{this.defered.push(a)}if(this.loaded===undefined){this.loaded=false;window.opMapReady=function(){hui.log("ready");for(var b=0;b<this.defered.length;b++){this.defered[b]()}window.opMapReady=null;this.loaded=true}.bind(this);hui.require("https://maps.googleapis.com/maps/api/js?sensor=false&callback=opMapReady")}};op.part.Map.types={roadmap:"ROADMAP",terrain:"TERRAIN"};op.part.Map.prototype={initialize:function(){hui.log("init");op.part.Map.onReady(this.ready.bind(this))},ready:function(){var b={zoom:this.options.zoom,center:new google.maps.LatLng(-34.397,150.644),mapTypeId:google.maps.MapTypeId[this.options.type.toUpperCase()],scrollwheel:false};var e=this.options.markers;if(this.options.center){b.center=new google.maps.LatLng(this.options.center.latitude,this.options.center.longitude)}this.map=new google.maps.Map(this.container,b);if(this.options.center){var a=new google.maps.Marker({position:new google.maps.LatLng(this.options.center.latitude,this.options.center.longitude),map:this.map,icon:new google.maps.MarkerImage(op.context+"style/basic/gfx/part_map_pin.png",new google.maps.Size(29,30),new google.maps.Point(0,0),new google.maps.Point(8,26))});var d=hui.get.firstByClass(this.element,"part_map_text");if(d){var c=new google.maps.InfoWindow({content:hui.build("div",{text:d.innerHTML,"class":"part_map_bubble"})});c.open(this.map,a)}return;var a=new google.maps.Marker({position:new google.maps.LatLng(this.options.center.latitude,this.options.center.longitude),map:this.map})}}};op.part.Movie=function(a){this.options=a;this.element=hui.get(a.element);this._attach()};op.part.Movie.prototype={_attach:function(){var a=hui.get.firstByClass(this.element,"part_movie_body");var b=hui.get.firstByClass(this.element,"part_movie_code");if(b){a.innerHTML=hui.dom.getText(b)}}};hui.transition=function(c){var e=c.hide,b=c.show;var a=hui.transition[b.effect],d=hui.transition[e.effect];hui.style.set(c.container,{height:c.container.clientHeight+"px",position:"relative"});hui.style.set(e.element,{width:c.container.clientWidth+"px",position:"absolute",boxSizing:"border-box"});hui.style.set(b.element,{width:c.container.clientWidth+"px",position:"absolute",display:"block",visibility:"hidden",boxSizing:"border-box"});hui.animate({node:c.container,css:{height:b.element.clientHeight+"px"},duration:c.duration+10,ease:hui.ease.slowFastSlow,onComplete:function(){hui.style.set(c.container,{height:"",position:""})}});d.beforeHide(e.element);d.hide(e.element,c.duration,function(){hui.style.set(e.element,{display:"none",position:"static",width:""})});a.beforeShow(b.element);hui.style.set(b.element,{display:"block",visibility:"visible"});a.show(b.element,c.duration,function(){hui.style.set(b.element,{position:"static",width:""})})};hui.transition.css=function(a){this.options=a};hui.transition.css.prototype={beforeShow:function(a){hui.style.set(a,this.options.hidden)},show:function(a,c,b){hui.animate({node:a,css:this.options.visible,duration:c,ease:hui.ease.slowFastSlow,onComplete:b})},beforeHide:function(a){hui.style.set(a,this.options.visible)},hide:function(a,c,b){hui.animate({node:a,css:this.options.hidden,duration:c,ease:hui.ease.slowFastSlow,onComplete:function(){b();hui.style.set(a,this.options.visible)}.bind(this)})}};hui.transition.dissolve=new hui.transition.css({visible:{opacity:1},hidden:{opacity:0}});hui.transition.scale=new hui.transition.css({visible:{opacity:1,transform:"scale(1)"},hidden:{opacity:0,transform:"scale(.9)"}});hui.transition.slideLeft=new hui.transition.css({visible:{opacity:1,marginLeft:"0%"},hidden:{opacity:0,marginLeft:"-100%"}});hui.transition.slideRight=new hui.transition.css({visible:{opacity:1,marginLeft:"0%"},hidden:{opacity:0,marginLeft:"100%"}});op.SearchField=function(a){a=this.options=hui.override({placeholderClass:"placeholder",placeholder:""},a);this.field=hui.get(a.element);this.field.onfocus=function(){if(this.field.value==a.placeholder){this.field.value="";hui.cls.add(this.field,a.placeholderClass)}else{this.field.select()}}.bind(this);this.field.onblur=function(){if(this.field.value==""){hui.cls.add(this.field,a.placeholderClass);this.field.value=a.placeholder}}.bind(this);this.field.onblur()};op.Dissolver=function(a){a=this.options=hui.override({wait:4000,transition:2000,delay:0},a);this.pos=Math.floor(Math.random()*(a.elements.length-0.00001));this.z=1;a.elements[this.pos].style.display="block";window.setTimeout(this.next.bind(this),a.wait+a.delay)};op.Dissolver.prototype={next:function(){this.pos++;this.z++;var b=this.options.elements;if(this.pos==b.length){this.pos=0}var a=b[this.pos];hui.style.setOpacity(a,0);hui.style.set(a,{display:"block",zIndex:this.z});hui.animate(a,"opacity",1,this.options.transition,{ease:hui.ease.slowFastSlow,onComplete:function(){window.setTimeout(this.next.bind(this),this.options.wait)}.bind(this)})}};var ctrl={attach:function(){if(!hui.cls.has(document.body,"front")){return}var k=hui.get.firstByTag(e,"nav");var s={"/":"top","/cv/":"about","":"theater","/fotografier/":"photos","/kommunikation/":"communication","/film/":"movies","/en/":"top","/en/cv/":"about","":"theater","/en/photos/":"photos","/en/communication-training/":"communication","/en/movie-clips/":"movies"};hui.listen(k,"click",function(B){B=hui.event(B);B.stop();var x=B.findByTag("a");if(x){var A=s[x.getAttribute("data-path")];if(!A){return}var y=hui.get.byTag(document.body,"a");for(var z=0;z<y.length;z++){if(A==y[z].getAttribute("name")){hui.window.scrollTo({element:y[z].parentNode,duration:1000,top:A=="theater"?40:140});return}}}});hui.listen("handmade","click",function(x){hui.stop(x);var i=hui.get("humanise");i.style.display="block";window.setTimeout(function(){hui.cls.add(i,"visible")})});hui.listen("video_poster","click",function(){hui.get("video_poster").innerHTML='<iframe width="640" height="480" src="http://www.youtube.com/embed/9q-HBMSSbp4?autoplay=1" frameborder="0" allowfullscreen="allowfullscreen"><xsl:comment/></iframe>'});if(hui.browser.touch){return}hui.cls.add(document.body.parentNode,"desktop");var e=hui.get("head"),w=hui.get("title"),j=hui.get("job"),v=hui.get("broen"),c=hui.get("about"),r=hui.get("pressphotos"),b=hui.get("theater"),g=hui.get("background1"),h=hui.get.firstByTag(g,"div"),f=hui.get("background2"),m=hui.get.firstByTag(f,"div"),d=hui.get("background3"),p=hui.get.firstByTag(d,"div"),t=hui.get.firstByClass(b,"photo"),n=hui.get.firstByClass(b,"theaters"),o=hui.get("reelContent");var l=hui.window.getViewWidth();var a=0;var q=hui.get.byTag(k,"li");for(var u=q.length-1;u>=0;u--){a+=q[u].clientWidth+10}if(!hui.browser.animation){hui.style.setOpacity(t,0);hui.style.setOpacity(n,0)}hui.parallax.listen({min:0,max:246,$scroll:function(i){e.style.height=((1-i)*146+100)+"px";w.style.fontSize=((1-i)*30+50)+"px";j.style.left=(hui.ease.fastSlow(i)*260+10)+"px";j.style.top=((i)*-133+170)+"px";hui.style.setOpacity(v,1-hui.ease.quadOut(i))}});hui.parallax.listen({element:c,$scroll:function(i){hui.cls.set(c,"visible",i<0.8)}});hui.parallax.listen({element:o,$scroll:function(i){o.style.marginLeft=(i*-400-100)+"px"}});hui.parallax.listen({element:b,$scroll:function(y){var x=y>0&&y<1;if(this.darkened!=x){hui.cls.set(document.body,"full",x);this.darkened=x}var i=y>0.3&&y<0.7;if(this.shown!=i){if(i){hui.animate({node:t,css:{opacity:i?1:0},ease:hui.ease.flicker,duration:3000,$complete:function(){if(hui.browser.animation){hui.cls.set(b,"final",y>0&&y<1)}else{hui.animate({node:n,css:{opacity:i?1:0},ease:hui.ease.slowFast,duration:5000})}}})}this.shown=i}}});hui.parallax.listen({$resize:function(x,i){b.style.height=Math.round(i*1)+"px";if(!hui.browser.mediaQueries){hui.cls.set(document.body,"small",x<1200)}l=x}});hui.parallax.start()}};hui.onReady(ctrl.attach.bind(ctrl));hui.between=function(c,d,b){var a=Math.min(b,Math.max(c,d));return isNaN(a)?c:a};