/**
 * @constructor
 */
op.Editor.Richtext = function(options) {
	this.element = hui.get(options.element);
	this.id = hui.ui.Editor.getPartId(this.element);
	this.container = hui.dom.firstChild(this.element);
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
		this.editor = new hui.ui.MarkupEditor({
            replace : this.container,
            linkDelegate : new op.Editor.Richtext.LinkDelegate()
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

// Link delegate

op.Editor.Richtext.LinkDelegate = function() {
    
}

op.Editor.Richtext.LinkDelegate.prototype = {
    _ensureUI : function() {
        if (this.window) {
            return;
        }
		var win = this.window = hui.ui.Window.create({title:'Link',padding:5,width:300,listener:{
        	$userClosedWindow : this._closeWindow.bind(this)
		}});
		var form = this.form = hui.ui.Formula.create({listener : {
        	$submit : this._submitForm.bind(this)
		}});
		win.add(form);
		var group = form.buildGroup({},[
			{type : 'TextField', options : {key:'url',label:'Address:',name:'linkAddress'}},
			{type : 'DropDown', options : {
				label : 'Page:',
				key : 'page',
				url : '../../Model/Items.php?type=page'
			}},
			{type : 'DropDown', options : {
				label : 'File:',
				key : 'file',
				url : '../../Model/Items.php?type=file'
			}}
		]);
		var buttons = group.createButtons();
		buttons.add(hui.ui.Button.create({text:'Remove', listener : {
		    $click : this._clickRemove.bind(this)
		}}));
		buttons.add(hui.ui.Button.create({text:'Cancel', listener : {
		    $click : this._clickCancel.bind(this)
		}}));
		buttons.add(hui.ui.Button.create({text:'OK',highlighted:true,submit:true}));
		
    },
    
	$editLink : function(options) {
        this._ensureUI();
		this.options = options;
		this.form.reset();
		var node = options.node;
		var info = hui.string.fromJSON(node.getAttribute('data'));
        hui.log(info);
		if (info) {
			this.form.setValues(info);
		}
		this.window.show();
	},
    $cancel : function() {
        if (this.form) {
    		this.form.reset();
    		this.window.hide();            
        }
    },
    
    // UI listeners
    
    _clickCancel : function() {
		this.form.reset();
		this.window.hide();
        this.options.$cancel();
    },
    
    _clickRemove : function() {
		this.form.reset();
		this.window.hide();
        this.options.$remove();
    },
    
    _submitForm : function() {
		this.options.node.setAttribute('data',hui.string.toJSON(this.form.getValues()));
		this.form.reset();
		this.window.hide();
		this.options.$changed();
    },
    _closeWindow : function() {
        this.options.$cancel();
    }
	
}