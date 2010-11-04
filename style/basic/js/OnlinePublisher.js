if (!op) {
	var op = {};
}

op.preview = false;
op.page = {id:null,path:null,template:null};

op.ignite = function() {
	if (!this.preview) {
		document.onkeypress=function keypresshandler(e) {
			if(document.all) e=window.event;
			if(e.keyCode==13 && e.shiftKey==true) {
				window.location=(op.page.path+"Editor/index.php?page="+op.page.id);
			}
			return true;
		}
	}
}

op.showImage = function(image) {
	if (!this.imageViewer) {
		this.imageViewer = In2iGui.ImageViewer.create({maxWidth:2000,maxHeight:2000,perimeter:60,sizeSnap:10});
		this.imageViewer.listen(op.imageViewerDelegate);
	}
	this.imageViewer.clearImages();
	this.imageViewer.addImage(image);
	this.imageViewer.show();
}

op.registerImageViewer = function(id,image) {
	$(id).onclick = function() {
		op.showImage(image);
		return false;
	}
}

op.imageViewerDelegate = {
	$resolveImageUrl : function(img,width,height) {
		var w = img.width ? Math.min(width,img.width) : width;
		var h = img.height ? Math.min(height,img.height) : height;
		return op.page.path+'util/images/?id='+img.id+'&maxwidth='+w+'&maxheight='+h+'&format=jpg&quality=100';
	}
}

op.showVideo = function(video) {
	if (!this.videoViewer) {
		this.videoViewer = new op.VideoViewer();
	}
	this.videoViewer.show(video);
}

op.VideoViewer = function() {
	this.box = In2iGui.Box.create(null,{absolute:true,modal:true})
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
	this.element = $(options.element);
	this.images = [];
}

op.part.ImageGallery.prototype = {
	registerImage : function(node,image) {
		if (this.options.editor) {
			return;
		}
		this.images.push(image);
		var self = this;
		$(node).onclick = function() {
			self.showImage(image.id);
			return false;
		}
	},
	showImage : function(id) {
		if (!this.viewer) {
			this.imageViewer = In2iGui.ImageViewer.create({maxWidth:2000,maxHeight:2000});
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

op.part.Formula = function(options) {
	this.element = $(options.element);
	this.id = options.id;
	this.element.observe('submit',this._send.bind(this));
}

op.part.Formula.prototype = {
	_send : function(e) {
		e.stop();
		var name = this.element.name.value;
		var email = this.element.email.value;
		var message = this.element.message.value;
		if (n2i.isBlank(name) || n2i.isBlank(email) || n2i.isBlank(message)) {
			ui.showMessage({text:'Alle felter skal udfyldes',duration:2000});
			this.element.name.focus();
			return;
		}
		ui.showMessage({text:'Sender besked...'});
		var url = op.page.path+'services/parts/formula/';
		var parms = {name:name,email:email,message:message,id:this.id};
		ui.request({url:url,parameters:parms,onSuccess:this._onSuccess.bind(this),onFailure:this._onFailure.bind(this)});
	},
	_onSuccess : function() {
		ui.showMessage({text:'Beskeden er nu sendt',duration:2000});
		this.element.reset();
	},
	_onFailure : function() {
		ui.showMessage({text:'Beskeden kunne desværre ikke afleveres',duration:5000});
	}
}

op.SearchField = function(o) {
	o = this.options = n2i.override({placeholderClass:'placeholder',placeholder:''},o);
	this.field = $(o.element);
	this.field.onfocus = function() {
		if (this.field.value==o.placeholder) {
			this.field.value = '';
			this.field.addClassName(o.placeholderClass);
		} else {
			this.field.select();
		}
	}.bind(this);
	this.field.onblur = function() {
		if (this.field.value=='') {
			this.field.addClassName(o.placeholderClass);
			this.field.value=o.placeholder;
		}
	}.bind(this);
	this.field.onblur();
}

op.Dissolver = function(options) {
	options = this.options = n2i.override({wait:4000,transition:2000,delay:0},options);
	this.pos = Math.floor(Math.random()*(options.elements.length-.00001));
	this.z = 1;
	options.elements[this.pos].setStyle({display:'block'});
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
		e.setStyle({display:'block',zIndex:this.z});
		n2i.ani(e,'opacity',1,this.options.transition,{ease:n2i.ease.slowFastSlow,onComplete:function() {
			window.setTimeout(this.next.bind(this),this.options.wait);
		}.bind(this)});
	}
}