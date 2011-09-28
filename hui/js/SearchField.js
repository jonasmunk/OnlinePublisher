/** @constructor */
hui.ui.SearchField = function(options) {
	this.options = hui.override({expandedWidth:null},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	this.field = hui.firstByTag(this.element,'input');
	this.value = this.field.value;
	this.adaptive = hui.hasClass(this.element,'hui_searchfield_adaptive');
	hui.ui.onReady(function() {
		this.initialWidth = parseInt(hui.getStyle(this.element,'width'))
	}.bind(this));
	hui.ui.extend(this);
	this.addBehavior();
	this.updateClass()
}

hui.ui.SearchField.create = function(options) {
	options = options || {};
	
	options.element = hui.build('span',{
		'class' : options.adaptive ? 'hui_searchfield hui_searchfield_adaptive' : 'hui_searchfield',
		html : '<em class="hui_searchfield_placeholder"></em><a href="javascript:void(0);" class="hui_searchfield_reset"></a><span><span><input type="text"/></span></span>'
	});
	return new hui.ui.SearchField(options);
}

hui.ui.SearchField.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		hui.listen(this.field,'keyup',this.onKeyUp.bind(this));
		var reset = hui.firstByTag(this.element,'a');
		reset.tabIndex=-1;
		if (!hui.browser.ipad) {
			var focus = function() {self.field.focus();self.field.select()};
			hui.listen(this.element,'mousedown',focus);
			hui.listen(this.element,'mouseup',focus);
			hui.listen(hui.firstByTag(this.element,'em'),'mousedown',focus);
		} else {
			var focus = function() {self.field.focus();};
			hui.listen(hui.firstByTag(this.element,'em'),'click',focus);
		}
		hui.listen(reset,'mousedown',function(e) {
			hui.stop(e);
			self.reset();
			focus()
		});
		hui.listen(this.field,'focus',function() {
			self.focused=true;
			self.updateClass();
		});
		hui.listen(this.field,'blur',function() {
			self.focused=false;
			self.updateClass();
		});
		if (this.options.expandedWidth>0) {
			this.field.onfocus = function() {
				hui.animate(self.element,'width',self.options.expandedWidth+'px',500,{ease:hui.ease.slowFastSlow});
			}
			this.field.onblur = function() {
				hui.animate(self.element,'width',self.initialWidth+'px',500,{ease:hui.ease.slowFastSlow,delay:100});
			}
		}
	},
	onKeyUp : function(e) {
		this.fieldChanged();
		if (e.keyCode===hui.KEY_RETURN) {
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
		var className = 'hui_searchfield';
		if (this.adaptive) {
			className+=' hui_searchfield_adaptive';
		}
		if (this.focused && this.value!='') {
			className+=' hui_searchfield_focus_dirty';
		} else if (this.focused) {
			className+=' hui_searchfield_focus';
		} else if (this.value!='') {
			className+=' hui_searchfield_dirty';
		}
		this.element.className=className;
	},
	/** @private */
	fieldChanged : function() {
		if (this.field.value!=this.value) {
			this.value=this.field.value;
			this.updateClass();
			this.fire('valueChanged',this.value);
			hui.ui.firePropertyChange(this,'value',this.value);
		}
	}
}

/* EOF */