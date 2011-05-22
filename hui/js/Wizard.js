/**
 * @constructor
 * A wizard with a number of steps
 */
hui.ui.Wizard = function(o) {
	/** @private */
	this.options = o || {};
	/** @private */
	this.element = hui.get(o.element);
	/** @private */
	this.name = o.name;
	/** @private */
	this.container = hui.firstByClass(this.element,'in2igui_wizard_steps');
	/** @private */
	this.steps = hui.byClass(this.element,'in2igui_wizard_step');
	/** @private */
	this.anchors = hui.byClass(this.element,'in2igui_wizard_selection');
	/** @private */
	this.selected = 0;
	hui.ui.extend(this);
	this.addBehavior();
}
	
hui.ui.Wizard.prototype = {
	/** @private */
	addBehavior : function() {
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
	/** Goes to the step with the index */
	goToStep : function(index) {
		var c = this.container;
		c.style.height=c.clientHeight+'px';
		hui.removeClass(this.anchors[this.selected],'in2igui_selected');
		this.steps[this.selected].style.display='none';
		hui.addClass(this.anchors[index],'in2igui_selected');
		this.steps[index].style.display='block';
		this.selected=index;
		hui.ani(c,'height',this.steps[index].clientHeight+'px',500,{ease:hui.ease.slowFastSlow,onComplete:function() {
			c.style.height='';
		}});
		hui.ui.callVisible(this);
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