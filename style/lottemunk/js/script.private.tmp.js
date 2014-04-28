/* ["style\/lottemunk\/js\/lottemunk.js"] */


/* style/basic/js/OnlinePublisher.js */
if (!op) {
	var op = {};
}

op.preview = false;
op.page = {id:null,path:null,template:null};


op.ignite = function() {
	if (!this.preview) {
		document.onkeydown=function(e) {
			e = hui.event(e);
			if(e.returnKey && e.shiftKey) {
				e.stop();
				var temp;
				temp = function(e) {
					e = hui.event(e);
					if (e.returnKey) {
						hui.unListen(document,'keyup',temp);
						if (!hui.browser.msie && !op.user.internal) {
							e.stop();
							op.showLogin();
						} else {
							window.location=(op.page.path+"Editor/index.php?page="+op.page.id);
						}
					}
				}
				hui.listen(document,'keyup',temp);
			}
			return true;
		}
		hui.request({
			url : op.context+'services/statistics/',
			parameters : {page : op.page.id, referrer : document.referrer, uri : document.location.href}
		});
	}
	if (hui.browser.msie7) {
		hui.onReady(function() {
			hui.cls.add(document.body.parentNode,'msie7');
		});
	}
}

op.showLogin = function() {
	if (!this.loginBox) {
		if (this.loadingLogin) {
			hui.log('Aborting, the box is loading')
			return;
		}
		this.loadingLogin = true;
		hui.ui.showMessage({text:{en:'Loading...',da:'Indlæser...'},busy:true,delay:300});
		hui.ui.require(['Formula','Button','TextField'],
			function() {
				hui.ui.hideMessage();
				var box = this.loginBox = hui.ui.Box.create({width:300,title:{en:'Access control',da:'Adgangskontrol'},modal:true,absolute:true,closable:true,curtainCloses:true,padding:10});
				this.loginBox.addToDocument();
				var form = this.loginForm = hui.ui.Formula.create();
				form.listen({
					$submit : function() {
						if (!box.isVisible()) {
							// Be sure to not submit if no box
							return;
						}
						var values = form.getValues();
						op.login(values.username,values.password);
					}
				});
				var g = form.buildGroup(null,[
					{type:'TextField',options:{label:{en:'Username',da:'Brugernavn'},key:'username'}},
					{type:'TextField',options:{label:{en:'Password',da:'Kodeord'},key:'password',secret:true}}
				]);
				var b = g.createButtons();
				
				var forgot = hui.ui.Button.create({text:{en:'Forgot password?',da:'Glemt kode?'}})
				forgot.listen({$click:function() {
					document.location = op.context+'Editor/Authentication.php?forgot=true';
				}});
				b.add(forgot);
				
				var cancel = hui.ui.Button.create({text:{en:'Cancel',da:'Annuller'}})
				cancel.listen({$click:function() {
					form.reset();
					box.hide();
					document.body.focus();
				}});
				b.add(cancel);
				
				b.add(hui.ui.Button.create({text:{en:'Log in',da:'Log ind'},highlighted:true,submit:true}));
				this.loginBox.add(form);
				this.loginBox.show();
				window.setTimeout(function() {
					form.focus();
				},100);
				this.loadingLogin = false;
				op.startListening();
				var p = new hui.Preloader({context:hui.ui.context+'hui/icons/'});
				p.addImages('common/success24.png');
				p.load();
			}.bind(this)
		);
	} else {
		this.loginBox.show();
		this.loginForm.focus();
	}
}

op.startListening = function() {
	hui.listen(window,'keyup',function(e) {
		e = hui.event(e);
		if (e.escapeKey && this.loginBox.isVisible()) {
			this.loginBox.hide();
			var a = hui.get.firstByTag(document.body,'a');
			if (a) {
				a.focus();
				a.blur();
			}
			document.body.blur();
		}
	}.bind(this));
}

