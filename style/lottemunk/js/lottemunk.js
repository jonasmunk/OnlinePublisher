

var ctrl = {
		
	attach : function() {
		
		if (!hui.cls.has(document.body,'front')) {
			return;
		}
		
		var nav = hui.get.firstByTag(head,'nav');
	
		var paths = {
			'/' : 'top',
			'/cv/' : 'about',
			'' : 'theater',
			'/fotografier/' : 'photos',
			'/kommunikation/' : 'communication',
			'/film/' : 'movies',
			

			'/en/' : 'top',
			'/en/cv/' : 'about',
			'' : 'theater',
			'/en/photos/' : 'photos',
			'/en/communication-training/' : 'communication',
			'/en/movie-clips/' : 'movies'
		};
		
		hui.listen(nav,'click',function(e) {
			e = hui.event(e);
			e.stop();
			var a = e.findByTag('a');
			if (a) {
				var hash = paths[a.getAttribute('data-path')]
				if (!hash) {
					return;
				}
				var links = hui.get.byTag(document.body,'a');
				for (var i = 0; i < links.length; i++) {
					if (hash == links[i].getAttribute('name')) {
						hui.window.scrollTo({
							element : links[i].parentNode,
							duration : 1000,
							top : hash=='theater' ? 40 : 140
						});
						return;
					}
				}
			}
		});

		hui.listen('handmade','click',function(e) {
			hui.stop(e);
			var hum = hui.get('humanise');
			hum.style.display='block'
			window.setTimeout(function() {
				hui.cls.add(hum,'visible');
			})
		})
		
		
		hui.listen('video_poster','click',function() {
			hui.get('video_poster').innerHTML = '<iframe width="640" height="480" src="http://www.youtube.com/embed/2k5DfFfNcO8?autoplay=1" frameborder="0" allowfullscreen="allowfullscreen"><xsl:comment/></iframe>';
		});

		// The rest is just non-touch...
		
		if (hui.browser.touch) {
			return;
		}
		hui.cls.add(document.body.parentNode,'desktop');
		
		var head = hui.get('head'),
			title = hui.get('title'),
			job = hui.get('job'),
			broen = hui.find('.js_broen'),
			about = hui.get('about'),
			press = hui.get('pressphotos'),
			theater = hui.get('theater'),
			background1 = hui.get('background1'),
			background1_body = hui.get.firstByTag(background1,'div'),
			background2 = hui.get('background2'),
			background2_body = hui.get.firstByTag(background2,'div'),
			background3 = hui.get('background3'),
			background3_body = hui.get.firstByTag(background3,'div'),
			theater_photo = hui.get.firstByClass(theater,'photo'),
			theaters = hui.get.firstByClass(theater,'theaters'),
			reelContent = hui.get('reelContent');
		
		
		
		var currentWidth = hui.window.getViewWidth();
		var menuWidth = 0;
		var items = hui.get.byTag(nav,'li');
		for (var i = items.length - 1; i >= 0; i--) {
			menuWidth+=items[i].clientWidth+10;
		}
		
		if (!hui.browser.animation) {
			hui.style.setOpacity(theater_photo,0);
			hui.style.setOpacity(theaters,0);
		}
    /*
		hui.parallax.listen({
			min : 0,
			max : 246,
			$scroll : function(pos) {
        if (currentWidth < 600) {
          head.style.height = '';
          title.style.fontSize = '';
  				job.style.left = '';
  				job.style.top = '';
        } else {
  				head.style.height = ((1-pos)*146+100)+'px';
  				title.style.fontSize = ((1-pos)*30+50)+'px';
  				job.style.left = (hui.ease.fastSlow(pos)*250+10)+'px';
  				job.style.top = ((pos)*-135+180)+'px';
        }
				hui.style.setOpacity(broen,1-hui.ease.quadOut(pos));
			}
		})*/
    
		hui.parallax.listen({
			element : about,
			$scroll : function(pos) {
				hui.cls.set(about,'visible',pos<.8)
			}
		})
    if (reelContent) {
  		hui.parallax.listen({
  			element : reelContent,
  			$scroll : function(pos) {
  				reelContent.style.marginLeft = (pos*-400-100)+'px';
  			}
  		})
    }
    
    if (theater) {
  		hui.parallax.listen({
  			element : theater,
  			//darkened : false,
  			$scroll : function(pos) {
  				var dark = pos>0 && pos<1;
  				if (this.darkened!=dark) {
  					hui.cls.set(document.body,'is-full',dark);
  					if (hui.browser.animation) {
  						hui.cls.set(document.body,'is-dark',dark);
  					} else {
  						hui.animate({node:document.body,css:{'background-color':dark ? '#000' : '#fff'},duration:1000});
  					}
  					this.darkened = dark;
  				}
  				var show = pos>.3 && pos<.7;
  				if (this.shown!=show) {
  					if (show) {
  						hui.animate({node:theater_photo,css:{opacity:show ? 1 : 0},ease:hui.ease.flicker,duration:3000,$complete : function() {
  							if (hui.browser.animation) {
  								hui.cls.set(theater,'final',pos>0 && pos<1);
  							} else {
  								hui.animate({node:theaters,css:{opacity:show ? 1 : 0},ease:hui.ease.slowFast,duration:5000});
  							}
  						}});
  					}
  					this.shown = show;
  				}
  			}
  		})
  		hui.parallax.listen({
  			$resize : function(width,height) {
  				theater.style.height = Math.round(height*1)+'px';
  				if (!hui.browser.mediaQueries) {
  					hui.cls.set(document.body,'small',width<1200);
  				}
  				currentWidth = width;
  			}
  		})
    }
		hui.parallax.start();
	}
}

hui.onReady(ctrl.attach.bind(ctrl));

function easeInout(num) {
  return (num*2-1) * (num*2-1) * -1 +1;
}

hui.onReady(function() {
  var photos = hui.get.byClass(document.body,'js-photo');
  //photos = [photos[0]];
  hui.each(photos,function(photo) {
    var effect = hui.find('.js-photo-effect',photo);
    var pos = hui.position.get(photo);
    var size = {width:photo.clientWidth,height:photo.clientHeight};
    hui.ui.listen({
      $$afterResize : function() {
        pos = hui.position.get(photo);
        size = {width:photo.clientWidth,height:photo.clientHeight};
      }
    })
    hui.listen(window,'mousemove',function(e) {
      e = hui.event(e);
      var horz = (e.getLeft() - pos.left) / size.width;
      var vert = (e.getTop() - pos.top) / size.height;
      effect.style.marginLeft = (horz * 20) + 'px';
      effect.style.marginTop = (vert * 20) + 'px';
      var op = hui.between(0,easeInout(horz),1) * hui.between(0,easeInout(vert),1);
      effect.style.opacity = op;
    })    
  })
})