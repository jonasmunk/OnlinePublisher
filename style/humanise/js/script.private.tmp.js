/* ["style\/humanise\/js\/poster.js","style\/humanise\/js\/layout.js"] */


/* style/basic/js/OnlinePublisher.js */
if (!op) {
	var op = {};
}

op.preview = false;
op.page = op.page || {id:null,path:null,template:null};


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

	if (hui.browser.msie7 || hui.browser.msie6) {
        // Fix frames
        var frames = hui.get.byClass(document.body,'shared_frame');
        for (var i = 0; i < frames.length; i++) {
            frames[i].style.width = frames[i].clientWidth + 'px';
            frames[i].style.display = 'block';
        }
        // TODO Fix document layout (turn into tables)
        var rows = hui.get.byClass(document.body,'document_row');
		for (var i = rows.length - 1; i >= 0; i--) {
			var table = hui.build('table',{'class':rows[i].className,style:rows[i].style.cssText});
			var tbody = hui.build('tbody',{parent:table});
			var tr = hui.build('tr',{parent:tbody});
	        var columns = hui.get.byClass(rows[i],'document_column');
			for (var j = 0; j < columns.length; j++) {
				var col = columns[j];
				var td = hui.build('td',{'class':col.className,parent:tr,style:col.style.cssText});
				while (col.firstChild) {
				    td.appendChild(col.firstChild); // *Moves* the child
				}
			}
			rows[i].parentNode.insertBefore(table,rows[i]);
			hui.dom.remove(rows[i]);
		}
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
		$object : function(response) {
			if (response.success) {
				hui.ui.showMessage({text:{en:'You are now logged in',da:'Du er nu logget ind'},icon:'common/success',duration:4000});
				op.igniteEditor();
			} else {
				hui.ui.showMessage({text:{en:'The user was not found',da:'Brugeren blev ikke fundet'},icon:'common/warning',duration:4000});
			}
		},
		$failure : function() {
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

if (op.part===undefined) {
	op.part = {};
}


/************* Feedback *************/

op.feedback = function(a) {
	hui.require(op.page.path+'style/basic/js/Feedback.js',function() {
		op.feedback.Controller.init(a);
	})
}

window.define && define('op');

/************* Image gallery *************/



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
			$success : this._success.bind(this),
			$failure : this._failure.bind(this)
		});
	},
	_success : function() {
		hui.ui.showMessage({text:'Beskeden er nu sendt',icon:'common/success',duration:2000});
		this.element.reset();
	},
	_failure : function() {
		hui.ui.showMessage({text:'Beskeden kunne desværre ikke afleveres',duration:5000});
	}
}

window.define && define('op.part.Formula');

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
window.define && define('op.part.Image');

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
window.define && define('op.part.Poster');

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

window.define && define('op.part.Map');

////////////////////// Movie ////////////////////////

op.part.Movie = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
    this._attach();
}

op.part.Movie.prototype = {
    _attach : function() {
        hui.listen(this.element,'click',this._activate.bind(this));
        var poster = hui.get.firstByClass(this.element,'part_movie_poster');
        if (poster) {
            var id = poster.getAttribute('data-id');
            if (id) {
        		var x = window.devicePixelRatio || 1;
        		var url = op.context + 'services/images/?id=' + id + '&width=' + (poster.clientWidth * x) + '&height=' + (poster.clientHeight * x);
                poster.style.backgroundImage = 'url(' + url + ')';
            } else {
                var vimeoId = poster.getAttribute('data-vimeo-id');
                if (vimeoId) {
                    this._vimeo(vimeoId,poster);                    
                }
            }
        }
    },
    _activate : function() {
        var body = hui.get.firstByClass(this.element,'part_movie_body');
        var code = hui.get.firstByTag(this.element,'noscript');
        if (code) {
            body.innerHTML = hui.dom.getText(code);
        }
        body.style.background='';
    },
    _vimeo : function(id,poster) {
        var cb = 'callback_' + id;
        
        var url = "http://vimeo.com/api/v2/video/" + id + ".json?callback=" + cb;

        window[cb] = function(data) {
            poster.style.backgroundImage = 'url(' + data[0].thumbnail_large + ')';
        }
        var script = hui.build('script',{type:'text/javascript',src:url,parent:document.head});
    }
}

