/**
 * @constructor
 */
In2iGui.ColorPicker = function(options) {
	this.options = options || {};
	this.name = options.name;
	this.element = n2i.get(options.element);
	this.color = null;
	this.buttons = [];
	this.preview = n2i.firstByClass(this.element,'in2igui_colorpicker_preview');
	this.pages = n2i.byClass(this.element,'in2igui_colorpicker_page');
	this.input = n2i.firstByTag(this.element,'input');
	this.wheel1 = this.pages[0];
	this.wheel2 = this.pages[1];
	this.wheel3 = this.pages[2];
	this.swatches = this.pages[3];
	In2iGui.extend(this);
	this.addBehavior();
	this.buildData();
}

In2iGui.ColorPicker.create = function(options) {
	var swatches = '',
		c, hex, j;
	for (var i=0; i < 360; i+=30) {
		for (j=0.05; j <= 1; j+=.15) {
			c = n2i.Color.hsv2rgb(i,j,1);
			hex = n2i.Color.rgb2hex(c);
			swatches+='<a style="background: rgb('+c[0]+','+c[1]+','+c[2]+')" rel="'+hex+'"></a>';
		}
		for (j=1; j >= .20; j-=.15) {
			c = n2i.Color.hsv2rgb(i,1,j);
			hex = n2i.Color.rgb2hex(c);
			swatches+='<a style="background: rgb('+c[0]+','+c[1]+','+c[2]+')" rel="'+hex+'"></a>';
		}
	}
	for (j=255; j >=0; j-=255/12) {
		hex = n2i.Color.rgb2hex([j,j,j]);
		swatches+='<a style="background: rgb('+Math.round(j)+','+Math.round(j)+','+Math.round(j)+')" rel="'+hex+'"></a>';
	}
	options = options || {};
	options.element = n2i.build('div',{
		'class':'in2igui_colorpicker',
		html : 
			'<div class="in2igui_bar in2igui_bar_window">'+
				'<div class="in2igui_bar_body">'+
					'<a class="in2igui_bar_button in2igui_bar_button_selected" href="javascript:void(0)" rel="0">'+
						'<span class="in2igui_icon_1" style="background: url('+In2iGui.getIconUrl('colorpicker/wheel_pastels',1)+')"></span>'+
					'</a>'+
					'<a class="in2igui_bar_button" href="javascript:void(0)" rel="1">'+
						'<span class="in2igui_icon_1" style="background: url('+In2iGui.getIconUrl('colorpicker/wheel_brightness',1)+')"></span>'+
					'</a>'+
					'<a class="in2igui_bar_button" href="javascript:void(0)" rel="2">'+
						'<span class="in2igui_icon_1" style="background: url('+In2iGui.getIconUrl('colorpicker/wheel_saturated',1)+')"></span>'+
					'</a>'+
					'<a class="in2igui_bar_button" href="javascript:void(0)" rel="3">'+
						'<span class="in2igui_icon_1" style="background: url('+In2iGui.getIconUrl('colorpicker/swatches',1)+')"></span>'+
					'</a>'+
					'<input class="in2igui_colorpicker"/>'+
				'</div>'+
			'</div>'+
			'<div class="in2igui_colorpicker_pages">'+
				'<div class="in2igui_colorpicker_page in2igui_colorpicker_wheel1"></div>'+
				'<div class="in2igui_colorpicker_page in2igui_colorpicker_wheel2"></div>'+
				'<div class="in2igui_colorpicker_page in2igui_colorpicker_wheel3"></div>'+
				'<div class="in2igui_colorpicker_page in2igui_colorpicker_swatches">'+swatches+'</div>'+
			'</div>'+
			'<div class="in2igui_colorpicker_preview"></div>'
	});
	return new In2iGui.ColorPicker(options);
}

