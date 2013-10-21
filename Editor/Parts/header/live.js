/**
 * @constructor
 */
op.Editor.Header = function(options) {
	this.element = hui.get(options.element);
	this.id = hui.ui.Editor.getPartId(this.element);
	this.header = hui.get.firstByTag(this.element,'*');
	this.style = {};
	this.field = null;
}

op.Editor.Header.prototype = {
	type : 'header',
	
	activate : function(callback) {
		this._load(callback);
		hui.ui.listen(this);
	},
	save : function(options) {
		this.value = this.field.value;
		this.header.innerHTML = this.value;
		op.DocumentEditor.savePart({
			part : this,
			parameters : {
				text : this.value, 
				level : this.part.level, 
				color : this.part.color, 
				fontSize : this.part.fontSize, 
				lineHeight : this.part.lineHeight
			},
			$success : function(html) {
				this.element.innerHTML = html;
				this.field = null;
				this.header = hui.dom.firstChild(this.element);
			}.bind(this),
			callback : options.callback
		});
	},
	cancel : function() {
		
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
		this.field = hui.build('textarea',{'class':'hui_editor_header',style:'resize: none;'});
		this.field.value = this.part.text;
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
		this.part.level = values.level;
		this.part.color = values.color;
		this.part.fontSize = values.fontSize;
		this.part.lineHeight = values.lineHeight;
		this.field.style.color = values.color;
		this.field.style.fontSize = values.fontSize;
		this.field.style.textAlign = values.textAlign;
		this.field.style.fontWeight = values.fontWeight;
		this.field.style.lineHeight = values.lineHeight;
	}
}