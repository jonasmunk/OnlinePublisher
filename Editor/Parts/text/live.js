/**
 * @constructor
 */
op.Editor.Text = function(options) {
	this.element = hui.get(options.element);
	this.id = hui.ui.Editor.getPartId(this.element);
	this.header = hui.get.firstByTag(this.element,'*');
	this.field = null;
}

op.Editor.Text.prototype = {
	activate : function(callback) {
		this._load(callback);
	},
	_load : function(callback) {
		hui.ui.request({url:'parts/load.php',parameters:{type:'text',id:this.id},onJSON:function(part) {
			this.part = part;
			this._edit();
			callback();
		}.bind(this)});
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
	save : function() {
		var value = this.field.value;
		this.deactivate();
		if (value!=this.value) {
			this.value = value;
			this.header.innerHTML = value;
			hui.ui.Editor.get().partChanged(this);
			hui.ui.request({
				url : 'parts/update.php',
				parameters : {id:this.id,pageId:op.page.id,text:this.value,type:'text'},
				onText : function(html) {
					this.element.innerHTML=html;
					this.header = hui.get.firstByTag(this.element,'*');
				}.bind(this)
			});
		}
	},
	cancel : function() {
		this.deactivate();
	},
	deactivate : function() {
		this.header.style.display='';
		this.element.removeChild(this.field);
		hui.ui.Editor.get().partDidDeacivate(this);
	},
	getValue : function() {
		return this.value;
	},
	
	
	_updateFieldStyle : function() {
		hui.style.set(this.field,{width:this.header.clientWidth+'px',height:this.header.clientHeight+'px'});
		hui.style.copy(this.header,this.field,['font-size','line-height','margin-top','font-weight','font-family','text-align','color','font-style']);
	}
}