/**
 * An image slideshow viewer
 * <pre><strong>options:</strong> {
 *  element : «Element | ID»,
 *  name : «String»,
 *  perimeter : «Integer»,
 *  sizeSnap : «Integer»,
 *  margin : «Integer»,
 *  ease : «Function»,
 *  easeEnd : «Function»,
 *  easeAuto : «Function»,
 *  easeReturn : «Function»,
 *  transition : «Integer»,
 *  transitionEnd : «Integer»,
 *  transitionReturn : «Integer»
 * }
 * </pre>
 * @constructor
 */
hui.ui.ImageViewer = function(options) {
	
	this.options = hui.override({
		maxWidth : 800,
		maxHeight : 600,
		perimeter : 100,
		sizeSnap : 100,
		margin : 0,
		ease : hui.ease.slowFastSlow,
		easeEnd : hui.ease.bounce,
		easeAuto : hui.ease.slowFastSlow,
		easeReturn : hui.ease.cubicInOut,
		transition : 400,
		transitionEnd : 1000,
		transitionReturn : 300
	},options);
	
	// Collect elements ...
	this.element = hui.get(options.element);
	this.box = this.options.box;
	this.viewer = hui.get.firstByClass(this.element,'hui_imageviewer_viewer');
	this.innerViewer = hui.get.firstByClass(this.element,'hui_imageviewer_inner_viewer');
	
	this.status = hui.get.firstByClass(this.element,'hui_imageviewer_status');
	
	this.previousControl = hui.get.firstByClass(this.element,'hui_imageviewer_previous');
	this.controller = hui.get.firstByClass(this.element,'hui_imageviewer_controller');
	this.nextControl = hui.get.firstByClass(this.element,'hui_imageviewer_next');
	this.playControl = hui.get.firstByClass(this.element,'hui_imageviewer_play');
	this.closeControl = hui.get.firstByClass(this.element,'hui_imageviewer_close');
	
	this.text = hui.get.firstByClass(this.element,'hui_imageviewer_text');
	
	// State ...
	this.dirty = false;
	this.width = 600;
	this.height = 460;
	this.index = 0;
	this.playing = false;
	this.name = options.name;
	this.images = [];
	
	// Behavior ...
	this.box.listen(this);
	this._addBehavior();
	hui.ui.extend(this);
}

/**
 * Creates a new image viewer
 */
hui.ui.ImageViewer.create = function(options) {
	options = options || {};
	var element = options.element = hui.build('div',
		{'class':'hui_imageviewer',
		html:
		'<div class="hui_imageviewer_viewer"><div class="hui_imageviewer_inner_viewer"></div></div>'+
		'<div class="hui_imageviewer_text"></div>'+
		'<div class="hui_imageviewer_status"></div>'+
		'<div class="hui_imageviewer_controller"><div><div>'+
		'<a class="hui_imageviewer_previous"></a>'+
		'<a class="hui_imageviewer_play"></a>'+
		'<a class="hui_imageviewer_next"></a>'+
		'<a class="hui_imageviewer_close"></a>'+
		'</div></div></div>'});
	var box = options.box = hui.ui.Box.create({absolute:true,modal:true,closable:true});
	box.add(element);
	box.addToDocument();
	return new hui.ui.ImageViewer(options);
}

