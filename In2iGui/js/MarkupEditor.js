/**
 * @constructor
 */
In2iGui.MarkupEditor = function(options) {
	this.name = options.name;
	this.options = n2i.override({debug:false,value:'',autoHideToolbar:true,style:'font-family: sans-serif; font-size: 11px;'},options);
	if (options.replace) {
		options.replace = n2i.get(options.replace);
		options.element = n2i.build('div',{className:'in2igui_markupeditor'});
		options.replace.parentNode.insertBefore(options.element,options.replace);
		options.replace.style.display='none';
		options.value = options.replace.innerHTML;
	}
	this.element = n2i.get(options.element);
	this.impl = In2iGui.MarkupEditor.webkit;
	this.impl.initialize({element:this.element,controller:this});
	if (options.value) {
		this.impl.setHTML(options.value);
	}
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.MarkupEditor.create = function(options) {
	options = options || {};
	options.element = n2i.build('div',{className:'in2igui_markupeditor'});
	return new In2iGui.MarkupEditor(options);
}

In2iGui.MarkupEditor.prototype = {
	addBehavior : function() {
		
	},
	getValue : function() {
		return this.impl.getHTML();
	},
	focus : function() {
		this.impl.focus();
	},
	focused : function() {
		this.showBar();
	},
	blurred : function() {
		this.bar.hide();
		this.fire('blur');
	},
	showBar : function() {
		if (!this.bar) {
			var things = [
				{key:'bold',icon:'edit/text_bold'},
				{key:'italic',icon:'edit/text_italic'},
				{key:'color',icon:'common/color'},
				{key:'image',icon:'common/image'},
				{key:'align',value:'left',icon:'edit/text_align_left'},
				{key:'align',value:'center',icon:'edit/text_align_center'},
				{key:'align',value:'right',icon:'edit/text_align_right'},
				{key:'align',value:'justify',icon:'edit/text_align_justify'}
				
				
				/*,
				{key:'insert-table',icon:'edit/text_italic'}*/
			]
			
			this.bar = In2iGui.Bar.create({absolute:true,variant:'mini',small:true});
			n2i.each(things,function(info) {
				var button = new In2iGui.Bar.Button.create({icon:info.icon,stopEvents:true});
				button.listen({
					$click:function() {this._buttonClicked(info)}.bind(this)
				});
				this.bar.add(button);
			}.bind(this));
			this.bar.addToDocument();
		}
		this.bar.placeAbove(this);
		this.bar.show();
	},
	_buttonClicked : function(info) {
		if (info.key=='color') {
			this._showColors();
		} else if (info.key=='align') {
			this.impl.align(info.value);
		} else {
			this.impl.format(info);
		}
	},
	_showColors : function() {
		if (!this.colorPicker) {
			this.colorPicker = In2iGui.Window.create();
			var picker = In2iGui.ColorPicker.create();
			picker.listen(this);
			this.colorPicker.add(picker);
		}
		this.colorPicker.show();
	},
	$colorWasSelected : function(color) {
		this.impl.colorize(color);
	}
}

In2iGui.MarkupEditor.webkit = {
	initialize : function(options) {
		this.element = options.element;
		this.element.style.overflow='auto';
		this.element.contentEditable = true;
		var ctrl = this.controller = options.controller;
		n2i.listen(this.element,'focus',function() {
			ctrl.focused();
		});
		n2i.listen(this.element,'blur',function() {
			ctrl.blurred();
		});
	},
	focus : function() {
		this.element.focus();
	},
	format : function(info) {
		if (info.key=='strong' || info.key=='em') {
			this._wrapInTag(info.key);
		} else if (info.key=='insert-table') {
			this._insertHTML('<table><tbody><tr><td>Lorem ipsum dolor</td><td>Lorem ipsum dolor</td></tr></tbody></table>');
		} else {
			document.execCommand(info.key,null,info.value);
		}
	},
	colorize : function(color) {
		n2i.log(color);
		document.execCommand('forecolor',null,color);
	},
	align : function(value) {
		var x = {center:'justifycenter',justify:'justifyfull',left:'justifyleft',right:'justifyright'};
		document.execCommand(x[value],null,null);
	},
	_wrapInTag : function(tag) {
		document.execCommand('inserthtml',null,'<'+tag+'>'+n2i.escape(n2i.getSelectedText())+'</'+tag+'>');
	},
	_insertHTML : function(html) {
		document.execCommand('inserthtml',null,html);
	},
	setHTML : function(html) {
		this.element.innerHTML = html;
	},
	getHTML : function() {
		return this.element.innerHTML;
	}
}

In2iGui.MarkupEditor.iframe = {
	initialize : function(options) {
		this.element = options.element;
		this.iframe = n2i.build('iframe',{style:'display:block; width: 100%; border: 0;'})
		this.element.contentEditable = true;
		var ctrl = this.controller = options.controller;
		n2i.listen(this.element,'focus',function() {
			ctrl.focused();
		});
		n2i.listen(this.element,'blur',function() {
			ctrl.blurred();
		});
	},
	focus : function() {
		this.element.focus();
	},
	format : function(info) {
		if (info.key=='strong' || info.key=='em') {
			this._wrapInTag(info.key);
		} else if (info.key=='insert-table') {
			this._insertHTML('<table><tbody><tr><td>Lorem ipsum dolor</td><td>Lorem ipsum dolor</td></tr></tbody></table>');
		} else {
			document.execCommand(info.key,null,null);
		}
	},
	_wrapInTag : function(tag) {
		document.execCommand('inserthtml',null,'<'+tag+'>'+n2i.escape(n2i.getSelectedText())+'</'+tag+'>');
	},
	_insertHTML : function(html) {
		document.execCommand('inserthtml',null,html);
	},
	setHTML : function(html) {
		this.element.innerHTML = html;
	},
	getHTML : function() {
		return this.element.innerHTML;
	}
}

/* EOF */