/**
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Bar = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	hui.ui.extend(this);
};

hui.ui.Bar.create = function(options) {
	options = options || {};
	var cls = 'hui_bar';
	if (options.variant) {
		cls+=' hui_bar_'+options.variant;
	}
	if (options.absolute) {
		cls+=' hui_bar_absolute';
	}
	options.element = hui.build('div',{
		'class' : cls
	});
	hui.build('div',{'class':'hui_bar_body',parent:options.element});
	return new hui.ui.Bar(options);
}

hui.ui.Bar.prototype = {
	addToDocument : function() {
		document.body.appendChild(this.element);
	},
	add : function(widget) {
		var body = hui.firstByClass(this.element,'hui_bar_body');
		body.appendChild(widget.getElement());
	},
	placeAbove : function(widget) {
		hui.place({
			source:{element:this.element,vertical:1,horizontal:0},
			target:{element:widget.getElement(),vertical:0,horizontal:0}
		});
		this.element.style.zIndex = hui.ui.nextTopIndex();
	},
	show : function() {
		if (this.options.absolute) {
			this.element.style.visibility='visible';
		} else {
			this.element.style.display='';
		}
	},
	hide : function() {
		if (this.options.absolute) {
			this.element.style.visibility='hidden';
		} else {
			this.element.style.display='none';
		}
	}
}

/**
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Bar.Button = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	hui.listen(this.element,'click',this._click.bind(this));
	hui.listen(this.element,'mousedown',this._mousedown.bind(this));
	hui.listen(this.element,'mouseup',function(e) {hui.stop(e)});
	hui.ui.extend(this);
};

hui.ui.Bar.Button.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('a',{'class':'hui_bar_button'});
	if (options.icon) {
		e.appendChild(hui.ui.createIcon(options.icon,16));
	}
	return new hui.ui.Bar.Button(options);
}

hui.ui.Bar.Button.prototype = {
	_mousedown : function(e) {
		this.fire('mousedown');
		if (this.options.stopEvents) {
			hui.stop(e);
		}
	},
	_click : function(e) {
		this.fire('click');
		if (this.options.stopEvents) {
			hui.stop(e);
		}
	},
	setSelected : function(highlighted) {
		hui.setClass(this.element,'hui_bar_button_selected',highlighted);
	}
}

/**
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Bar.Text = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	hui.ui.extend(this);
};

hui.ui.Bar.Text.prototype = {
	setText : function(str) {
		hui.dom.setText(this.element,str);
	}
}