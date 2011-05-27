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
}

op.showLogin = function() {
	if (!this.loginBox) {
		if (this.loadingLogin) {return}
		this.loadingLogin = true;
		hui.ui.showMessage({text:'Indlæser...',busy:true,delay:300});
		hui.ui.require(['Formula','Button'],
			function() {
				hui.ui.hideMessage();
				var box = this.loginBox = hui.ui.Box.create({width:300,title:'Adgangskontrol',modal:true,absolute:true,closable:true,padding:10});
				this.loginBox.addToDocument();
				var form = hui.ui.Formula.create();
				form.listen({$submit:function() {
					var values = form.getValues();
					op.login(values.username,values.password);
				}});
				var g = form.buildGroup(null,[
					{type:'Text',options:{label:'Brugernavn',key:'username'}},
					{type:'Text',options:{label:'Kodeord',key:'password',secret:true}}
				]);
				var b = g.createButtons();
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
			}
		);
	} else {
		this.loginBox.show();
	}
}

op.login = function(username,password) {
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
	n2i.get(id).onclick = function() {
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
	this.element = n2i.get(options.element);
	this.images = [];
}

op.part.ImageGallery.prototype = {
	registerImage : function(node,image) {
		node = n2i.get(node);
		if (this.options.editor) {
			return;
		}
		this.images.push(image);
		var self = this;
		node.onclick = function(e) {
			n2i.stop(e);
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
				n2i.animate(nodes[index],'opacity',0,200,{hideOnComplete:true,delay:800});
			}
			index++;
			if (index>nodes.length-1) {
				index = 0;
			}
			if (!first) {
			n2i.setOpacity(nodes[index],0)
			nodes[index].style.zIndex=zIndex;
			nodes[index].style.display='block';
			n2i.animate(nodes[index],'opacity',1,1000,{ease:n2i.ease.slowFastSlow});
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
	this.element = n2i.get(options.element);
	this.id = options.id;
	n2i.listen(this.element,'submit',this._send.bind(this));
}

op.part.Formula.prototype = {
	_send : function(e) {
		n2i.stop(e);
		var name = this.element.name.value;
		var email = this.element.email.value;
		var message = this.element.message.value;
		if (n2i.isBlank(name) || n2i.isBlank(email) || n2i.isBlank(message)) {
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
	this.element = n2i.get(options.element);
	this.pages = n2i.byClass(this.element,'part_poster_page');
	this.index = 0;
	this.delay = 5000;
	this.callNext();
}

op.part.Poster.prototype = {
	next : function() {
		this.pages[this.index].style.display='none';
		this.index++;
		if (this.index>=this.pages.length) {
			this.index = 0;
		}
		this.pages[this.index].style.display='block';
		this.callNext();
	},
	callNext : function() {
		window.setTimeout(this.next.bind(this),this.delay);
	}
}

///////////////// Search field //////////////////

op.SearchField = function(o) {
	o = this.options = n2i.override({placeholderClass:'placeholder',placeholder:''},o);
	this.field = n2i.get(o.element);
	this.field.onfocus = function() {
		if (this.field.value==o.placeholder) {
			this.field.value = '';
			n2i.addClass(this.field,o.placeholderClass);
		} else {
			this.field.select();
		}
	}.bind(this);
	this.field.onblur = function() {
		if (this.field.value=='') {
			n2i.addClass(this.field,o.placeholderClass);
			this.field.value=o.placeholder;
		}
	}.bind(this);
	this.field.onblur();
}

op.Dissolver = function(options) {
	options = this.options = n2i.override({wait:4000,transition:2000,delay:0},options);
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
		n2i.setOpacity(e,0);
		n2i.setStyle(e,{display:'block',zIndex:this.z});
		n2i.ani(e,'opacity',1,this.options.transition,{ease:n2i.ease.slowFastSlow,onComplete:function() {
			window.setTimeout(this.next.bind(this),this.options.wait);
		}.bind(this)});
	}
}