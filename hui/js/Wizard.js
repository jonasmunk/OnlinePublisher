/**
 * A wizard with a number of steps
 * @constructor
 */
hui.ui.Wizard = function(o) {
	/** @private */
	this.options = o || {};
	/** @private */
	this.element = hui.get(o.element);
	/** @private */
	this.name = o.name;
	/** @private */
	this.container = hui.get.firstByClass(this.element,'hui_wizard_steps');
	/** @private */
	this.steps = hui.get.byClass(this.element,'hui_wizard_step');
	/** @private */
	this.anchors = hui.get.byClass(this.element,'hui_wizard_selection');
	/** @private */
	this.selected = 0;
	hui.ui.extend(this);
	this._addBehavior();
}
	
hui.ui.Wizard.prototype = {
	_addBehavior : function() {
		var self = this;
		hui.each(this.anchors,function(node,i) {
			hui.listen(node,'mousedown',function(e) {
				hui.stop(e);
				self.goToStep(i)
			});
			hui.listen(node,'click',function(e) {
				hui.stop(e);
			});
		});
	},
	/** Get the currently selected step (0-based)*/
	getStep : function() {
		return this.selected;
	},
	/** Goes to the step with the index (0-based) */
	goToStep : function(index) {
		var c = this.container;
		c.style.height = c.clientHeight+'px';
		hui.cls.remove(this.anchors[this.selected],'hui_selected');
		this.steps[this.selected].style.display = 'none';
		hui.cls.add(this.anchors[index],'hui_selected');
		this.steps[index].style.display = 'block';
		this.selected = index;
		hui.animate(c,'height',this.steps[index].clientHeight+'px',500,{ease:hui.ease.slowFastSlow,onComplete:function() {
			c.style.height='';
		}});
		hui.ui.callVisible(this);
		this.fire('stepChanged',this.selected);
	},
	isFirst : function() {
		return this.selected==0;
	},
	isLast : function() {
		return this.selected==this.steps.length-1;
	},
	/** Goes to the next step */
	next : function() {
		if (this.selected<this.steps.length-1) {
			this.goToStep(this.selected+1);
		}
	},
	/** Goes to the previous step */
	previous : function() {
		if (this.selected>0) {
			this.goToStep(this.selected-1);
		}
	}
}

/* EOF */