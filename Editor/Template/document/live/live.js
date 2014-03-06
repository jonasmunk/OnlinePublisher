op.DocumentEditor = {
	
	part : null,
	section : null,

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
		this.part = part;
		this.originalStyle = this.part.element.getAttribute('style');
	},
	$cancelPart$huiEditor : function(part) {
		this.part.element.setAttribute('style',this.originalStyle);
	},
	_initiatePartWindow : function() {
		hui.ui.get('layoutFormula').setValues(this.section);
		if (this.part.$partWindowLoaded) {
			this.part.$partWindowLoaded()			
		}
		// Select the current page
		hui.ui.get('bar').select(hui.ui.get('pages').getPageKey());
		hui.ui.get('partWindow').show();
	},
	$deactivatePart$huiEditor : function() {
		var partWindow = hui.ui.get('partWindow');
		if (partWindow) {
			partWindow.hide();
			hui.ui.destroy(partWindow);
			hui.ui.destroyDescendants(partWindow);
			hui.dom.remove(partWindow.element);
		}
	},
	$clickButton$bar : function(button) {
		hui.ui.get('pages').goTo(button.getKey());
		hui.ui.get('bar').select(button.getKey());
	},
	$valuesChanged$layoutFormula : function(values) {
		this._updateSection(values);
		hui.override(this.section,values);
	},
	_updateSection : function(values) {
		hui.style.set(this.part.element,{
			paddingTop : values.top,
			paddingBottom : values.bottom,
			paddingLeft : values.left,
			paddingRight : values.right,
			'float' : values.float,
			'width' : values.width
		});
	},
	$toggleInfo$huiEditor : function() {
        if (this._loadingPartWindow) {
            return;
        }
		if (hui.ui.get('partWindow')) {
			this._initiatePartWindow();
			return;
		}
        this._loadingPartWindow = true;
		hui.ui.include({
			url : op.context+'Editor/Template/document/live/gui/properties.php?type=' + this.part.type,
			$success : function() {
                this._initiatePartWindow();
                this._loadingPartWindow = false;
            }.bind(this)
		})		
	},
	
	loadPart : function(options) {
		this.section = {};
		hui.ui.request({
			url : op.context+'Editor/Template/document/live/LoadPart.php',
			parameters : {type:options.part.type,id:options.part.id},
			$object : function(data) {
				options.$success(data.part);
				this.section = data.section;
				var form = hui.ui.get('layoutFormula');
				if (form) {
					form.setValues(this.section)
				}
				options.callback();
			}.bind(this),
			$failure : function() {
				options.callback();
			}
		});
	},
	savePart : function(options) {
		var parameters = hui.override({
			id : options.part.id,
			pageId : op.page.id,
			type : options.part.type,
			section : hui.string.toJSON(this.section)
		},options.parameters);
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
		if (instantly) {
			this.options.field.style.height=height;
		} else {
			//this.options.field.scrollTop=0;
			hui.animate(this.options.field,'height',height,200,{ease:hui.ease.slowFastSlow});
		}
	}
}