/**
 * @constructor
 */
op.Editor.Header = function(options) {
	this.element = hui.get(options.element);
	this.id = hui.ui.Editor.getPartId(this.element);
	this.header = hui.get.firstByTag(this.element,'*');
	this.style = {};
	this.field = null;
	this.originalStyle = null;
	this.originalTag = null;
}

op.Editor.Header.prototype = {
	type : 'header',
	properties : ['color','fontSize','lineHeight','fontWeight','fontFamily','textAlign'],
	
	activate : function(callback) {
		this._load(callback);
		hui.ui.listen(this);
	},
	save : function(options) {
		this.value = this.field.value;
		var parameters = {
			text : this.value, 
			level : this.part.level
		}
		for (var i = 0; i < this.properties.length; i++) {
			var p = this.properties[i];
			parameters[p] = this.part[p];
		}
		op.DocumentEditor.savePart({
			part : this,
			parameters : parameters,
			$success : function(html) {
				this.element.innerHTML = html;
				this.field = null;
				this.header = hui.dom.firstChild(this.element);
			}.bind(this),
			callback : options.callback
		});
	},
	cancel : function() {
		this.header = hui.dom.changeTag(this.header,'h'+this.originalLevel);
		this.header.setAttribute('style',this.originalStyle);
		this._changeSectionClass(this.originalLevel);
	},
	deactivate : function(callback) {
		this.header.style.visibility='';
		if (this.field) {
			this.element.removeChild(this.field);
		}
		hui.ui.unListen(this);
		callback();
	},
	getValue : function() {
		return this.value;
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
		this.field = hui.build('textarea',{style:'resize: none; background: none; border: none; position: absolute; padding: 0px; display: block; outline: none;'});
		this.field.value = this.part.text;
		this.originalStyle = this.header.getAttribute('style');
		this.originalLevel = this.part.level;
		this.header.style.visibility='hidden';
		this._updateFieldStyle();
		this.element.insertBefore(this.field,this.header);
		this.field.focus();
		this.field.select();
		hui.ui.listen(this.field,'keydown',function(e) {
			if (e.keyCode==Event.KEY_RETURN) {
				this.save();
			}
		}.bind(this));
	},
	_updateFieldStyle : function() {
		hui.style.set(this.field,{width:this.header.clientWidth+'px',height:this.header.clientHeight+'px'});
		hui.style.copy(this.header,this.field,['font-size','line-height','margin-top','font-weight','font-family','text-align','color','font-style']);
	},
	$partWindowLoaded : function() {
		hui.ui.get('textFormula').setValues(this.part);
	},
	$valuesChanged$textFormula : function(values) {
		if (!this.field) {
			hui.log('No field',this);
			return;
		}
		if (this.part.level!=values.level) {
			this.header = hui.dom.changeTag(this.header,'h'+values.level);
			this._changeSectionClass(values.level);
			this.part.level = values.level;
		}
		for (var i = 0; i < this.properties.length; i++) {
			var p = this.properties[i];
			this.part[p] = values[p];
			this.header.style[p] = values[p];
		}
		
		this._updateFieldStyle();
	},
	_changeSectionClass : function(level) {
		for (var i = 1; i <= 6; i++) {
			hui.cls.remove(this.element,'part_section_header_'+i);
		}
		hui.cls.add(this.element,'part_section_header_'+level);
	}
}