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
};

hui.ui.TimeLine.prototype = {
	_attach : function() {
		
	},
	_start : function() {
		this.startTime = new Date().getTime();
		this.background = hui.build('div',{'class':'hui_timeline_bg',parent:this.element});
		this._next();
		window.setTimeout(this.pause.bind(this),20000);
		window.setTimeout(this._addLine.bind(this),2000);
		window.setTimeout(this._addLine.bind(this),4000);
		window.setTimeout(this._addBlock.bind(this),Math.random()*4000);
		window.setTimeout(this._addBlock.bind(this),Math.random()*4000);
		window.setTimeout(this._addBlock.bind(this),Math.random()*4000);
		window.setTimeout(this._addBlock.bind(this),Math.random()*4000);
	},
	_next : function() {
		if (this.paused) return;
		var width = this._getNow();
		this.background.style.width = width+'px';
		this.element.scrollLeft = Math.max(0,width-this.element.clientWidth);
		window.setTimeout(this._next.bind(this),500);
	},
	_setData : function() {
		
	},
	_addLine : function() {
		var line = hui.build('div',{'class':'hui_timeline_line',parent:this.element});
		line.style.left=this._getNow()+'px';
	},
	_addBlock : function() {
		var line = hui.build('div',{'class':'hui_timeline_block',text:'New word',parent:this.element});
		line.style.left=this._getNow()+'px';
		line.style.width=Math.round(30+Math.random()*100)+'px';
	},
	_getNow : function() {
		return Math.round((new Date().getTime() - this.startTime)/10);
	},
	pause : function() {
		this.paused = true;
	}
};