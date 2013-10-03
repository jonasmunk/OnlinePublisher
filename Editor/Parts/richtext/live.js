/**
 * @constructor
 */
op.Editor.Richtext = function(options) {
	this.element = hui.get(options.element);
	this.id = hui.ui.Editor.getPartId(this.element);
	this.container = hui.dom.firstChild(this.element);
	this.section = {};
	this.field = null;
}

op.Editor.Richtext.prototype = {
	
	type : 'richtext',
	
	activate : function(callback) {
		this._load(callback);
	},
	_load : function(callback) {
		op.DocumentEditor.loadPart({
			part : this,
			$success : function(part) {
				this.part = part;
				this._edit();
			}.bind(this),
			callback : callback
		})
	},
	_edit : function() {
		this.original = this.container.innerHTML;
		this.editor = new hui.ui.MarkupEditor({replace:this.container});
		this.editor.listen({
			$blur : function() {
				
			}
		});
		this.editor.focus();
		return;		
	},
	save : function(options) {
		var value = this.editor.getValue()
		op.DocumentEditor.savePart({
			part : this,
			parameters : {html : value},
			$success : function(html) {
				this.element.innerHTML = html;
				this.field = null;
				this.container = hui.dom.firstChild(this.element);
				this.editor.destroy();
			}.bind(this),
			callback : options.callback
		});
	},
	cancel : function() {
		this.editor.destroy();
	},
	deactivate : function(callback) {
		callback();
	},
	getValue : function() {
		return this.value;
	},
	
	
	_updateFieldStyle : function() {
		hui.style.set(this.field,{width:this.header.clientWidth+'px',height:this.header.clientHeight+'px'});
		hui.style.copy(this.header,this.field,['font-size','line-height','margin-top','font-weight','font-family','text-align','color','font-style']);
	}
}