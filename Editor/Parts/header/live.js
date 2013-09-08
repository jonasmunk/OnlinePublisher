/**
 * @constructor
 */
op.Editor.Header = function(options) {
	this.element = hui.get(options.element);
	this.id = hui.ui.Editor.getPartId(this.element);
	this.header = hui.get.firstByTag(this.element,'*');
	this.field = null;
}

op.Editor.Header.prototype = {
	activate : function(callback) {
		this._load(callback);
	},
	save : function(options) {
		var value = this.field.value;
		if (value!=this.value) {
			this.value = value;
			this.header.innerHTML = value;
			hui.ui.request({
				url : 'parts/update.php',
				parameters : {id:this.id,pageId:op.page.id,text:this.value,type:'header'},
				$text : function(html) {
					this.element.innerHTML = html;
					this.field = null;
					this.header = hui.dom.firstChild(this.element);
					options.callback();
				}.bind(this)
			});
		} else {
			options.callback();
		}
	},
	cancel : function() {
		
	},
	deactivate : function(callback) {
		this.header.style.visibility='';
		if (this.field) {
			this.element.removeChild(this.field);
		}
		callback();
	},
	getValue : function() {
		return this.value;
	},
	_load : function(callback) {
		hui.ui.request({url:'parts/load.php',parameters:{type:'header',id:this.id},onJSON:function(part) {
			this.part = part;
			this._edit();
			callback();
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
	_updateFieldStyle : function() {
		hui.style.set(this.field,{width:this.header.clientWidth+'px',height:this.header.clientHeight+'px'});
		hui.style.copy(this.header,this.field,['font-size','line-height','margin-top','font-weight','font-family','text-align','color','font-style']);
	}
}