op.login = function(username,password) {
	if (hui.isBlank(username) || hui.isBlank(password)) {
		hui.ui.showMessage({text:{en:'Please fill in both fields',da:'Udfyld venligst begge felter'},duration:3000});
		this.loginForm.focus();
		return;
	}

	hui.ui.request({		
		message : {start:{en:'Logging in...',da:'Logger ind...'},delay:300},
		url : op.context+'Editor/Services/Core/Authentication.php',
		parameters : {username:username,password:password},
		onJSON : function(response) {
			if (response.success) {
				hui.ui.showMessage({text:{en:'You are now logged in',da:'Du er nu logget ind'},icon:'common/success',duration:4000});
				op.igniteEditor();
			} else {
				hui.ui.showMessage({text:{en:'The user was not found',da:'Brugeren blev ikke fundet'},icon:'common/warning',duration:4000});
			}
		},
		onFailure : function() {
			hui.ui.showMessage({text:{en:'An internal error occurred',da:'Der skete en fejl internt i systemet'},icon:'common/warning',duration:4000});
		}
	});
}

op.igniteEditor = function() {
	window.location=(op.page.path+"Editor/index.php?page="+op.page.id);
}

op.showImage = function(image) {
	if (!this.imageViewer) {
		this.imageViewer = hui.ui.ImageViewer.create({maxWidth:2000,maxHeight:2000,perimeter:40,sizeSnap:10});
		this.imageViewer.listen(op.imageViewerDelegate);
	}
	this.imageViewer.clearImages();
	this.imageViewer.addImage(image);
	this.imageViewer.show();
}

op.registerImageViewer = function(id,image) {
	hui.get(id).onclick = function() {
		op.showImage(image);
		return false;
	}
}

op.imageViewerDelegate = {
	$resolveImageUrl : function(img,width,height) {
		var w = img.width ? Math.min(width,img.width) : width;
		var h = img.height ? Math.min(height,img.height) : height;
		return op.page.path+'services/images/?id='+img.id+'&width='+w+'&height='+h+'&format=jpg&quality=100';
	}
}

op.showVideo = function(video) {
	if (!this.videoViewer) {
		this.videoViewer = new op.VideoViewer();
	}
	this.videoViewer.show(video);
}

op.VideoViewer = function() {
	this.box = hui.ui.Box.create(null,{absolute:true,modal:true})
	this.box.addToDocument();
	this.content = new Element('div').setStyle({width:'400px',height:'200px'});
	this.box.add(this.content);
	this.box.listen(this);
}

op.VideoViewer.prototype = {
	show : function(video) {
		//this.content.update('<video onclick="this.play()" src="'+video.file+'" style="width: '+video.width+'px;height: '+video.height+'px;"></video>');
		this.content.setStyle({width:video.width+'px',height:video.height+'px'});
		this.content.insert(this.buildQuickTime(video));
		this.box.show();
	},
	buildFlash : function() {
		var s1 = new SWFObject('player.swf',"ply","328","200","9","#FFFFFF");
		s1.addParam("allowfullscreen","true");
		s1.addParam("allowscriptaccess","always");
		s1.addParam("flashvars","file=video.flv&image=preview.jpg");
		s1.write("container");
	},
	buildQuickTime : function(video) {
		var obj =  new Element('object',{width:video.width,height:video.height,type:'video/quicktime',data:video.file});
		return obj;
/*		'<object width="320" height="180" type="video/quicktime" data="http://movies.apple.com/movies/us/apple/mac/macbook/2008/designvideo/apple_new_macbook_video_20081014_320x180.mov?width=320&amp;height=180" id="movieIdInner">
		<param value="false" name="controller"/>
		<param value="undefined" name="posterframe"/>
		<param value="false" name="showlogo"/>
		<param value="true" name="autostart"/>
		<param value="true" name="cache"/>
		<param value="white" name="bgcolor"/>
		<param value="false" name="aggressivecleanup"/>
		<param value="true" name="saveembedtags"/>
		</object>';*/
	},
	boxCurtainWasClicked : function() {
		this.box.hide();
		this.content.update();
	}
}

