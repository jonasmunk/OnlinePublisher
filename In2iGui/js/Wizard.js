/**
 * @constructor
 * A wizard with a number of steps
 */
In2iGui.Wizard = function(o) {
	/** @private */
	this.options = o || {};
	/** @private */
	this.element = n2i.get(o.element);
	/** @private */
	this.name = o.name;
	/** @private */
	this.container = n2i.firstByClass(this.element,'in2igui_wizard_steps');
	/** @private */
	this.steps = n2i.byClass(this.element,'in2igui_wizard_step');
	/** @private */
	this.anchors = n2i.byClass(this.element,'in2igui_wizard_selection');
	/** @private */
	this.selected = 0;
	In2iGui.extend(this);
	this.addBehavior();
}
	
In2iGui.Wizard.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		n2i.each(this.anchors,function(node,i) {
			n2i.listen(node,'mousedown',function(e) {
				n2i.stop(e);
				self.goToStep(i)
			});
			n2i.listen(node,'click',function(e) {
				n2i.stop(e);
			});
		});
	},
	/** Goes to the step with the index */
	goToStep : function(index) {
		var c = this.container;
		c.style.height=c.clientHeight+'px';
		n2i.removeClass(this.anchors[this.selected],'in2igui_selected');
		this.steps[this.selected].style.display='none';
		n2i.addClass(this.anchors[index],'in2igui_selected');
		this.steps[index].style.display='block';
		this.selected=index;
		n2i.ani(c,'height',this.steps[index].clientHeight+'px',500,{ease:n2i.ease.slowFastSlow,onComplete:function() {
			c.style.height='';
		}});
		In2iGui.callVisible(this);
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