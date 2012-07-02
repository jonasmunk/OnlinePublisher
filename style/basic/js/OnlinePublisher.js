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
		hui.ui.showMessage({text:'Indlæser...',busy:true,delay:300});
		hui.ui.require(['Formula','Button','TextField'],
			function() {
				hui.ui.hideMessage();
				var box = this.loginBox = hui.ui.Box.create({width:300,title:'Adgangskontrol',modal:true,absolute:true,closable:true,curtainCloses:true,padding:10});
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
					},
					$close : function() {
						// Be sure to blur the login form
						document.body.focus();
					}
				});
				var g = form.buildGroup(null,[
					{type:'TextField',options:{label:'Brugernavn',key:'username'}},
					{type:'TextField',options:{label:'Kodeord',key:'password',secret:true}}
				]);
				var b = g.createButtons();
				
				var forgot = hui.ui.Button.create({text:'Glemt kode...'})
				forgot.listen({$click:function() {
					document.location = op.context+'Editor/Authentication.php?forgot=true';
				}});
				b.add(forgot);
				
				var cancel = hui.ui.Button.create({text:'Annuller'})
				cancel.listen({$click:function() {
					form.reset();
					box.hide();
				}});
				b.add(cancel);
				
				b.add(hui.ui.Button.create({text:'Log ind',highlighted:true,submit:true}));
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
		hui.ui.showMessage({text:'Udfyld venligst begge felter',duration:3000});
		this.loginForm.focus();
		return;
	}

	hui.ui.request({		
		message : {start:'Logger ind...',delay:300},
		url : op.context+'Editor/Services/Core/Authentication.php',
		parameters : {username:username,password:password},
		onJSON : function(response) {
			if (response.success) {
				hui.ui.showMessage({text:'Du er nu logget ind!',icon:'common/success',duration:4000});
				op.igniteEditor();
			} else {
				hui.ui.showMessage({text:'Brugeren blev ikke fundet',icon:'common/warning',duration:4000});
			}
		},
		onFailure : function() {
			hui.ui.showMessage({text:'Der skete en fejl internt i systemet!',icon:'common/warning',duration:4000});
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
		if (!this.viewer) {
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
		var nodes = element.getElementsByTagName('a');
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
			hui.style.setOpacity(nodes[index],0)
			nodes[index].style.zIndex=zIndex;
			nodes[index].style.display='block';
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
	hui.listen(this.element,'submit',this._send.bind(this));
}

op.part.Formula.prototype = {
	_send : function(e) {
		hui.stop(e);
		var name = this.element.name.value;
		var email = this.element.email.value;
		var message = this.element.message.value;
		if (hui.isBlank(name) || hui.isBlank(email) || hui.isBlank(message)) {
			hui.ui.showMessage({text:'Alle felter skal udfyldes',duration:2000});
			this.element.name.focus();
			return;
		}
		hui.ui.showMessage({text:'Sender besked...'});
		var url = op.page.path+'services/parts/formula/';
		var parms = {name:name,email:email,message:message,id:this.id};
		hui.ui.request({url:url,parameters:parms,onSuccess:this._onSuccess.bind(this),onFailure:this._onFailure.bind(this)});
	},
	_onSuccess : function() {
		hui.ui.showMessage({text:'Beskeden er nu sendt',duration:2000});
		this.element.reset();
	},
	_onFailure : function() {
		hui.ui.showMessage({text:'Beskeden kunne desværre ikke afleveres',duration:5000});
	}
}

///////////////// Poster //////////////////

op.part.Poster = function(options) {
	this.options = hui.override({duration:1500,delay:5000},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.container = hui.get.firstByClass(this.element,'part_poster_pages');
	this.pages = hui.get.byClass(this.element,'part_poster_page');
	this.index = 0;
	this.indicators = [];
	this._buildNavigator();
	if (!this.options.editmode) {
		this._callNext();
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
	setPage : function(index) {
		if (index==this.index) return;
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
		this.timer = window.setTimeout(this.next.bind(this),this.options.delay);
	},
	_onClick : function(e) {
		e = hui.event(e);
		var a = e.findByTag('a');
		if (a) {
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