/**
 * @constructor
 */
op.Editor.Listing = function(options) {
	this.element = hui.get(options.element);
	this.id = hui.ui.Editor.getPartId(this.element);
	this.header = hui.get.firstByTag(this.element,'*');
	this.section = {};
	this.field = null;
}

op.Editor.Listing.prototype = {
	
	type : 'listing',
	
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
		this.field = hui.build('textarea',{style:'resize: none; overflow: hidden; background: none; border: none; padding: 0; display: block; outline: none;'});
		this.field.value = this.part.text;
		this._updateFieldStyle();
		this.header.style.display='none';
		this.element.insertBefore(this.field,this.header);
		new op.FieldResizer({field:this.field}).resize(true);
		this.field.focus();
		this.field.select();
	},
	save : function(options) {
		var value = this.field.value;
		if (value!=this.value) {
			this.value = value;
			op.DocumentEditor.savePart({
				part : this,
				parameters : {text : this.value},
				$success : function(html) {
					this.element.innerHTML = html;
					this.field = null;
					this.header = hui.dom.firstChild(this.element);
				}.bind(this),
				callback : options.callback
			});
		} else {
			options.callback();
		}
	},
	cancel : function() {
		
	},
	deactivate : function(callback) {
		this.header.style.display='';
		if (this.field) {
			hui.dom.remove(this.field);
		}
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