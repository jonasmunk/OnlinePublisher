/** A progress bar is a widget that shows progress from 0% to 100%
	@constructor
*/
In2iGui.ProgressBar = function(o) {
	this.element = n2i.get(o.element);
	this.name = o.name;
	/** @private */
	this.WAITING = o.small ? 'in2igui_progressbar_small_waiting' : 'in2igui_progressbar_waiting';
	/** @private */
	this.COMPLETE = o.small ? 'in2igui_progressbar_small_complete' : 'in2igui_progressbar_complete';
	/** @private */
	this.options = o || {};
	/** @private */
	this.indicator = n2i.firstByTag(this.element,'div');
	In2iGui.extend(this);
}

/** Creates a new progress bar:
	@param o {Object} Options : {small:false}
*/
In2iGui.ProgressBar.create = function(o) {
	o = o || {};
	var e = o.element = n2i.build('div',{'class':o.small ? 'in2igui_progressbar in2igui_progressbar_small' : 'in2igui_progressbar'});
	e.appendChild(document.createElement('div'));
	return new In2iGui.ProgressBar(o);
}
	
In2iGui.ProgressBar.prototype = {
	/** Set the progress value
	@param value {Number} A number between 0 and 1
	*/
	setValue : function(value) {
		var el = this.element;
		if (this.waiting) {
			n2i.removeClass(el,this.WAITING);
		}
		n2i.setClass(el,this.COMPLETE,value==1);
		n2i.ani(this.indicator,'width',(value*100)+'%',200);
	},
	/** Mark progress as waiting */
	setWaiting : function() {
		this.waiting = true;
		this.indicator.style.width=0;
		n2i.addClass(this.element,this.WAITING);
	},
	/** Reset the progress bar */
	reset : function() {
		var el = this.element;
		if (this.waiting) {
			n2i.removeClass(el,this.WAITING);
		}
		n2i.removeClass(el,this.COMPLETE);
		this.indicator.style.width='0%';
	},
	/** Hide the progress bar */
	hide : function() {
		this.element.style.display = 'none';
	},
	/** Show the progress bar */
	show : function() {
		this.element.style.display = 'block';
	}
}

/* EOF */