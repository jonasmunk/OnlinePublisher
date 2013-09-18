var ctrl = {
	
	nodes : {},
	
	attach : function() {
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
			theater_photo = hui.get.firstByClass(theater,'photo');
		
		hui.parallax.listen({
			min : 0,
			max : 246,
			$scroll : function(pos) {
				head.style.height = ((1-pos)*146+100)+'px';
				title.style.fontSize = ((1-pos)*30+50)+'px';
				job.style.left = (hui.ease.fastSlow(pos)*260+10)+'px';
				job.style.top = ((pos)*-133+170)+'px';
				broen.style.opacity = 1-hui.ease.quadOut(pos);
			}
		})
		hui.parallax.listen({
			min : 0,
			max : 700,
			$scroll : function(pos) {
				hui.cls.set(document.body,'full',pos==1);
			}
		});
		hui.parallax.listen({
			element : about,
			$scroll : function(pos) {
				hui.cls.set(about,'visible',pos<.8)
			}
		})
		hui.parallax.listen({
			element : press,
			$scroll : function(pos) {
				hui.cls.set(press,'visible',pos>.2 && pos<.8);
				hui.cls.set(press,'saturated',pos>.1 && pos<.9)
			}
		})
		hui.parallax.listen({
			element : background1,
			$scroll : function(pos) {
				background1_body.style.marginTop = (pos*200-250)+'px';
			}
		})
		hui.parallax.listen({
			element : background2,
			$scroll : function(pos) {
				background2_body.style.marginTop = (pos*200-250)+'px';
			}
		})
		hui.parallax.listen({
			element : background3,
			$scroll : function(pos) {
				background3_body.style.marginTop = (pos*200-250)+'px';
			}
		})
		hui.parallax.listen({
			element : theater,
			$scroll : function(pos) {
				hui.cls.set(document.body,'dark',pos>0 && pos<1);
				var show = pos>.3 && pos<.7;
				if (this.shown!=show) {
					//hui.cls.set(document.body,'full',show);
					if (show) {
						hui.animate({node:theater_photo,css:{opacity:show ? 1 : 0},ease:hui.ease.flicker,duration:3000,$complete : function() {
							hui.cls.set(theater,'final',pos>0 && pos<1);
						}});
					}
					this.shown = show;
				}
			},
			$resize : function(width,height) {
				theater.style.height = Math.round(height*.8)+'px';
			}
		})
		hui.parallax.start();
	},
	_scroll : function() {
		var pos = document.body.scrollTop,
			n = this.nodes;
		n.head.style.height = Math.min(246,Math.max(246-pos,100))+'px';
		n.title.style.top = Math.min(30,Math.max(30-pos+20,10))+'px';
		n.title.style.fontSize = Math.min(80,Math.max(80-pos+100,50))+'px';
	}
}

hui.onReady(ctrl.attach.bind(ctrl))

hui.between = function(min,value,max) {
	var result = Math.min(max,Math.max(min,value));
	return isNaN(result) ? min : result;
}