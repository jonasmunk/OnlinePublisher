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
 *  transitionReturn : «Integer»,
 *  images : «Array»,
 *  listener : «Object»
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
		transitionReturn : 300,
		images : []
	},options);
	
	// Collect elements ...
	this.element = hui.get(options.element);

	this.box = this.options.box;
	
	// State ...
	this.dirty = false;
	this.width = 600;
	this.height = 460;
	this.index = 0;
	this.position = 0; // pixels
	this.playing = false;
	this.name = options.name;
	this.images = options.images || [];

	hui.ui.extend(this);
	
	// Behavior ...
	this.box.listen(this);
	this._attach();
	this._attachDrag();
	
	if (options.listener) {
		this.listen(options.listener);
	}
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
	var box = options.box = hui.ui.Box.create({variant:'plain',absolute:true,modal:true,closable:true});
	box.add(element);
	box.addToDocument();
	return new hui.ui.ImageViewer(options);
}

hui.ui.ImageViewer.prototype = {

	nodes : {
		viewer : '.hui_imageviewer_viewer',
		innerViewer : '.hui_imageviewer_inner_viewer',

		status : '.hui_imageviewer_status',
		text : '.hui_imageviewer_text',

		previous : '.hui_imageviewer_previous',
		controller : '.hui_imageviewer_controller',
		next : '.hui_imageviewer_next',
		play : '.hui_imageviewer_play',
		close : '.hui_imageviewer_close'
	},

	_attach : function() {
		var self = this;
		this.nodes.next.onclick = function() {
			self.next(true);
		}
		this.nodes.previous.onclick = function() {
			self.previous(true);
		}
		this.nodes.play.onclick = function() {
			self.playOrPause();
		}
		this.nodes.close.onclick = this.hide.bind(this);

		this._timer = function() {
			self.next(false);
		}
		this._keyListener = function(e) {
			e = hui.event(e);
			if (e.escapeKey) {
				self.hide();
			} else if (!self.zoomed) {
				if (e.rightKey) {
					self.next(true);
				} else if (e.leftKey) {
					self.previous(true);
				} else if (e.returnKey) {
					self.playOrPause();
				}				
			}
		},
		hui.listen(this.nodes.viewer,'mousemove',this._onMouseMove.bind(this));
		hui.listen(this.nodes.controller,'mouseover',function() {
			self.overController = true;
		});
		hui.listen(this.nodes.controller,'mouseout',function() {
			self.overController = false;
		});
		hui.listen(this.nodes.viewer,'mouseout',function(e) {
			if (!hui.ui.isWithin(e,this.nodes.viewer)) {
				self._hideController();
			}
		}.bind(this));
	},
	_draw : function(pos) {
		if (hui.browser.webkit) {
			this.nodes.innerViewer.style.webkitTransform = 'translate3d(' + this.position + 'px,0,0)';			
		} else {
			this.nodes.innerViewer.style.marginLeft = this.position + 'px';
		}
	},
	_attachDrag : function() {
		var initial = 0;
		var left = 0;
		var scrl = 0;
		var viewer = this.nodes.viewer;
		var inner = this.nodes.innerViewer;
		var max = 0;
		hui.drag.register({
			touch : true,
			element : this.nodes.innerViewer,
			onBeforeMove : function(e) {
				initial = e.getLeft();
				scrl = this.position;
				max = (this.images.length-1) * this.width * -1;
			}.bind(this),
			onMove : function(e) {
				left = e.getLeft();
				var pos = (scrl - (initial - left));
				if (pos > 0) {
					pos = (Math.exp(pos * -0.013) -1) * -80;
				}
				if (pos < max) {
					pos = (Math.exp((pos - max) * 0.013) -1) * 80 + max;
				}
				this.position = pos;
				this._draw();
			}.bind(this),
			onAfterMove : function() {
				var func = (initial - left) < 0 ? Math.floor : Math.ceil;
				this.index = func(this.position * -1 / this.width);
				var num = this.images.length - 1;
				if (this.index==this.images.length) {
					this.index = 0;
				} else if (this.index < 0) {
					this.index = this.images.length - 1;
				} else {
					num = 1;
				}
				
				this._goToImage(true,num,false,true);
			}.bind(this),
			onNotMoved : this._zoom.bind(this)
		})
	},
	_onMouseMove : function() {
		window.clearTimeout(this.ctrlHider);
		if (this._shouldShowController()) {
			this.ctrlHider = window.setTimeout(this._hideController.bind(this),2000);
			if (!hui.browser.opacity) {
				this.nodes.controller.style.display='block';
			} else {
				hui.effect.fadeIn({element:this.nodes.controller,duration:200});
			}
		}
	},
	_hideController : function() {
		if (!this.overController) {
			if (!hui.browser.opacity) {
				this.nodes.controller.style.display='none';
			} else {
				hui.effect.fadeOut({element:this.nodes.controller,duration:500});
			}
		}
	},
	_getLargestSize : function(canvas,image) {
		return hui.fit(image,canvas,{upscale:false});
	},
	_calculateSize : function() {
		var snap = this.options.sizeSnap;
		var newWidth = hui.window.getViewWidth() - this.options.perimeter;
		newWidth = Math.floor(newWidth / snap) * snap;
		newWidth = Math.min(newWidth, this.options.maxWidth);
		var newHeight = hui.window.getViewHeight() - this.options.perimeter;
		newHeight = Math.floor(newHeight / snap) * snap;
		newHeight = Math.min(newHeight, this.options.maxHeight);
		var maxWidth = 0;
		var maxHeight = 0;
		for (var i = 0; i < this.images.length; i++) {
			var dims = this._getLargestSize({
				width: newWidth,
				height: newHeight
			}, this.images[i]);
			maxWidth = Math.max(maxWidth, dims.width);
			maxHeight = Math.max(maxHeight, dims.height);
		};
		newHeight = Math.floor(Math.min(newHeight, maxHeight));
		newWidth = Math.floor(Math.min(newWidth, maxWidth));

		if (newWidth != this.width || newHeight != this.height) {
			this.width = newWidth;
			this.height = newHeight;
			this.dirty = true;
		}

	},
	_updateUI : function() {
		if (this.dirty) {
			this.nodes.innerViewer.innerHTML='';
			for (var i=0; i < this.images.length; i++) {
				var element = hui.build('div',{'class':'hui_imageviewer_image'});
				hui.style.set(element,{width: (this.width + this.options.margin) + 'px',height : (this.height-1)+'px' });
				this.nodes.innerViewer.appendChild(element);
			};
			this.nodes.controller.style.display = this._shouldShowController() ? 'block' : 'none';
			this.dirty = false;
			this._preload();
		}
	},
	_shouldShowController : function() {
		return this.images.length > 1;
	},
	_goToImage : function(animate,num,user,drag) {
		var initial = this.position;
		var target = this.position = this.index * (this.width + this.options.margin) * -1;
		if (animate) {
			var duration, ease;
			if (drag) {
				duration = 200 * num;
				ease = hui.ease.fastSlow;
				ease = hui.ease.quadOut;
			}
			else if (num > 1) {
				duration = Math.min(num * this.options.transitionReturn, 2000)
				ease = this.options.easeReturn;
			} else {
				var end = this.index == 0 || this.index == this.images.length - 1;
				ease = (end ? this.options.easeEnd : this.options.ease);
				if (!user) {
					ease = this.options.easeAuto;
				}
				duration = (end ? this.options.transitionEnd : this.options.transition);
			}
			hui.animate({
				node : this.nodes.innerViewer, 
				css : {marginLeft : target + 'px'}, 
				duration : duration,
				ease : ease
				,$render : function(node,v) {
					this.position = initial + (target - initial) * v;
					this._draw();
				}.bind(this)
			});
		} else {
			this._draw();
		}
		this._drawText();
	},
	
	_drawText : function() {
		var text = this.images[this.index].text;
		if (text) {
			this.nodes.text.innerHTML = text;
			this.nodes.text.style.display = 'block';
		} else {
			this.nodes.text.innerHTML = '';
			this.nodes.text.style.display = 'none';
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
		hui.style.set(this.element, {
			width: (this.width + margin) + 'px',
			height: (this.height + margin * 2 - 1) + 'px'
		});
		hui.style.set(this.nodes.viewer, {
			width: (this.width + margin) + 'px',
			height: (this.height - 1) + 'px'
		});
		hui.style.set(this.nodes.innerViewer, {
			width: ((this.width + margin) * this.images.length) + 'px',
			height: (this.height - 1) + 'px'
		});
		hui.style.set(this.nodes.controller, {
			marginLeft: ((this.width - 160) / 2 + margin * 0.5) + 'px',
			display: 'none'
		});
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
		this._endZoom();
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
		this.nodes.play.className='hui_imageviewer_pause';
	},
	/** Pauseslideshow */
	pause : function() {
		window.clearInterval(this.interval);
		this.interval = null;
		this.nodes.play.className='hui_imageviewer_play';
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
		if (this.index < 0) {
			this.index = this.images.length - 1;
			num = this.images.length - 1;
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
			this.index = 0;
			num = this.images.length - 1;
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
		this.nodes.status.innerHTML = '0%';
		this.nodes.status.style.display = '';
		loader.load(this.index);
	},
	/** @private */
	allImagesDidLoad : function() {
		this.nodes.status.style.display = 'none';
	},
	/** @private */
	imageDidLoad : function(loaded,total,index) {
		this.nodes.status.innerHTML = Math.round(loaded/total*100)+'%';
		var url = hui.ui.resolveImageUrl(this,this.images[index],this.width,this.height);
		url = url.replace(/&amp;/g,'&');
		this.nodes.innerViewer.childNodes[index].style.backgroundImage="url('"+url+"')";
		hui.cls.set(this.nodes.innerViewer.childNodes[index],'hui_imageviewer_image_abort',false);
		hui.cls.set(this.nodes.innerViewer.childNodes[index],'hui_imageviewer_image_error',false);
	},
	/** @private */
	imageDidGiveError : function(loaded,total,index) {
		hui.cls.set(this.nodes.innerViewer.childNodes[index],'hui_imageviewer_image_error',true);
	},
	/** @private */
	imageDidAbort : function(loaded,total,index) {
		hui.cls.set(this.nodes.innerViewer.childNodes[index],'hui_imageviewer_image_abort',true);
	},
	
	
	
	
	// Zooming ...
	
	zoomed : false,

	_zoom : function(e) {
		var img = this.images[this.index];
		if (img.width <= this.width && img.height <= this.height) {
			return; // Don't zoom if small
		}
		if (!this.zoomer) {
			this.zoomer = hui.build('div',{
				'class' : 'hui_imageviewer_zoomer',
				'style' : 'width:'+this.nodes.viewer.clientWidth+'px;height:'+this.nodes.viewer.clientHeight+'px'
			});
			this.element.insertBefore(this.zoomer,hui.dom.firstChild(this.element));
			hui.listen(this.zoomer,'mousemove',this._onZoomMove.bind(this));
			hui.listen(this.zoomer,'click',this._endZoom.bind(this));
		}
		this._hideController();
		this.pause();
		var size = this._getLargestSize({width:2000,height:2000},img);
		var url = hui.ui.resolveImageUrl(this,img,size.width,size.height);
		var top = Math.max(0, Math.round((this.nodes.viewer.clientHeight - size.height) / 2));
		this.zoomer.innerHTML = '<div style="width:'+size.width+'px;height:'+size.height+'px; margin: 0 auto;"><img src="'+url+'" style="margin-top: '+ top + 'px" /></div>';
		this.zoomer.style.display = 'block';
		this.zoomInfo = {width:size.width,height:size.height};
		this._onZoomMove(e);
		this.zoomed = true;
	},
	_onZoomMove : function(e) {
		if (!this.zoomInfo) {
			return;
		}
		var offset = hui.position.get(this.zoomer);
		e = new hui.Event(e);
		var x = (e.getLeft() - offset.left) / this.zoomer.clientWidth * (this.zoomInfo.width - this.zoomer.clientWidth);
		var y = (e.getTop() - offset.top) / this.zoomer.clientHeight * (this.zoomInfo.height - this.zoomer.clientHeight);

		this.zoomer.scrollLeft = x;
		this.zoomer.scrollTop = y;
	},
	_endZoom : function() {
		if (this.zoomer) {
			this.zoomer.style.display='none';
			this.zoomed = false;			
		}
	}
	
}

if (window.define) {
	define('hui.ui.ImageViewer',hui.ui.ImageViewer);
}

/* EOF */