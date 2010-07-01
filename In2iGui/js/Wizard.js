/**
 * @constructor
 * A wizard with a number of steps
 */
In2iGui.Wizard = function(o) {
	/** @private */
	this.options = o || {};
	/** @private */
	this.element = $(o.element);
	/** @private */
	this.name = o.name;
	/** @private */
	this.container = this.element.select('.in2igui_wizard_steps')[0];
	/** @private */
	this.steps = this.element.select('.in2igui_wizard_step');
	/** @private */
	this.anchors = this.element.select('ul.in2igui_wizard a');
	/** @private */
	this.selected = 0;
	In2iGui.extend(this);
	this.addBehavior();
}
	
In2iGui.Wizard.prototype = {
	/** @private */
	addBehavior : function() {
		this.anchors.each(function(node,i) {
			node.observe('mousedown',function(e) {e.stop();this.goToStep(i)}.bind(this));
			node.observe('click',function(e) {e.stop();});
		}.bind(this));
	},
	/** Goes to the step with the index */
	goToStep : function(index) {
		var c = this.container;
		c.setStyle({height:c.getHeight()+'px'})
		this.anchors[this.selected].removeClassName('in2igui_selected');
		this.steps[this.selected].hide();
		this.anchors[index].addClassName('in2igui_selected');
		this.steps[index].show();
		this.selected=index;
		n2i.ani(c,'height',this.steps[index].getHeight()+'px',500,{ease:n2i.ease.slowFastSlow,onComplete:function() {
			c.setStyle({height:''});
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