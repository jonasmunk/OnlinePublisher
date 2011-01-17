/**
 * @constructor
 */
In2iGui.MarkupEditor = function(options) {
	this.name = options.name;
	this.element = n2i.get(options.element);
	this.options = n2i.override({debug:false,value:'',autoHideToolbar:true,style:'font-family: sans-serif; font-size: 11px;'},options);
	this.impl = In2iGui.MarkupEditor.webkit;
	this.impl.initialize({element:this.element});
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
		this.element.observe('focus',this._focus.bind(this));
	},
	_focus : function() {
		if (!this.ignited) {
			this.element.observe('blur',this.hideBar.bind(this));
			this.ignited = true;
		}
		this.showBar();
	},
	getValue : function() {
		return this.impl.getHTML();
	},
	hideBar : function() {
		this.bar.hide();
	},
	showBar : function() {
		if (!this.bar) {
			var things = [{key:'bold',icon:'edit/text_bold'},{key:'italic',icon:'edit/text_italic'}]
			
			this.bar = In2iGui.Bar.create({absolute:true,small:true});
			n2i.each(things,function(info) {
				var button = new In2iGui.Bar.Button.create({icon:info.icon,stopEvents:true});
				button.listen({
					$click:function() {this.impl.format(info.key)}.bind(this)
				});
				this.bar.add(button);
			}.bind(this));
			this.bar.addToDocument();
		}
		this.bar.placeAbove(this);
		this.bar.show();
	}
}

In2iGui.MarkupEditor.webkit = {
	initialize : function(options) {
		this.element = options.element;
		this.element.contentEditable = true;
	},
	format : function(command) {
		document.execCommand(command,null,null);
	},
	setHTML : function(html) {
		this.element.innerHTML = html;
	},
	getHTML : function() {
		return this.element.innerHTML;
	}
}