hui.ui.ImageViewer.prototype = {

	_addBehavior : function() {
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
		hui.listen(this.viewer,'click',this._zoom.bind(this));
		this._timer = function() {
			self.next(false);
		}
		this._keyListener = function(e) {
			e = hui.event(e);
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
		hui.listen(this.viewer,'mousemove',this._onMouseMove.bind(this));
		hui.listen(this.controller,'mouseover',function() {
			self.overController = true;
		});
		hui.listen(this.controller,'mouseout',function() {
			self.overController = false;
		});
		hui.listen(this.viewer,'mouseout',function(e) {
			if (!hui.ui.isWithin(e,this.viewer)) {
				self._hideController();
			}
		}.bind(this));
	},
	_onMouseMove : function() {
		window.clearTimeout(this.ctrlHider);
		if (this._shouldShowController()) {
			this.ctrlHider = window.setTimeout(this._hideController.bind(this),2000);
			if (hui.browser.msie) {
				this.controller.style.display='block';
			} else {
				hui.effect.fadeIn({element:this.controller,duration:200});
			}
		}
	},
	_hideController : function() {
		if (!this.overController) {
			if (hui.browser.msie) {
				this.controller.style.display='none';
			} else {
				hui.effect.fadeOut({element:this.controller,duration:500});
			}
		}
	},
	_getLargestSize : function(canvas,image) {
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
	_calculateSize : function() {
		var snap = this.options.sizeSnap;
		var newWidth = hui.window.getViewWidth() - this.options.perimeter;
		newWidth = Math.floor(newWidth / snap) * snap;
		newWidth = Math.min(newWidth , this.options.maxWidth);
		var newHeight = hui.window.getViewHeight() - this.options.perimeter;
		newHeight = Math.floor(newHeight / snap) * snap;
		newHeight = Math.min(newHeight , this.options.maxHeight);
		var maxWidth = 0;
		var maxHeight = 0;
		for (var i=0; i < this.images.length; i++) {
			var dims = this._getLargestSize({ width : newWidth, height : newHeight}, this.images[i] );
			maxWidth = Math.max(maxWidth , dims.width);
			maxHeight = Math.max(maxHeight , dims.height);
		};
		newHeight = Math.floor( Math.min(newHeight , maxHeight) );
		newWidth = Math.floor( Math.min(newWidth , maxWidth) );
		
		if (newWidth != this.width || newHeight != this.height) {
			this.width = newWidth;
			this.height = newHeight;
			this.dirty = true;
		}
	},
	_updateUI : function() {
		if (this.dirty) {
			this.innerViewer.innerHTML='';
			for (var i=0; i < this.images.length; i++) {
				var element = hui.build('div',{'class':'hui_imageviewer_image'});
				hui.style.set(element,{width: (this.width + this.options.margin) + 'px',height : (this.height-1)+'px' });
				this.innerViewer.appendChild(element);
			};
			if (this._shouldShowController()) {
				this.controller.style.display='block';
			} else {
				this.controller.style.display='none';
			}
			this.dirty = false;
			this._preload();
		}
	},
	_shouldShowController : function() {
		return this.images.length>1;
	},
	_goToImage : function(animate,num,user) {	
		if (animate) {
			if (num>1) {
				hui.animate(this.viewer,'scrollLeft',this.index*(this.width+this.options.margin),Math.min(num*this.options.transitionReturn,2000),{ease:this.options.easeReturn});				
			} else {
				var end = this.index==0 || this.index==this.images.length-1;
				var ease = (end ? this.options.easeEnd : this.options.ease);
				if (!user) {
					ease = this.options.easeAuto;
				}
				hui.animate(this.viewer,'scrollLeft',this.index*(this.width+this.options.margin),(end ? this.options.transitionEnd : this.options.transition),{ease:ease});
			}
		} else {
			this.viewer.scrollLeft = this.index*(this.width+this.options.margin);
		}
		var text = this.images[this.index].text;
		if (text) {
			this.text.innerHTML = text;
			this.text.style.display = 'block';
		} else {
			this.text.innerHTML = '';
			this.text.style.display = 'none';
		}
	},
	
	// Show / hide ...

	/** Show the image viewer starting at the image with a certain id. Will not show if image is not found
	 * @param {Integer} id The id if the image to start with
	 */
	showById: function(id) {
		for (var i=0; i < this.images.length; i++) {
			if (this.images[i].id==id) {
				this.show(i);
				break;
			}
		};
	},
	/** Show the image viewer
	 * @param {Integer} index? Optional index to start from (zero-based)
	 */
	show: function(index) {
		this.index = index || 0;
		this._calculateSize();
		this._updateUI();
		var margin = this.options.margin;
		hui.style.set(this.element, {width:(this.width+margin)+'px',height:(this.height+margin*2-1)+'px'});
		hui.style.set(this.viewer, {width:(this.width+margin)+'px',height:(this.height-1)+'px'});
		hui.style.set(this.innerViewer, {width:((this.width+margin)*this.images.length)+'px',height:(this.height-1)+'px'});
		hui.style.set(this.controller, {marginLeft:((this.width-180)/2+margin*0.5)+'px',display:'none'});
		this.box.show();
		this._goToImage(false,0,false);
		hui.listen(document,'keydown',this._keyListener);
		this.visible = true;
		this._setHash(true);
	},
	_setHash : function(visible) {
		return; // Disabled
		if (!this._listening) {
			this._listening = true;
			if (!hui.browser.msie6 && !hui.browser.msie7) {
				hui.listen(window,'hashchange',this._onHashChange.bind(this));
			}
		}
		if (visible) {
			document.location='#imageviewer';
		} else {
			hui.location.clearHash();
		}
	},
	_onHashChange : function() {
		if (this._changing) return;
		this._changing = true;
		if (hui.location.hasHash('imageviewer') && !this.visible) {
			this.show();
		} else if (!hui.location.hasHash('imageviewer') && this.visible) {
			this.hide();
		}
		this._changing = false;
	},
	/** Hide the image viewer */
	hide: function() {
		this._hide();
	},
	_hide : function() {
		this.pause();
		this.box.hide();
		hui.unListen(document,'keydown',this._keyListener);
		this.visible = false;
		this._setHash(false);	
	},


	// Listeners ...

	/** @private */
	$boxCurtainWasClicked : function() {
		this.hide();
	},
	/** @private */
	$boxWasClosed : function() {
		this.hide();
	},
	
	
	// Data handling ...
	
	/** Clear all images in the stack */
	clearImages : function() {
		this.images = [];
		this.dirty = true;
	},
	/**
	 * Add multiple images to the stack
	 * @param {Array} images An array of image objects
	 */
	addImages : function(images) {
		for (var i=0; i < images.length; i++) {
			this.addImage(images[i]);
		};
	},
	/**
	 * Add an image to the stack
	 * @param {Object} img An image object representing an image
	 */
	addImage : function(img) {
		this.images.push(img);
		this.dirty = true;
	},
	
	
	// Playback...
	
	/** Start playing slideshow */
	play : function() {
		if (!this.interval) {
			this.interval = window.setInterval(this._timer,6000);
		}
		this.next(false);
		this.playing=true;
		this.playControl.className='hui_imageviewer_pause';
	},
	/** Pauseslideshow */
	pause : function() {
		window.clearInterval(this.interval);
		this.interval = null;
		this.playControl.className='hui_imageviewer_play';
		this.playing = false;
	},
	/** Start or pause slideshow */
	playOrPause : function() {
		if (this.playing) {
			this.pause();
		} else {
			this.play();
		}
	},
	_resetPlay : function() {
		if (this.playing) {
			window.clearInterval(this.interval);
			this.interval = window.setInterval(this._timer,6000);
		}
	},
	/** Go to the previous image
	 * @param {Boolean} user If it is initiated by the user
	 */
	previous : function(user) {
		var num = 1;
		this.index--;
		if (this.index<0) {
			this.index=this.images.length-1;
			num = this.images.length-1;
		}
		this._goToImage(true,num,user);
		this._resetPlay();
	},
	/** Go to the next image
	 * @param {Boolean} user If it is initiated by the user
 	 */
	next : function(user) {
		var num = 1;
		this.index++;
		if (this.index==this.images.length) {
			this.index=0;
			num = this.images.length-1;
		}
		this._goToImage(true,num,user);
		this._resetPlay();
	},
	
	
	
	
	
	
	// Preloading ...
	
	_preload : function() {
		var guiLoader = new hui.Preloader();
		guiLoader.addImages(hui.ui.context+'/hui/gfx/imageviewer_controls.png');
		var self = this;
		guiLoader.setDelegate({allImagesDidLoad:function() {self._preloadImages()}});
		guiLoader.load();
	},
	_preloadImages : function() {
		var loader = new hui.Preloader();
		loader.setDelegate(this);
		for (var i=0; i < this.images.length; i++) {
			var url = hui.ui.resolveImageUrl(this,this.images[i],this.width,this.height);
			if (url!==null) {
				loader.addImages(url);
			}
		};
		this.status.innerHTML = '0%';
		this.status.style.display = '';
		loader.load(this.index);
	},
	/** @private */
	allImagesDidLoad : function() {
		this.status.style.display = 'none';
	},
	/** @private */
	imageDidLoad : function(loaded,total,index) {
		this.status.innerHTML = Math.round(loaded/total*100)+'%';
		var url = hui.ui.resolveImageUrl(this,this.images[index],this.width,this.height);
		url = url.replace(/&amp;/g,'&');
		this.innerViewer.childNodes[index].style.backgroundImage="url('"+url+"')";
		hui.cls.set(this.innerViewer.childNodes[index],'hui_imageviewer_image_abort',false);
		hui.cls.set(this.innerViewer.childNodes[index],'hui_imageviewer_image_error',false);
	},
	/** @private */
	imageDidGiveError : function(loaded,total,index) {
		hui.cls.set(this.innerViewer.childNodes[index],'hui_imageviewer_image_error',true);
	},
	/** @private */
	imageDidAbort : function(loaded,total,index) {
		hui.cls.set(this.innerViewer.childNodes[index],'hui_imageviewer_image_abort',true);
	},
	
	
	
	
	// Zooming ...

	_zoom : function(e) {
		var img = this.images[this.index];
		if (img.width<=this.width && img.height<=this.height) {
			return; // Don't zoom if small
		}
		if (!this.zoomer) {
			this.zoomer = hui.build('div',{
				'class' : 'hui_imageviewer_zoomer',
				style : 'width:'+this.viewer.clientWidth+'px;height:'+this.viewer.clientHeight+'px'
			});
			this.element.insertBefore(this.zoomer,hui.dom.firstChild(this.element));
			hui.listen(this.zoomer,'mousemove',this._onZoomMove.bind(this));
			hui.listen(this.zoomer,'click',function() {
				this.zoomer.style.display='none';
			}.bind(this));
		}
		this.pause();
		var size = this._getLargestSize({width:2000,height:2000},img);
		var url = hui.ui.resolveImageUrl(this,img,size.width,size.height);
		this.zoomer.innerHTML = '<div style="width:'+size.width+'px;height:'+size.height+'px; margin: 0 auto;"><img src="'+url+'"/></div>';
		this.zoomer.style.display = 'block';
		this.zoomInfo = {width:size.width,height:size.height};
		this._onZoomMove(e);
	},
	_onZoomMove : function(e) {
		e = new hui.Event(e);
		if (!this.zoomInfo) {
			return;
		}
		var offset = {left:hui.position.getLeft(this.zoomer),top:hui.position.getTop(this.zoomer)};
		var x = (e.getLeft()-offset.left)/this.zoomer.clientWidth*(this.zoomInfo.width-this.zoomer.clientWidth);
		var y = (e.getTop()-offset.top)/this.zoomer.clientHeight*(this.zoomInfo.height-this.zoomer.clientHeight);
		this.zoomer.scrollLeft = x;
		this.zoomer.scrollTop = y;
	}
	
}

/* EOF */