/** A progress bar is a widget that shows progress from 0% to 100%
	@constructor
*/
In2iGui.ProgressBar = function(o) {
	this.element = $(o.element);
	this.name = o.name;
	/** @private */
	this.WAITING = o.small ? 'in2igui_progressbar_small_waiting' : 'in2igui_progressbar_waiting';
	/** @private */
	this.COMPLETE = o.small ? 'in2igui_progressbar_small_complete' : 'in2igui_progressbar_complete';
	/** @private */
	this.options = o || {};
	/** @private */
	this.indicator = this.element.firstDescendant();
	In2iGui.extend(this);
}

/** Creates a new progress bar:
	@param o {Object} Options : {small:false}
*/
In2iGui.ProgressBar.create = function(o) {
	o = o || {};
	var e = o.element = new Element('div',{'class':'in2igui_progressbar'}).insert(new Element('div'));
	if (o.small) e.addClassName('in2igui_progressbar_small');
	return new In2iGui.ProgressBar(o);
}
	
In2iGui.ProgressBar.prototype = {
	/** Set the progress value
	@param value {Number} A number between 0 and 1
	*/
	setValue : function(value) {
		var el = this.element;
		if (this.waiting) el.removeClassName(this.WAITING);
		el.setClassName(this.COMPLETE,value==1);
		n2i.ani(this.indicator,'width',(value*100)+'%',200);
	},
	/** Mark progress as waiting */
	setWaiting : function() {
		this.waiting = true;
		this.indicator.setStyle({width:0});
		this.element.addClassName(this.WAITING);
	},
	/** Reset the progress bar */
	reset : function() {
		var el = this.element;
		if (this.waiting) el.removeClassName(this.WAITING);
		el.removeClassName(this.COMPLETE);
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