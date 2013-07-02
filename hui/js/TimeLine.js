/**
 * A timeline showing events over time
 * @constructor
 * @param {Object} options { element «Node | id», name: «String» }
 */
hui.ui.TimeLine = function(options) {
	this.name = options.name;
	this.options = options || {};
	this.element = hui.get(options.element);
	hui.ui.extend(this);
	hui.onReady(this._start.bind(this));
	
}

hui.ui.TimeLine.prototype = {
	_attach : function() {
		
	},
	_start : function() {
		this.startTime = new Date().getTime();
		this.background = hui.build('div',{'class':'hui_timeline_bg',parent:this.element});
		this._next();
	},
	_next : function() {
		var now = new Date().getTime();
		var diff = now - this.startTime;
		this.background.style.width = (diff/10)+'px';
		window.setTimeout(this._next.bind(this),500);
	},
	_setData : function() {
		
	}
}

/* EOF */