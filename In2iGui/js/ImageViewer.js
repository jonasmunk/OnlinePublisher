/**
 @constructor
 */
In2iGui.ImageViewer = function(options) {
	this.options = n2i.override({
		maxWidth:800,maxHeight:600,perimeter:100,sizeSnap:100,
		margin:0,
		ease:n2i.ease.slowFastSlow,
		easeEnd:n2i.ease.bounce,
		easeAuto:n2i.ease.slowFastSlow,
		easeReturn:n2i.ease.cubicInOut,transition:400,transitionEnd:1000,transitionReturn:300
		},options);
	this.element = n2i.get(options.element);
	this.box = this.options.box;
	this.viewer = n2i.firstByClass(this.element,'in2igui_imageviewer_viewer');
	this.innerViewer = n2i.firstByClass(this.element,'in2igui_imageviewer_inner_viewer');
	this.status = n2i.firstByClass(this.element,'in2igui_imageviewer_status');
	this.previousControl = n2i.firstByClass(this.element,'in2igui_imageviewer_previous');
	this.controller = n2i.firstByClass(this.element,'in2igui_imageviewer_controller');
	this.nextControl = n2i.firstByClass(this.element,'in2igui_imageviewer_next');
	this.playControl = n2i.firstByClass(this.element,'in2igui_imageviewer_play');
	this.closeControl = n2i.firstByClass(this.element,'in2igui_imageviewer_close');
	this.text = n2i.firstByClass(this.element,'in2igui_imageviewer_text');
	this.dirty = false;
	this.width = 600;
	this.height = 460;
	this.index = 0;
	this.playing=false;
	this.name = options.name;
	this.images = [];
	this.box.listen(this);
	this.addBehavior();
	In2iGui.extend(this);
}

In2iGui.ImageViewer.create = function(options) {
	options = options || {};
	var element = options.element = n2i.build('div',
		{'class':'in2igui_imageviewer',
		html:
		'<div class="in2igui_imageviewer_viewer"><div class="in2igui_imageviewer_inner_viewer"></div></div>'+
		'<div class="in2igui_imageviewer_text"></div>'+
		'<div class="in2igui_imageviewer_status"></div>'+
		'<div class="in2igui_imageviewer_controller"><div><div>'+
		'<a class="in2igui_imageviewer_previous"></a>'+
		'<a class="in2igui_imageviewer_play"></a>'+
		'<a class="in2igui_imageviewer_next"></a>'+
		'<a class="in2igui_imageviewer_close"></a>'+
		'</div></div></div>'});
	var box = In2iGui.Box.create({absolute:true,modal:true,closable:true});
	box.add(element);
	box.addToDocument();
	options.box=box;
	return new In2iGui.ImageViewer(options);
}

