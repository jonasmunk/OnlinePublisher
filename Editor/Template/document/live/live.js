op.DocumentEditor = {
	
	part : null,

	$partWasMoved$huiEditor : function(info) {
		var data = hui.string.fromJSON(info.dragged.getAttribute('data'));
		var p = {
			sectionId : data.id,
			rowIndex : info.rowIndex,
			columnIndex : info.columnIndex,
			sectionIndex : info.partIndex
		}
		hui.ui.request({
			url : op.context+'Editor/Template/document/live/MoveSection.php',
			parameters : p,
			message : {start:{en:'Moving...',da:'Flytter...'},delay:300},
			$success : function() {
				info.onSuccess();
				op.Editor.signalChange();
			}.bind(this)
		})
	},
	$editPart$huiEditor : function(part) {
		this._getPartEditor();
		var e = part.element;
		this.part = part;
		this._partEditorForm.setValues({
			top: hui.style.get(e,'paddingTop'),
			bottom: hui.style.get(e,'paddingBottom'),
			left: hui.style.get(e,'paddingLeft'),
			right: hui.style.get(e,'paddingRight')
		});
		this._partWindow.show();
	},
	$deactivatePart$huiEditor : function() {
		this._partWindow.hide();
	},
	_updatePartProperties : function(values) {
		hui.style.set(this.part.element,{
			paddingTop : values.top,
			paddingBottom : values.bottom,
			paddingLeft : values.left,
			paddingRight : values.right
		});
		values.id = hui.string.fromJSON(this.part.element.getAttribute('data')).id;
		this.part.section = values;
	},
	_getPartEditor : function() {
	   	if (!this._partWindow) {
	   		var w = this._partWindow = hui.ui.Window.create({padding:5,title:'Afsnit',close:false,width: 200});
	   		var f = this._partEditorForm = hui.ui.Formula.create();
	   		f.buildGroup({above:false},[
	   			{type:'StyleLength',label:'Top',options:{key:'top'}},
	   			{type:'StyleLength',label:'Bottom',options:{key:'bottom'}},
	   			{type:'StyleLength',label:'Left',options:{key:'left'}},
	   			{type:'StyleLength',label:'Right',options:{key:'right'}}
	   		]);
	   		w.add(f);
	   		f.listen({$valuesChanged:this._updatePartProperties.bind(this)});
	   	}
	},
	
	
	loadPart : function(options) {
		hui.ui.request({
			url : op.context+'Editor/Template/document/live/LoadPart.php',
			parameters : {type:options.part.type,id:options.part.id},
			$object : function(part) {
				options.$success(part);
				options.callback();
			},
			$failure : function() {
				options.callback();
			}
		});
	},
	savePart : function(options) {
		var parameters = hui.override({id:options.part.id,pageId:op.page.id,type:options.part.type,section:hui.string.toJSON(options.part.section)},options.parameters);
		hui.ui.request({
			url : op.context+'Editor/Template/document/live/SavePart.php',
			parameters : parameters,
			$text : function(html) {
				options.$success(html);
				options.callback();
			},
			$failure : function() {
				options.callback();
			}
		});
	}
};

hui.ui.listen(op.DocumentEditor);

op.FieldResizer = function(options) {
	this.options = options;
	this.options.field.style.overflow='hidden';
	this.dummy = document.createElement('div');
	document.body.appendChild(this.dummy);
	this.dummy.style.position='absolute';
	this.dummy.style.left='-999999px';
	this.dummy.style.top='-999999px';
	this.dummy.style.visibility='hidden';
	var self = this;
	hui.listen(options.field,'keyup',function(){self.resize(false,true)});
	hui.listen(options.field,'keydown',function(){self.options.field.scrollTop=0;});
}

op.FieldResizer.prototype = {
	resize : function(instantly,focused) {
				
		var field = this.options.field;
		hui.style.copy(field,this.dummy,[
			'font-size','line-height','font-weight','letter-spacing','word-spacing','font-family','text-transform','font-variant','text-indent'
		]);
		var html = field.value;
		if (html[html.length-1]==='\n') {
			html+='x';
		}
		// Force webkit redraw
		if (!focused) {
			field.style.display='none';
			field.offsetHeight; // no need to store this anywhere, the reference is enough
			field.style.display='block';
		}
		this.dummy.innerHTML = html.replace(/\n/g,'<br/>');
		this.options.field.style.webkitTransform = 'scale(1)';
		this.dummy.style.width=this.options.field.clientWidth+'px';
		var height = Math.max(50,this.dummy.clientHeight)+'px';
		hui.log(height)
		if (instantly) {
			this.options.field.style.height=height;
		} else {
			//this.options.field.scrollTop=0;
			hui.animate(this.options.field,'height',height,200,{ease:hui.ease.slowFastSlow});
		}
	}
}