if (op.part===undefined) {
	op.part = {};
}


/************* Feedback *************/

op.feedback = function(a) {
	hui.require(op.page.path+'style/basic/js/Feedback.js',function() {
		op.feedback.Controller.init(a);
	})
}


/************* Image gallery *************/

op.part.ImageGallery = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.images = [];
}

op.part.ImageGallery.prototype = {
	registerImage : function(node,image) {
		node = hui.get(node);
		if (this.options.editor) {
			return;
		}
		this.images.push(image);
		var self = this;
		node.onclick = function(e) {
			hui.stop(e);
			self.showImage(image.id);
			return false;
		}
	},
	showImage : function(id) {
		if (!this.imageViewer) {
			this.imageViewer = hui.ui.ImageViewer.create({maxWidth:2000,maxHeight:2000,perimeter:40,sizeSnap:10});
			this.imageViewer.listen(op.imageViewerDelegate);
		}
		this.imageViewer.clearImages();
		this.imageViewer.addImages(this.images);
		this.imageViewer.showById(id);
	},
	init : function() {
		if (this.options.variant=='changing') {
			op.part.ImageGallery.changing.init(this.element);
			//new op.Dissolver({elements:this.element.select('a')});
		}
	}
}

op.part.ImageGallery.changing = {
	init : function(element) {
		var nodes = hui.get.byClass(element,'part_imagegallery_item');
		if (nodes.length==0) {
			return;
		}
        element.style.height = element.clientHeight+'px';
        for (var i = 0; i < nodes.length; i++) {
            nodes[i].style.position = 'absolute';
        }
		var timer;
		var index = -1;
		var zIndex = 1;
		var first = true;
		timer = function() {
			if (index>-1) {
				hui.animate(nodes[index],'opacity',0,200,{hideOnComplete:true,delay:800});
			}
			index++;
			if (index>nodes.length-1) {
				index = 0;
			}
			if (!first) {
                hui.style.set(nodes[index],{
                    opacity: 0,
                    zIndex : zIndex,
                    display : 'block'
                })
				hui.animate(nodes[index],'opacity',1,1000,{ease:hui.ease.slowFastSlow});
			}
			window.setTimeout(timer,3000);
			zIndex++;
			first = false;
		}
		timer();
	}
}

///////////////// Formula //////////////////

op.part.Formula = function(options) {
	this.element = hui.get(options.element);
	this.id = options.id;
	this.inputs = options.inputs;
	hui.listen(this.element,'submit',this._send.bind(this));
}

op.part.Formula.prototype = {
	_send : function(e) {
		hui.stop(e);
		
		var fields = [];
		
		for (var i=0; i < this.inputs.length; i++) {
			var info = this.inputs[i];
			var input = hui.get(info.id);
			var validation = info.validation;
			if (validation.required) {
				if (hui.isBlank(input.value)) {
					hui.ui.showMessage({text : validation.message,duration:2000});
					input.focus();
					return;
				}
			}
			if (validation.syntax=='email' && !hui.isBlank(input.value)) {
				var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\\n".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA\n-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				if (!re.test(input.value)) {
					hui.ui.showMessage({text : validation.message ,duration:2000});
					input.focus();
					return;
				}
			}
			fields.push({
				label : info.label,
				value : input.value
			})
		};
		
		var url = op.page.path+'services/parts/formula/';
		var data = {
			id : this.id,
			fields : fields
		}
		hui.ui.showMessage({text:'Sender besked...',busy:true});
		hui.ui.request({
			url : url,
			json : {data:data},
			onSuccess : this._onSuccess.bind(this),
			onFailure : this._onFailure.bind(this)
		});
	},
	_onSuccess : function() {
		hui.ui.showMessage({text:'Beskeden er nu sendt',icon:'common/success',duration:2000});
		this.element.reset();
	},
	_onFailure : function() {
		hui.ui.showMessage({text:'Beskeden kunne desværre ikke afleveres',duration:5000});
	}
}