window.define && define('op.part.Movie');

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

window.define && define('op.SearchField');

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

window.define && define('op.Dissolver');
/* style/humanise/js/poster.js */
Poster = function() {
	this.poster = hui.get('poster');
	this.left = hui.get('poster_left');
	this.right = hui.get('poster_right');
	this.progress = hui.get('poster_loader');
	this.context = 'style/in2isoft/gfx/';
	this.leftImages = ['poster_in2isoft_image.png','poster_publisher_image.png','poster_in2igui_image.png','poster_onlineobjects_image.png'];
	this.rightImages = ['poster_in2isoft_text.png','poster_publisher_text.png','poster_in2igui_text.png','poster_onlineobjects_text.png'];

	this.leftImages = ['poster_humanise_image.png','poster_editor_image.png','poster_hui_image.png','poster_onlineobjects_image.png'];
	this.rightImages = ['poster_humanise_text.png','poster_editor_text.png','poster_hui_text.png','poster_onlineobjects_text.png'];

	this.links = ['om/','produkter/onlinepublisher/','teknologi/in2iGui/','produkter/onlineobjects/'];
	this.leftPos = -1;
	this.rightPos = -1;
	var self = this;
	this.poster.onclick = function() {
		document.location=op.page.path+self.links[self.leftPos];
	}
  this.preload();
}

Poster.prototype.start = function() {
	this.left.scrollLeft = 495;
	var self = this;
	var base = op.page.path+this.context;
	new hui.animation.Loop([
		function() {
			self.leftPos++;
			if (self.leftPos>=self.leftImages.length) {
				self.leftPos=0;
			}
			hui.get('poster_left_inner').style.backgroundImage='url(\''+base+self.leftImages[self.leftPos]+'\')';

			self.rightPos++;
			if (self.rightPos>=self.rightImages.length) self.rightPos=0;
			hui.get('poster_right_inner').style.backgroundImage='url(\''+base+self.rightImages[self.rightPos]+'\')';
		},
		{duration:1000},
		{element:this.left,property:'scrollLeft',value:'0',duration:1000,ease:hui.ease.fastSlow,wait:500},
		{element:this.right,property:'scrollLeft',value:'495',duration:1000,ease:hui.ease.fastSlow},
		{duration:4000},
		{element:this.left,property:'scrollLeft',value:'495',duration:1000,ease:hui.ease.quintIn,wait:500},
		{element:this.right,property:'scrollLeft',value:'0',duration:1000,ease:hui.ease.quintIn}
	]).start();
}

Poster.prototype.preload = function() {
	var loader = new hui.Preloader({context:op.page.path+this.context});
	loader.setDelegate({
		allImagesDidLoad : function() {
			this.progress.style.display='none';
			this.start();
		}.bind(this),
		imageDidLoad : function(loaded,total) {
			this.progress.innerHTML = Math.round(loaded/total*100)+'%';
		}.bind(this)
	});
	loader.addImages(this.leftImages);
	loader.addImages(this.rightImages);
	loader.load();
}

define('Poster',Poster);
/* style/humanise/js/layout.js */
require(['hui'],function() {

  var SearchField = function(options) {
    this.element = options.element;
    hui.collect(this.nodes);
    this._attach();
  }

  SearchField.prototype = {
    nodes : {
      icon : '.layout_search_icon',
      text : '.layout_search_text'
    },
    _attach : function() {
      hui.listen(this.nodes.icon,'click',this._toggle.bind(this));
      hui.listen(this.nodes.text,'blur',this._blur.bind(this));
      this._toggle();
    },
    _toggle : function() {
    	hui.cls.toggle(document.body,'layout_searching');
      window.setTimeout(function() {
        this.nodes.text.focus();
      }.bind(this),100)
    },
    _blur : function() {
      hui.cls.remove(document.body,'layout_searching');
    }
  }
  new SearchField({element:hui.find('.layout_search')});

});