In2iGui.ColorPicker.prototype = {
	/** @private */
	addBehavior : function() {
		var bs = n2i.byClass(this.element,'in2igui_bar_button');
		for (var i=0; i < bs.length; i++) {
			var button = new In2iGui.Bar.Button({element:bs[i]});
			button.listen(this);
			this.buttons.push(button);
		};
		
		n2i.listen(this.element,'click',this._click.bind(this));
		n2i.listen(this.wheel1,'mousemove',this._hoverWheel1.bind(this));
		n2i.listen(this.wheel1,'click',this._pickColor.bind(this));
		n2i.listen(this.wheel2,'mousemove',this._hoverWheel2.bind(this));
		n2i.listen(this.wheel2,'click',this._pickColor.bind(this));
		n2i.listen(this.wheel3,'mousemove',this._hoverWheel3.bind(this));
		n2i.listen(this.wheel3,'click',this._pickColor.bind(this));
		n2i.listen(this.element,'mousedown',function(e) {
			n2i.stop(e);
		})
		n2i.listen(this.swatches,'mousemove',function(e) {
			e = n2i.event(e);
			this._hoverColor(e.element.getAttribute('rel'));
		}.bind(this));
		n2i.listen(this.swatches,'click',this._pickColor.bind(this));
	},
	$click : function(button) {
		var page = parseInt(button.element.getAttribute('rel')),
			i;
		for (i = this.pages.length - 1; i >= 0; i--){
			this.pages[i].style.display = i==page ? 'block' : 'none';
		};
		for (i=0; i < this.buttons.length; i++) {
			this.buttons[i].setSelected(this.buttons[i]==button);
		};
	},
	_click : function(e) {
		e = n2i.event(e);
		e.stop();
	//	return;
		var input = e.findByTag('input');
		if (input) {input.focus()}
	},
	/** @private */
	_pickColor : function(e) {
		n2i.stop(e);
		this.fire('colorWasSelected',this.color);
	},
	_hoverColor : function(color) {
		this.preview.style.background = color;
		this.color = color;
		this.fire('colorWasHovered',this.color);
		this.input.value = color;
	},
	/** @private */
	buildData : function() {
		var addary = new Array();           //red
		addary[0] = new Array(0,1,0);   //red green
		addary[1] = new Array(-1,0,0);  //green
		addary[2] = new Array(0,0,1);   //green blue
		addary[3] = new Array(0,-1,0);  //blue
		addary[4] = new Array(1,0,0);   //red blue
		addary[5] = new Array(0,0,-1);  //red
		addary[6] = new Array(255,1,1);
		var clrary = new Array(360);
		for(var i = 0; i < 6; i++) {
			for(var j = 0; j < 60; j++) {
				clrary[60 * i + j] = new Array(3);
				for(var k = 0; k < 3; k++) {
					clrary[60 * i + j][k] = addary[6][k];
					addary[6][k] += (addary[i][k] * 4);
				}
			}
		}
		this.colorArray = clrary;
	},
	/** @private */
	_hoverWheel1 : function(e) {
		e = n2i.event(e);
		var pos = n2i.getPosition(this.wheel1);
		var x = 4 * (e.getLeft() - pos.left);
		var y = 4 * (e.getTop() - pos.top);

		var sx = x - 512;
		var sy = y - 512;
		var qx = (sx < 0)?0:1;
		var qy = (sy < 0)?0:1;
		var q = 2 * qy + qx;
		var quad = new Array(-180,360,180,0);
		var xa = Math.abs(sx);
		var ya = Math.abs(sy);
		var d = ya * 45 / xa;
		if(ya > xa) {
			 d = 90 - (xa * 45 / ya);
		}
		var deg = Math.floor(Math.abs(quad[q] - d));
		sx = Math.abs(x - 512);
		sy = Math.abs(y - 512);
		var r = Math.sqrt((sx * sx) + (sy * sy));
		if(x == 512 & y == 512) {
			var c = "000000";
		} else {
			var n = 0;
			for(var i = 0; i < 3; i++) {
				var r2 = this.colorArray[deg][i] * r / 256;
				if(r > 256) r2 += Math.floor(r - 256);
				if(r2 > 255) r2 = 255;
				n = 256 * n + Math.floor(r2);
			}
			c = n.toString(16);
		}
		while(c.length < 6) c = "0" + c;
		this._hoverColor('#'+c);
	},
	_hoverWheel2 : function(e) {
		var rgb,sat,val;
		e = n2i.event(e);
		var pos = n2i.getPosition(this.wheel2);
		var x = (e.getLeft() - pos.left);
		var y = (e.getTop() - pos.top);

		if (y > 256) {return}

	    var cartx = x - 128;
	    var carty = 128 - y;
	    var cartx2 = cartx * cartx;
	    var carty2 = carty * carty;
	    var rraw = Math.sqrt(cartx2 + carty2);       //raw radius
	    var rnorm = rraw/128;                        //normalized radius
	    if (rraw == 0) {
			sat = 0;
			val = 0;
			rgb = new Array(0,0,0);
		} else {
			var arad = Math.acos(cartx/rraw);            //angle in radians 
			var aradc = (carty>=0)?arad:2*Math.PI - arad;  //correct below axis
			var adeg = 360 * aradc/(2*Math.PI);  //convert to degrees
			if (rnorm > 1) {    // outside circle
				rgb = new Array(255,255,255);
				sat = 1;
				val = 1;            
			} else if (rnorm >= .5) {
				sat = 1 - ((rnorm - .5) *2);
				val = 1;
				rgb = n2i.Color.hsv2rgb(adeg,sat,val);
			} else {
				sat = 1;
				val = rnorm * 2;
				rgb = n2i.Color.hsv2rgb(adeg,sat,val);
			}
		}
		this._hoverColor(n2i.Color.rgb2hex(rgb));
	},
	_hoverWheel3 : function(e) {
		var rgb,sat,val;
		e = n2i.event(e);
		var pos = n2i.getPosition(this.wheel3);
		var x = (e.getLeft() - pos.left);
		var y = (e.getTop() - pos.top);

		if (y > 256) {return}

	    var cartx = x - 128;
	    var carty = 128 - y;
	    var cartx2 = cartx * cartx;
	    var carty2 = carty * carty;
	    var rraw = Math.sqrt(cartx2 + carty2);       //raw radius
	    var rnorm = rraw/128;                        //normalized radius
	    if (rraw == 0) {
			sat = 0;
			val = 0;
			rgb = new Array(0,0,0);
		} else {
			var arad = Math.acos(cartx/rraw);            //angle in radians 
			var aradc = (carty>=0) ? arad : 2*Math.PI - arad;  //correct below axis
			var adeg = 360 * aradc/(2*Math.PI);  //convert to degrees
			if (rnorm > 1) {    // outside circle
				rgb = new Array(255,255,255);
				sat = 1;
				val = 1;            
			} else {
				sat = rnorm;// - ((rnorm - .5) *2);
				val = 1;
				rgb = n2i.Color.hsv2rgb(adeg,sat,val);
			}
		}
		this._hoverColor(n2i.Color.rgb2hex(rgb));
	}
}

/* EOF */