///////////////// Image //////////////////

op.part.Image = function(options) {
	var img = this.element = hui.get(options.element);
	var src = img.src;
	var parent = img.parentNode;
	parent.style.position = 'relative';
	parent.style.display = 'block';
	var effect = hui.build('img',{src:img.src+'&contrast=-20&brightness=80&blur=30',style:'position: absolute; left: 0; top: 0;',parent:parent});
	hui.animate({node:effect,duration:1000,delay:1000,ease:hui.ease.flicker,css:{opacity:0}})
	hui.listen(effect,'mouseover',function() {
		hui.animate({node:effect,duration:500,delay:0,ease:hui.ease.fastSlow,css:{opacity:1}})
	})
	hui.listen(effect,'mouseout',function() {
		hui.animate({node:effect,duration:1000,delay:1000,ease:hui.ease.flicker,css:{opacity:0}})
	})
}


///////////////// Poster //////////////////

op.part.Poster = function(options) {
	this.options = hui.override({duration:1500,interval:5000,delay:0},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.container = hui.get.firstByClass(this.element,'part_poster_pages');
	this.pages = hui.get.byClass(this.element,'part_poster_page');
	this.index = 0;
	this.indicators = [];
	this._buildNavigator();
	if (!this.options.editmode) {
		window.setTimeout(this._callNext.bind(this),this.options.delay);
	}
	hui.listen(this.element,'click',this._onClick.bind(this));
	hui.ui.extend(this);
}

op.part.Poster.prototype = {
	_buildNavigator : function() {
		this.navigator = hui.build('div',{'class':'part_poster_navigator',parent:this.element});
		for (var i=0; i < this.pages.length; i++) {
			this.indicators.push(hui.build('a',{parent:this.navigator,data:i,href:'javascript://','class' : i==0 ? 'part_poster_current' : ''}));
		};
	},
	next : function() {
		var index = this.index+1;
		if (index>=this.pages.length) {
			index = 0;
		}
		this.goToPage(index);
	},
	previous : function() {
		var index = this.index - 1;
		if (index<0) {
			index = this.pages.length - 1;
		}
		this.goToPage(index);
	},
	setPage : function(index) {
		if (index===null || index===undefined || index == this.index || this.pages.length-1 < index) {
			return;
		}
		this.pages[this.index].style.display = 'none';
		this.pages[index].style.display = 'block';
		this.index = index;
		for (var i=0; i < this.indicators.length; i++) {
			hui.cls.set(this.indicators[i],'part_poster_current',i==index);
		};
	},
	goToPage : function(index) {
		if (index==this.index) return;
		window.clearTimeout(this.timer);
		var recipe = {container:this.container,duration:this.options.duration};
		recipe.hide = {element:this.pages[this.index],effect:'slideLeft'};
		hui.cls.remove(this.indicators[this.index],'part_poster_current');
		this.index = index;
		recipe.show = {element : this.pages[this.index],effect:'slideRight'};
		hui.cls.add(this.indicators[this.index],'part_poster_current');
		hui.transition(recipe);
		if (!this.options.editmode) {
			this._callNext();
		}
		this.fire('pageChanged',index);
	},
	_callNext : function() {
		this.timer = window.setTimeout(this.next.bind(this),this.options.interval);
	},
	_onClick : function(e) {
		e = hui.event(e);
		var a = e.findByTag('a');
		if (a && hui.cls.has(a.parentNode,'part_poster_navigator')) {
			this.goToPage(parseInt(a.getAttribute('data')));
		}
	}
}

///////////////// Map //////////////////

op.part.Map = function(options) {
	this.options = hui.override({maptype:'roadmap',zoom:8},options);
	this.container = hui.get(options.element);
	hui.ui.onReady(this.initialize.bind(this));
}

op.part.Map.defered = [];

op.part.Map.onReady = function(callback) {
	hui.log('onReady... loaded:'+this.loaded)
	if (this.loaded) {
		callback();
	} else {
		this.defered.push(callback);
	}
	if (this.loaded===undefined) {
		this.loaded = false;
		window.opMapReady = function() {
			hui.log('ready')
			for (var i=0; i < this.defered.length; i++) {
				this.defered[i]();
			};
			window.opMapReady = null;
			this.loaded = true;
		}.bind(this)
		hui.require('https://maps.googleapis.com/maps/api/js?sensor=false&callback=opMapReady');
	}
}

op.part.Map.types = {roadmap : 'ROADMAP',terrain:'TERRAIN'};

op.part.Map.prototype = {
	initialize : function() {
		hui.log('init')
		op.part.Map.onReady(this.ready.bind(this));
	},
	ready : function() {
		var options = {
			zoom : this.options.zoom,
			center : new google.maps.LatLng(-34.397, 150.644),
			mapTypeId : google.maps.MapTypeId[this.options.type.toUpperCase()],
			scrollwheel : false
		};
		var markers = this.options.markers;
		if (this.options.center) {
			options.center = new google.maps.LatLng(this.options.center.latitude, this.options.center.longitude);
		}
		this.map = new google.maps.Map(this.container,options);
		
		if (this.options.center) {
		    var marker = new google.maps.Marker({
		        position : new google.maps.LatLng(this.options.center.latitude, this.options.center.longitude),
		        map : this.map,
		        icon : new google.maps.MarkerImage(
					op.context+'style/basic/gfx/part_map_pin.png',
      				new google.maps.Size(29, 30), // Size
      				new google.maps.Point(0,0), // Location (sprite)
      				new google.maps.Point(8, 26)) // anchor
		    	});
			var text = hui.get.firstByClass(this.element,'part_map_text');
			if (text) {
				var info = new google.maps.InfoWindow({
					content : hui.build('div',{
						text : text.innerHTML,
						'class' : 'part_map_bubble'
					})
				})				
				info.open(this.map,marker);
			}
		return
		    var marker = new google.maps.Marker({
		        position: new google.maps.LatLng(this.options.center.latitude, this.options.center.longitude),
		        map: this.map
		    });
		}
	}
}



////////////////////// Movie ////////////////////////

op.part.Movie = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
    hui.listen(this.element,'click',this._activate.bind(this));
}

