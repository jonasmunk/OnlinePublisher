/** @constructor */
In2iGui.SearchField = function(options) {
	this.options = n2i.override({expandedWidth:null},options);
	this.element = n2i.get(options.element);
	this.name = options.name;
	this.field = n2i.firstByTag(this.element,'input');
	this.value = this.field.value;
	this.adaptive = n2i.hasClass(this.element,'in2igui_searchfield_adaptive');
	In2iGui.onDomReady(function() {
		this.initialWidth = parseInt(n2i.getStyle(this.element,'width'))
	}.bind(this));
	In2iGui.extend(this);
	this.addBehavior();
	this.updateClass()
}

In2iGui.SearchField.create = function(options) {
	options = options || {};
	
	options.element = n2i.build('span',{
		'class' : options.adaptive ? 'in2igui_searchfield in2igui_searchfield_adaptive' : 'in2igui_searchfield',
		html : '<em class="in2igui_searchfield_placeholder"></em><a href="javascript:void(0);" class="in2igui_searchfield_reset"></a><span><span><input type="text"/></span></span>'
	});
	return new In2iGui.SearchField(options);
}

In2iGui.SearchField.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		n2i.listen(this.field,'keyup',this.onKeyUp.bind(this));
		var reset = n2i.firstByTag(this.element,'a');
		reset.tabIndex=-1;
		var focus = function() {self.field.focus();self.field.select()};
		n2i.listen(this.element,'mousedown',focus);
		n2i.listen(this.element,'mouseup',focus);
		n2i.listen(reset,'mousedown',function(e) {
			n2i.stop(e);
			self.reset();
			focus()
		});
		n2i.listen(n2i.firstByTag(this.element,'em'),'mousedown',focus);
		n2i.listen(this.field,'focus',function() {
			self.focused=true;
			self.updateClass();
		});
		n2i.listen(this.field,'blur',function() {
			self.focused=false;
			self.updateClass();
		});
		if (this.options.expandedWidth>0) {
			this.field.onfocus = function() {
				n2i.ani(self.element,'width',self.options.expandedWidth+'px',500,{ease:n2i.ease.slowFastSlow});
			}
			this.field.onblur = function() {
				n2i.ani(self.element,'width',self.initialWidth+'px',500,{ease:n2i.ease.slowFastSlow,delay:100});
			}
		}
	},
	onKeyUp : function(e) {
		this.fieldChanged();
		if (e.keyCode===Event.KEY_RETURN) {
			this.fire('submit');
		}
	},
	setValue : function(value) {
		this.field.value=value===undefined || value===null ? '' : value;
		this.fieldChanged();
	},
	getValue : function() {
		return this.field.value;
	},
	isEmpty : function() {
		return this.field.value=='';
	},
	isBlank : function() {
		return this.field.value.strip()=='';
	},
	reset : function() {
		this.field.value='';
		this.fieldChanged();
	},
	/** @private */
	updateClass : function() {
		var className = 'in2igui_searchfield';
		if (this.adaptive) {
			className+=' in2igui_searchfield_adaptive';
		}
		if (this.focused && this.value!='') {
			className+=' in2igui_searchfield_focus_dirty';
		} else if (this.focused) {
			className+=' in2igui_searchfield_focus';
		} else if (this.value!='') {
			className+=' in2igui_searchfield_dirty';
		}
		this.element.className=className;
	},
	/** @private */
	fieldChanged : function() {
		if (this.field.value!=this.value) {
			this.value=this.field.value;
			this.updateClass();
			this.fire('valueChanged',this.value);
			In2iGui.firePropertyChange(this,'value',this.value);
		}
	}
}

/* EOF */