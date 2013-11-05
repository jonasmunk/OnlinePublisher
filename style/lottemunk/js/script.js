

var ctrl = {
		
	attach : function() {
		
		hui.cls.add(document.body.parentNode,'desktop');
		
		var head = hui.get('head'),
			title = hui.get('title'),
			job = hui.get('job'),
			broen = hui.get('broen'),
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
			nav = hui.get.firstByTag(head,'nav'),
			reelContent = hui.get('reelContent');
		
			var paths = {
				'/' : 'top',
				'/cv/' : 'about',
				'' : 'theater',
				'/fotografier/' : 'photos',
				'/kommunikation/' : 'communication',
				'/film/' : 'movies'
			}
		
		
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
		hui.parallax.listen({
			min : 0,
			max : 246,
			$scroll : function(pos) {
				head.style.height = ((1-pos)*146+100)+'px';
				title.style.fontSize = ((1-pos)*30+50)+'px';
				job.style.left = (hui.ease.fastSlow(pos)*260+10)+'px';
				job.style.top = ((pos)*-133+170)+'px';
				hui.style.setOpacity(broen,1-hui.ease.quadOut(pos));
			}
		})
		/*
		hui.parallax.listen({
			min : 300,
			max : 500,
			$scroll : function(pos) {
				nav.style.width = Math.max(menuWidth,(1-hui.ease.slowFastSlow(pos))*currentWidth)+'px';
				nav.style.bottom = (hui.ease.slowFastSlow(pos)*40-40)+'px';
			}
		});
		hui.parallax.listen({
			min : 0,
			max : 700,
			$scroll : function(pos) {
				if (hui.browser.animation) {
					hui.cls.set(document.body,'full',pos==1);
				} else {
					hui.animate({
						node : head,
						css : {'margin-top':pos==1 ? '-100px' : '0px'},
						duration : 2000,
						ease : pos==1 ? hui.ease.fastSlow : hui.ease.bounce
					});
				}
			}
		});*/
		hui.parallax.listen({
			element : about,
			$scroll : function(pos) {
				hui.cls.set(about,'visible',pos<.8)
			}
		})
		hui.parallax.listen({
			element : reelContent,
			$scroll : function(pos) {
				reelContent.style.marginLeft = (pos*-400-100)+'px';
			}
		})
		/*
		if (hui.browser.animation) {
			hui.parallax.listen({
				element : press,
				$scroll : function(pos) {
					hui.cls.set(press,'invisible',!(pos>.2 && pos<.8));
					hui.cls.set(press,'saturated',pos>.1 && pos<.9)
				}
			})
		}*/
		/*
		hui.parallax.listen({
			element : background1,
			$scroll : function(pos) {
				background1_body.style.marginTop = (pos*200-250)+'px';
			}
		})*/
		hui.parallax.listen({
			element : theater,
			darkened : false,
			$scroll : function(pos) {
				
				var dark = pos>0 && pos<1;
				if (this.darkened!=dark) {
					hui.cls.set(document.body,'full',dark);
					if (hui.browser.animation) {
						hui.cls.set(document.body,'dark',dark);
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
		hui.parallax.start();
		
		hui.listen('handmade','click',function(e) {
			hui.stop(e);
			var hum = hui.get('humanise');
			hum.style.display='block'
			window.setTimeout(function() {
				hui.cls.add(hum,'visible');
			})
		})
	}
}

if (!hui.browser.touch) {
	hui.onReady(ctrl.attach.bind(ctrl))
}

hui.between = function(min,value,max) {
	var result = Math.min(max,Math.max(min,value));
	return isNaN(result) ? min : result;
}