op.part.Movie.prototype = {
    _activate : function() {
        var body = hui.get.firstByClass(this.element,'part_movie_body');
        var code = hui.get.firstByClass(this.element,'part_movie_code');
        if (code) {
            body.innerHTML = hui.dom.getText(code);
        } else {
            var video = hui.get.firstByClass(this.element,'part_movie_video');
            if (video) {
                body.innerHTML = hui.dom.getText(video);
            }
        }
        body.style.background='';
    }
}



// Stuff...

hui.transition = function(options) {
	var hide = options.hide,
		show = options.show;
	var showController = hui.transition[show.effect],
		hideController = hui.transition[hide.effect];
		
	hui.style.set(options.container,{height:options.container.clientHeight+'px',position:'relative'})
	hui.style.set(hide.element,{width:options.container.clientWidth+'px',position:'absolute',boxSizing:'border-box'})
	hui.style.set(show.element,{width:options.container.clientWidth+'px',position:'absolute',display:'block',visibility:'hidden',boxSizing:'border-box'})
	
	hui.animate({
		node : options.container,
		css : {height:show.element.clientHeight+'px'},
		duration : options.duration+10,
		ease : hui.ease.slowFastSlow,
		onComplete : function() {
			hui.style.set(options.container,{height:'',position:''})
		}
	})
	hideController.beforeHide(hide.element);
	hideController.hide(hide.element,options.duration,function() {
		hui.style.set(hide.element,{display:'none',position:'static',width:''})
	})
	
	showController.beforeShow(show.element);
	hui.style.set(show.element,{display:'block',visibility:'visible'})
	showController.show(show.element,options.duration,function() {
		hui.style.set(show.element,{position:'static',width:''})
	});
}