In2iGui.ImageViewer.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		this.nextControl.onclick = function() {
			self.next(true);
		}
		this.previousControl.onclick = function() {
			self.previous(true);
		}
		this.playControl.onclick = function() {
			self.playOrPause();
		}
		this.closeControl.onclick = this.hide.bind(this);
		n2i.listen(this.viewer,'click',this.zoom.bind(this));
		this.timer = function() {
			self.next(false);
		}
		this.keyListener = function(e) {
			e = n2i.event(e);
			if (e.rightKey) {
				self.next(true);
			} else if (e.leftKey) {
				self.previous(true);
			} else if (e.escapeKey) {
				self.hide();
			} else if (e.returnKey) {
				self.playOrPause();
			}
		},
		n2i.listen(this.viewer,'mousemove',this.mouseMoveEvent.bind(this));
		n2i.listen(this.controller,'mouseenter',function() {
			self.overController = true;
		});
		n2i.listen(this.controller,'mouseleave',function() {
			self.overController = false;
		});
		n2i.listen(this.viewer,'mouseout',function(e) {
			if (!In2iGui.isWithin(e,this.viewer)) {
				self.hideController();
			}
		}.bind(this));
	},
	/** @private */
	mouseMoveEvent : function() {
		window.clearTimeout(this.ctrlHider);
		if (this.shouldShowController()) {
			this.ctrlHider = window.setTimeout(this.hideController.bind(this),2000);
			if (n2i.browser.msie) {
				this.controller.show();
			} else {
				In2iGui.fadeIn(this.controller,200);
			}
		}
	},
	/** @private */
	hideController : function() {
		if (!this.overController) {
			if (n2i.browser.msie) {
				this.controller.hide();
			} else {
				In2iGui.fadeOut(this.controller,500);
			}
		}
	},
	/** @private */
	zoom : function(e) {
		var img = this.images[this.index];
		if (img.width<=this.width && img.height<=this.height) {
			return; // Don't zoom if small
		}
		if (!this.zoomer) {
			this.zoomer = n2i.build('div',{
				'class' : 'in2igui_imageviewer_zoomer',
				style : 'width:'+this.viewer.clientWidth+'px;height:'+this.viewer.clientHeight+'px'
			});
			this.element.insertBefore(this.zoomer,n2i.firstByTag(this.element,'*'));
			n2i.listen(this.zoomer,'mousemove',this.zoomMove.bind(this));
			n2i.listen(this.zoomer,'click',function() {
				this.style.display='none';
			});
		}
		this.pause();
		var size = this.getLargestSize({width:2000,height:2000},img);
		var url = In2iGui.resolveImageUrl(this,img,size.width,size.height);
		this.zoomer.innerHTML = '<div style="width:'+size.width+'px;height:'+size.height+'px;"><img src="'+url+'"/></div>';
		this.zoomer.style.display = 'block';
		this.zoomInfo = {width:size.width,height:size.height};
		this.zoomMove(e);
	},
	zoomMove : function(e) {
		e = new n2i.Event(e);
		if (!this.zoomInfo) {
			return;
		}
		var offset = {left:n2i.getLeft(this.zoomer),top:n2i.getTop(this.zoomer)};
		var x = (e.left()-offset.left)/this.zoomer.clientWidth*(this.zoomInfo.width-this.zoomer.clientWidth);
		var y = (e.top()-offset.top)/this.zoomer.clientHeight*(this.zoomInfo.height-this.zoomer.clientHeight);
		this.zoomer.scrollLeft = x;
		this.zoomer.scrollTop = y;
	},
	/** @private */
	getLargestSize : function(canvas,image) {
		if (image.width<=canvas.width && image.height<=canvas.height) {
			return {width:image.width,height:image.height};
		} else if (canvas.width/canvas.height>image.width/image.height) {
			return {width:Math.round(canvas.height/image.height*image.width),height:canvas.height};
		} else if (canvas.width/canvas.height<image.width/image.height) {
			return {width:canvas.width,height:Math.round(canvas.width/image.width*image.height)};
		} else {
			return {width:canvas.width,height:canvas.height};
		}
	},
	/** @private */
	calculateSize : function() {
		var snap = this.options.sizeSnap;
		var newWidth = n2i.getViewPortWidth()-this.options.perimeter;
		newWidth = Math.floor(newWidth/snap)*snap;
		newWidth = Math.min(newWidth,this.options.maxWidth);
		var newHeight = n2i.getViewPortHeight()-this.options.perimeter;
		newHeight = Math.floor(newHeight/snap)*snap;
		newHeight = Math.min(newHeight,this.options.maxHeight);
		var maxWidth = 0;
		var maxHeight = 0;
		for (var i=0; i < this.images.length; i++) {
			var dims = this.getLargestSize({width:newWidth,height:newHeight},this.images[i]);
			maxWidth = Math.max(maxWidth,dims.width);
			maxHeight = Math.max(maxHeight,dims.height);
		};
		newHeight = Math.floor(Math.min(newHeight,maxHeight));
		newWidth = Math.floor(Math.min(newWidth,maxWidth));
		
		if (newWidth!=this.width || newHeight!=this.height) {
			this.width = newWidth;
			this.height = newHeight;
			this.dirty = true;
		}
	},
	adjustSize : function() {
		
	},
	showById: function(id) {
		for (var i=0; i < this.images.length; i++) {
			if (this.images[i].id==id) {
				this.show(i);
				break;
			}
		};
	},
	show: function(index) {
		this.index = index || 0;
		this.calculateSize();
		this.updateUI();
		var margin = this.options.margin;
		n2i.setStyle(this.element, {width:(this.width+margin)+'px',height:(this.height+margin*2-1)+'px'});
		n2i.setStyle(this.viewer, {width:(this.width+margin)+'px',height:(this.height-1)+'px'});
		n2i.setStyle(this.innerViewer, {width:((this.width+margin)*this.images.length)+'px',height:(this.height-1)+'px'});
		n2i.setStyle(this.controller, {marginLeft:((this.width-180)/2+margin*0.5)+'px',display:'none'});
		this.box.show();
		this.goToImage(false,0,false);
		n2i.listen(document,'keydown',this.keyListener);
	},
	hide: function(index) {
		this.pause();
		this.box.hide();
		n2i.unListen(document,'keydown',this.keyListener);
	},
	/** @private */
	$boxCurtainWasClicked : function() {
		this.hide();
	},
	/** @private */
	$boxWasClosed : function() {
		this.hide();
	},
	/** @private */
	updateUI : function() {
		if (this.dirty) {
			this.innerViewer.innerHTML='';
			for (var i=0; i < this.images.length; i++) {
				var element = n2i.build('div',{'class':'in2igui_imageviewer_image'});
				n2i.setStyle(element,{'width':(this.width+this.options.margin)+'px','height':(this.height-1)+'px'});
				this.innerViewer.appendChild(element);
			};
			if (this.shouldShowController()) {
				this.controller.style.display='block';
			} else {
				this.controller.style.display='none';
			}
			this.dirty = false;
			this.preload();
		}
	},
	/** @private */
	shouldShowController : function() {
		return this.images.length>1;
	},
	/** @private */
	goToImage : function(animate,num,user) {	
		if (animate) {
			if (num>1) {
				n2i.ani(this.viewer,'scrollLeft',this.index*(this.width+this.options.margin),Math.min(num*this.options.transitionReturn,2000),{ease:this.options.easeReturn});				
			} else {
				var end = this.index==0 || this.index==this.images.length-1;
				var ease = (end ? this.options.easeEnd : this.options.ease);
				if (!user) {
					ease = this.options.easeAuto;
				}
				n2i.ani(this.viewer,'scrollLeft',this.index*(this.width+this.options.margin),(end ? this.options.transitionEnd : this.options.transition),{ease:ease});
			}
		} else {
			this.viewer.scrollLeft=this.index*(this.width+this.options.margin);
		}
		var text = this.images[this.index].text;
		if (text) {
			this.text.innerHTML=text;
			this.text.style.display='block';
		} else {
			this.text.innerHTML='';
			this.text.style.display='none';
		}
	},
	clearImages : function() {
		this.images = [];
		this.dirty = true;
	},
	addImages : function(images) {
		for (var i=0; i < images.length; i++) {
			this.addImage(images[i]);
		};
	},
	addImage : function(img) {
		this.images.push(img);
		this.dirty = true;
	},
	play : function() {
		if (!this.interval) {
			this.interval = window.setInterval(this.timer,6000);
		}
		this.next(false);
		this.playing=true;
		this.playControl.className='in2igui_imageviewer_pause';
	},
	pause : function() {
		window.clearInterval(this.interval);
		this.interval = null;
		this.playControl.className='in2igui_imageviewer_play';
		this.playing = false;
	},
	playOrPause : function() {
		if (this.playing) {
			this.pause();
		} else {
			this.play();
		}
	},
	resetPlay : function() {
		if (this.playing) {
			window.clearInterval(this.interval);
			this.interval = window.setInterval(this.timer,6000);
		}
	},
	previous : function(user) {
		var num = 1;
		this.index--;
		if (this.index<0) {
			this.index=this.images.length-1;
			num = this.images.length-1;
		}
		this.goToImage(true,num,user);
		this.resetPlay();
	},
	next : function(user) {
		var num = 1;
		this.index++;
		if (this.index==this.images.length) {
			this.index=0;
			num = this.images.length-1;
		}
		this.goToImage(true,num,user);
		this.resetPlay();
	},
	/** @private */
	preload : function() {
		var guiLoader = new n2i.Preloader();
		guiLoader.addImages(In2iGui.context+'In2iGui/gfx/imageviewer_controls.png');
		var self = this;
		guiLoader.setDelegate({allImagesDidLoad:function() {self.preloadImages()}});
		guiLoader.load();
	},
	/** @private */
	preloadImages : function() {
		var loader = new n2i.Preloader();
		loader.setDelegate(this);
		for (var i=0; i < this.images.length; i++) {
			var url = In2iGui.resolveImageUrl(this,this.images[i],this.width,this.height);
			if (url!==null) {
				loader.addImages(url);
			}
		};
		this.status.innerHTML = '0%';
		this.status.style.display='';
		loader.load(this.index);
	},
	/** @private */
	allImagesDidLoad : function() {
		this.status.style.display='none';
	},
	/** @private */
	imageDidLoad : function(loaded,total,index) {
		this.status.innerHTML = Math.round(loaded/total*100)+'%';
		var url = In2iGui.resolveImageUrl(this,this.images[index],this.width,this.height);
		url = url.replace(/&amp;/g,'&');
		this.innerViewer.childNodes[index].style.backgroundImage="url('"+url+"')";
		n2i.setClass(this.innerViewer.childNodes[index],'in2igui_imageviewer_image_abort',false);
		n2i.setClass(this.innerViewer.childNodes[index],'in2igui_imageviewer_image_error',false);
	},
	/** @private */
	imageDidGiveError : function(loaded,total,index) {
		n2i.setClass(this.innerViewer.childNodes[index],'in2igui_imageviewer_image_error',true);
	},
	/** @private */
	imageDidAbort : function(loaded,total,index) {
		n2i.setClass(this.innerViewer.childNodes[index],'in2igui_imageviewer_image_abort',true);
	}
}

/* EOF */