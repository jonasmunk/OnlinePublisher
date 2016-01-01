

var ctrl = {
		
	attach : function() {
		
		
		var nav = hui.find('nav');
	
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
			var a = e.findByTag('a');
			if (a) {
				var hash = paths[a.getAttribute('data-path')];
				if (!hash) {
					return;
				}
				var links = hui.get.byTag(document.body,'a');
				for (var i = 0; i < links.length; i++) {
					if (hash == links[i].getAttribute('name')) {
            e.stop();
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
		
		var theater = hui.find('.theater'),
			theater_photo = hui.find('.theater_photo',theater),
      theaters = hui.find('.theater_stages',theater);
		
		
		
		var currentWidth = hui.window.getViewWidth();
		
		if (!hui.browser.animation) {
			hui.style.setOpacity(theater_photo,0);
			hui.style.setOpacity(theaters,0);
		}
    if (theater) {
      var darkened = false;
  		hui.parallax.listen({
  			element : theater,
  			$scroll : function(pos) {
  				var dark = pos>0.2 && pos<.8 && currentWidth > 700;
  				if (darkened!=dark) {
  					hui.cls.set(document.body,'is-full',dark);
  					if (hui.browser.animation) {
  						hui.cls.set(document.body,'is-dark',dark);
  					} else {
  						hui.animate({node:document.body,css:{'background-color':dark ? '#000' : '#fff'},duration:1000});
  					}
  					darkened = dark;
  				}
  				var show = pos>.3 && pos<.7;
  				if (this.shown!=show) {
  					if (show) {
  						hui.animate({node:theater_photo,css:{opacity:show ? 1 : 0},ease:hui.ease.flicker,duration:3000,$complete : function() {
  							if (hui.browser.animation) {
  								hui.cls.set(theater,'is-final',pos>0 && pos<1);
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
  				theater.style.height = width > 700 ? Math.round(height*.8)+'px' : '';
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