hui.transition.css = function(options) {
	this.options = options;
}

hui.transition.css.prototype = {
	beforeShow : function(element) {
		hui.style.set(element,this.options.hidden)
	},
	show : function(element,duration,onComplete) {
		hui.animate({
			node : element,
			css : this.options.visible,
			duration : duration,
			ease : hui.ease.slowFastSlow,
			onComplete : onComplete
		})
	},
	beforeHide : function(element) {
		hui.style.set(element,this.options.visible);
	},
	hide : function(element,duration,onComplete) {
		hui.animate({
			node : element,
			css : this.options.hidden,
			duration : duration,
			ease : hui.ease.slowFastSlow,
			onComplete : function() {
				onComplete();
				hui.style.set(element,this.options.visible);
			}.bind(this)
		})
	}
}

hui.transition.dissolve = new hui.transition.css({visible:{opacity:1},hidden:{opacity:0}});

hui.transition.scale = new hui.transition.css({visible:{opacity:1,transform:'scale(1)'},hidden:{opacity:0,transform:'scale(.9)'}});

hui.transition.slideLeft = new hui.transition.css({visible:{opacity:1,marginLeft:'0%'},hidden:{opacity:0,marginLeft:'-100%'}});

hui.transition.slideRight = new hui.transition.css({visible:{opacity:1,marginLeft:'0%'},hidden:{opacity:0,marginLeft:'100%'}});

///////////////// Search field //////////////////

op.SearchField = function(o) {
	o = this.options = hui.override({placeholderClass:'placeholder',placeholder:''},o);
	this.field = hui.get(o.element);
	this.field.onfocus = function() {
		if (this.field.value==o.placeholder) {
			this.field.value = '';
			hui.cls.add(this.field,o.placeholderClass);
		} else {
			this.field.select();
		}
	}.bind(this);
	this.field.onblur = function() {
		if (this.field.value=='') {
			hui.cls.add(this.field,o.placeholderClass);
			this.field.value=o.placeholder;
		}
	}.bind(this);
	this.field.onblur();
}

op.Dissolver = function(options) {
	options = this.options = hui.override({wait:4000,transition:2000,delay:0},options);
	this.pos = Math.floor(Math.random()*(options.elements.length-.00001));
	this.z = 1;
	options.elements[this.pos].style.display='block';
	window.setTimeout(this.next.bind(this),options.wait+options.delay);
}

op.Dissolver.prototype = {
	next : function() {
		this.pos++;
		this.z++;
		var elm = this.options.elements;
		if (this.pos==elm.length) {
			this.pos=0;
		}
		var e = elm[this.pos];
		hui.style.setOpacity(e,0);
		hui.style.set(e,{display:'block',zIndex:this.z});
		hui.animate(e,'opacity',1,this.options.transition,{ease:hui.ease.slowFastSlow,onComplete:function() {
			window.setTimeout(this.next.bind(this),this.options.wait);
		}.bind(this)});
	}
}
/* style/lottemunk/js/lottemunk.js */


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
			//darkened : false,
			$scroll : function(pos) {
				var dark = pos>0 && pos<1;
				if (this.darkened!=dark) {
					hui.cls.set(document.body,'full',dark);
					/*
					if (hui.browser.animation) {
						hui.cls.set(document.body,'dark',dark);
					} else {
						hui.animate({node:document.body,css:{'background-color':dark ? '#000' : '#fff'},duration:1000});
					}*/
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
	}
}

hui.onReady(ctrl.attach.bind(ctrl));