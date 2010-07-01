/**
 * @constructor
 */
In2iGui.ColorPicker = function(options) {
	this.options = options || {};
	this.name = options.name;
	this.element = $(options.element);
	this.color = null;
	this.wheel1 = this.element.select('div.in2igui_colorpicker_wheel1')[0];
	In2iGui.extend(this);
	this.addBehavior();
	this.buildData();
}

In2iGui.ColorPicker.create = function(options) {
	options = options || {};
	var element = options.element = new Element('div',{'class':'in2igui_colorpicker'});
	element.update(
		'<div class="in2igui_colorpicker_content"><div class="in2igui_colorpicker_inner_content">'+
		'<div class="in2igui_colorpicker_page in2igui_colorpicker_wheel1"></div>'+
		'<div class="in2igui_colorpicker_page in2igui_colorpicker_wheel2"></div>'+
		'</div></div>'
	);
	return new In2iGui.ColorPicker(options);
}

In2iGui.ColorPicker.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		this.wheel1.observe('mousemove',function(e) {
			self.hoverWheel1(e);
		})
		this.wheel1.observe('mousedown',function(e) {
			e.stop();
		})
		this.wheel1.observe('click',function(e) {
			e.stop();
			self.pickColor();
		})
	},
	/** @private */
	pickColor : function() {
		this.fire('colorWasSelected',this.color);
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
		for(i = 0; i < 6; i++) {
			for(j = 0; j < 60; j++) {
				clrary[60 * i + j] = new Array(3);
				for(k = 0; k < 3; k++) {
					clrary[60 * i + j][k] = addary[6][k];
					addary[6][k] += (addary[i][k] * 4);
				}
			}
		}
		this.colorArray = clrary;
	},
	/** @private */
	hoverWheel1 : function(e) {
		var pos = this.wheel1.cumulativeOffset();
		var x = 4 * (e.pointerX() - pos.left);
		var y = 4 * (e.pointerY() - pos.top);

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
			for(i = 0; i < 3; i++) {
				var r2 = this.colorArray[deg][i] * r / 256;
				if(r > 256) r2 += Math.floor(r - 256);
				if(r2 > 255) r2 = 255;
				n = 256 * n + Math.floor(r2);
			}
			c = n.toString(16);
		}
		while(c.length < 6) c = "0" + c;
		this.color = '#'+c;
		this.fire('colorWasHovered',this.color);
	}
}

/* EOF */