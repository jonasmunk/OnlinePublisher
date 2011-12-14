/**
 * @constructor
 */
op.Editor.Header = function(element,row,column,position) {
	this.element = hui.get(element);
	this.row = row;
	this.column = column;
	this.position = position;
	this.id = hui.ui.Editor.getPartId(this.element);
	this.header = hui.get.firstByTag(this.element,'*');
	this.field = null;
}

op.Editor.Header.prototype = {
	activate : function() {
		this._load();
	},
	_load : function() {
		hui.ui.request({url:'parts/load.php',parameters:{type:'header',id:this.id},onJSON:function(part) {
			this.part = part;
			this._edit();
		}.bind(this)});
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
	save : function() {
		var value = this.field.value;
		this.deactivate();
		if (value!=this.value) {
			this.value = value;
			this.header.innerHTML = value;
			hui.ui.Editor.get().partChanged(this);
			hui.ui.request({url:'parts/update.php',parameters:{id:this.id,pageId:op.page.id,text:this.value,type:'header'},onText:function(html) {
				this.element.innerHTML = html;
				this.header = hui.dom.firstChild(this.element);
			}.bind(this)});
		}
	},
	cancel : function() {
		this.deactivate();
	},
	deactivate : function() {
		this.header.style.visibility='';
		this.element.removeChild(this.field);
		hui.ui.Editor.get().partDidDeacivate(this);
	},
	_updateFieldStyle : function() {
		hui.style.set(this.field,{width:this.header.clientWidth+'px',height:this.header.clientHeight+'px'});
		hui.style.copy(this.header,this.field,['font-size','line-height','margin-top','font-weight','font-family','text-align','color','font-style']);
	},
	getValue : function() {
		return this.value;
	}
}