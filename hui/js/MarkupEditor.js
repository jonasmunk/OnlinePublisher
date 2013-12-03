/**
 * @constructor
 * @param options The options { debug : «boolean», value : '«html»', autoHideToolbar : «boolean», style : '«css»', replace : «node-or-id»}
 */
hui.ui.MarkupEditor = function(options) {
	this.name = options.name;
	this.options = hui.override({debug:false,value:'',autoHideToolbar:true,style:'font-family: sans-serif; font-size: 11px;'},options);
	if (this.options.replace) {
		this.options.replace = hui.get(options.replace);
		this.options.element = hui.build('div',{className:'hui_markupeditor '+this.options.replace.className});
		this.options.replace.parentNode.insertBefore(this.options.element,this.options.replace);
		this.options.replace.style.display='none';
		this.options.value = this.options.replace.innerHTML;
	}
	this.ready = false;
	this.pending = [];
	this.element = hui.get(this.options.element);
	if (hui.browser.msie) {
		this.impl = hui.ui.MarkupEditor.MSIE;
	} else {
		this.impl = hui.ui.MarkupEditor.webkit;
	}
	this.impl.initialize({element:this.element,controller:this});
	if (this.options.value) {
		this.setValue(this.options.value);
	}
	hui.ui.extend(this);
}

hui.ui.MarkupEditor.create = function(options) {
	options = options || {};
	options.element = hui.build('div',{className:'hui_markupeditor'});
	return new hui.ui.MarkupEditor(options);
}

hui.ui.MarkupEditor.prototype = {
	/** @private */
	implIsReady : function() {
		this.ready = true;
		for (var i=0; i < this.pending.length; i++) {
			this.pending[i]();
		};
	},
	/** @private */
	implFocused : function() {
		this._showBar();
	},
	/** @private */
	implBlurred : function() {
		this.bar.hide();
		this.fire('blur');
	},
	/** @private */
	implValueChanged : function() {
		this._valueChanged();
	},
    implSelectionChanged : function() {
        if (this.options.linkDelegate) {
            this.options.linkDelegate.$cancel();
        }
		this._highlightNode(null)
		this.temporaryLink = null;
		this._valueChanged();
        this._refreshInfoWindow();
    },
	/** Remove the widget from the DOM */
	destroy : function() {
		hui.dom.remove(this.element);
		hui.ui.destroy(this);
		if (this.options.replace) {
			this.options.replace.style.display='';
		}
	},
	/** Get the HTML value */
	getValue : function() {
		return this.impl.getHTML();
	},
	/** Set the HTML value */
	setValue : function(value) {
		this._whenReady(function() {
			this.impl.setHTML(value);
		}.bind(this));
	},
	/** Focus the editor */
	focus : function() {
		this._whenReady(this.impl.focus.bind(this.impl));
	},

	_whenReady : function(func) {
		if (this.ready) {
			func();
		} else {
			this.pending.push(func);
		}
	},
	_showBar : function() {
		if (!this.bar) {
			var things = [
				{key:'bold',icon:'edit/text_bold'},
				{key:'italic',icon:'edit/text_italic'},
				{divider:true},
				{key:'color',icon:'common/color'},
				/*{key:'image',icon:'common/image'},*/
				{key:'addLink',icon:'monochrome/link'},
				{divider:true},
				{key:'align',value:'left',icon:'edit/text_align_left'},
				{key:'align',value:'center',icon:'edit/text_align_center'},
				{key:'align',value:'right',icon:'edit/text_align_right'},
				{key:'align',value:'justify',icon:'edit/text_align_justify'},
				{divider:true},
				{key:'clear',icon:'edit/clear'},
				{key:'info',icon:'monochrome/info'}/*,
				{key:'strong',icon:'edit/text_bold'}*/
				
				
				/*,
				{key:'insert-table',icon:'edit/text_italic'}*/
			]
			
			this.bar = hui.ui.Bar.create({absolute:true,variant:'mini',small:true});
			var drop = hui.ui.DropDown.create({focus:false,variant:'bar_mini',items:[
				{value:'p',text:'Paragraph'},
				{value:'h1',text:'Header 1'},
				{value:'h2',text:'Header 2'},
				{value:'blockquote',text:'Blockquote'}
			]});
			this.bar.add(drop);
			hui.each(things,function(info) {
				if (info.divider) {
					this.bar.addDivider();
					return
				}
				var button = new hui.ui.Bar.Button.create({icon:info.icon,stopEvents:true});
				button.listen({
					$mousedown : function() { this._buttonClicked(info) }.bind(this)
				});
				this.bar.add(button);
			}.bind(this));
			this.bar.addToDocument();
		}
		this.bar.placeAbove(this);
		this.bar.show();
	},
	_buttonClicked : function(info) {
		this.impl.saveSelection();
		if (info.key=='color') {
			this._showColorPicker();
		} else if (info.key=='addLink') {
			this._showLinkEditor();
		} else if (info.key=='align') {
			this.impl.align(info.value);
		} else if (info.key=='clear') {
			this.impl.removeFormat();
		} else if (info.key=='info') {
			this._toggleInfoWindow();
		} else {
			this.impl.format(info);
		}
		this._valueChanged();
        this._refreshInfoWindow();
		this.impl.restoreSelection();
	},
	_showColorPicker : function() {
		if (!this.colorPicker) {
			this.colorPicker = hui.ui.Window.create();
			var picker = hui.ui.ColorPicker.create();
			picker.listen(this);
			this.colorPicker.add(picker);
			this.colorPicker.listen({
				$userClosedWindow : function() {
						this.impl.restoreSelection();
				}.bind(this)
			})
		}
		this.colorPicker.show({avoid:this.element});
	},
	_highlightNode : function(node) {
		if (this._highlightedNode) {
			hui.cls.remove(this._highlightedNode,'hui_markupeditor_highlighted');
		}
		this._highlightedNode = node;
		if (node) {
			hui.cls.add(node,'hui_markupeditor_highlighted');
		}
	},
	_showLinkEditor : function() {
		this.temporaryLink = this.impl.getOrCreateLink();
		this._highlightNode(this.temporaryLink);
		if (this.options.linkDelegate ) {
			var delegate = this.options.linkDelegate;
			delegate.$editLink({
				node : this.temporaryLink,
				$changed : function() {
					this._highlightNode(null)
					this.temporaryLink = null;
					hui.log('success');
					this._valueChanged();
				}.bind(this),
				$cancel : function() {
					this._highlightNode(null)
					hui.log('cancelled');
					this.temporaryLink = null;
					this._valueChanged();
				}.bind(this),
                $remove : function() {
                    // TODO: Standardise this
                    this.impl._unWrap(this.temporaryLink);
                    this._refreshInfoWindow();
                }.bind(this)
			});
		} else if (!this.linkEditor) {
			this.linkEditor = hui.ui.Window.create({padding:5,width:300});
			this.linkForm = hui.ui.Formula.create();
			this.linkEditor.add(this.linkForm);
			var group = this.linkForm.buildGroup({},[
				{type : 'TextField', options:{key:'url',label:'Address:'}}
			]);
			var buttons = group.createButtons();
			var ok = hui.ui.Button.create({text:'OK',submit:true});
			this.linkForm.listen({$submit:this._updateLink.bind(this)});
			buttons.add(ok);
		}
		if (this.linkEditor) {
			this.linkForm.setValues({url:this.temporaryLink.href});
			this.linkEditor.show({avoid:this.element});
			this.linkForm.focus();
		}
	},
	_updateLink : function() {
		var values = this.linkForm.getValues();
		this.temporaryLink.href = values.url;
		this.linkForm.reset();
		this.temporaryLink = null;
		this.linkEditor.hide();
		this._valueChanged();
	},
	_valueChanged : function() {
		this.fire('valueChanged',this.impl.getHTML());
        this._refreshInfoWindow();
	},
    
    // Info window
    
    _toggleInfoWindow : function() {
        if (!this._infoWindow) {
            this._infoWindow = hui.ui.Window.create({title:'Info',width:400});
            this._infoPath = hui.build('div',{'class':'hui_markupeditor_path'});
            this._infoWindow.add(this._infoPath);
        }
        this._infoWindow.toggle({avoid:this});
        this._refreshInfoWindow();
    },
    
    _refreshInfoWindow : function() {
        hui.log('_refreshInfoWindow');
        if (!this._infoWindow) {return};
        var html = '';
        var path = this.impl.getPath();
        for (var i = path.length - 1; i >= 0; i--) {
            html+='<a data-index="' + i + '">' + path[i].tagName + '<a> ';
        }
        this._infoPath.innerHTML = html;
    },
	
	/** @private */
	$colorWasSelected : function(color) {
		this.impl.restoreSelection(function() {
			this.impl.colorize(color);
			this._valueChanged();
		}.bind(this));
	},
    
	/** @private */
	$$parentMoved : function() {
        if (this.bar) {
    		this.bar.placeAbove(this);
        }
    }
}

/** @namespace */
hui.ui.MarkupEditor.webkit = {
    
    path : [],
    
	initialize : function(options) {
		this.element = options.element;
		this.element.style.overflow='auto';
		this.element.contentEditable = true;
		var ctrl = this.controller = options.controller;
		hui.listen(this.element,'focus',function() {
			hui.log('Webkit focus');
			ctrl.implFocused();
		});
		hui.listen(this.element,'blur',function() {
			ctrl.implBlurred();
		});
		hui.listen(this.element,'keyup',this._keyUp.bind(this));
		hui.listen(this.element,'mouseup',this._selectionChanged.bind(this));
		ctrl.implIsReady();
	},
	saveSelection : function() {
		
	},
	restoreSelection : function(callback) {
		if (callback) {callback()}
	},
	focus : function() {
		this.element.focus();
	},
	format : function(info) {
		if (info.key=='strong' || info.key=='em') {
			this._wrapInTag(info.key);
		} else if (info.key=='insert-table') {
			this._insertHTML('<table><tbody><tr><td>Lorem ipsum dolor</td><td>Lorem ipsum dolor</td></tr></tbody></table>');
		} else {
			document.execCommand(info.key,null,info.value);
            var node = this._getSelectedNode();
            if (node.tagName=='B') {
                node = hui.dom.changeTag(node,'strong');
        		var selection = window.getSelection();
    			selection.selectAllChildren(node);
            } else if (node.tagName=='I') {
                node = hui.dom.changeTag(node,'em');
        		var selection = window.getSelection();
    			selection.selectAllChildren(node);
            }
		}
        this._selectionChanged();
	},
	getOrCreateLink : function() {
		var node = this._getSelectedNode();
		if (node && node.tagName.toLowerCase()=='a') {
			return node;
		}
		document.execCommand('createLink',null,'#');
		return this._getSelectedNode();
	},
	_getSelectedNode : function() {
		var selection = window.getSelection();
		var range = selection.getRangeAt(0);
		var ancestor = range.commonAncestorContainer;
		if (!hui.dom.isElement(ancestor)) {
			ancestor = ancestor.parentNode;
		}
		return ancestor;
	},
	colorize : function(color) {
		document.execCommand('forecolor',null,color);
        var node = this._getSelectedNode();
        if (node.tagName=='FONT') {
            node = hui.dom.changeTag(node,'span');
            node.style.color = color;
            node.removeAttribute('color');
    		var selection = window.getSelection();
			selection.selectAllChildren(node);
        }
        this._selectionChanged();
	},
	align : function(value) {
		var x = {center:'justifycenter',justify:'justifyfull',left:'justifyleft',right:'justifyright'};
		document.execCommand(x[value],null,null);
	},
	_keyUp : function() {
		this.controller.implValueChanged();
		this._selectionChanged();
	},
	_wrapInTag : function(tag) {
		var selection = window.getSelection();
		if (selection.rangeCount<1) {return}
		var range = selection.getRangeAt(0);
		var ancestor = range.commonAncestorContainer;
		if (!hui.dom.isElement(ancestor)) {
			ancestor = ancestor.parentNode;
		}
		if (ancestor.tagName.toLowerCase()==tag) {
			this._unWrap(ancestor);
		} else {
			var node = document.createElement(tag);
			range.surroundContents(node);
			selection.selectAllChildren(node);
		}
        this._selectionChanged();
	},
	_getInlineTag : function() {
		var selection = window.getSelection();
		if (selection.rangeCount<1) {return}
		
	},
    removeLink : function(node) {
        this._unWrap(node);
        this._selectionChanged();
    },
	_unWrap : function(node) {
		var c = node.childNodes;
		for (var i=0; i < c.length; i++) {
			node.parentNode.insertBefore(c[i],node);
		};
		node.parentNode.removeChild(node);
	},
	_insertHTML : function(html) {
		document.execCommand('inserthtml',null,html);
	},
    _getAncestor : function() {
		var selection = window.getSelection();
		if (selection.rangeCount<1) {
            return null;
        }
		var range = selection.getRangeAt(0);
		var ancestor = range.commonAncestorContainer;
		if (!hui.dom.isElement(ancestor)) {
			ancestor = ancestor.parentNode;
		}
        return ancestor;
    },
	_selectionChanged : function() {
        var path = [],
            tag = this._getAncestor();
        while (tag !== this.element) {
            path.push(tag);
            tag = tag.parentNode;
        }
        this.path = path;
        this.controller.implSelectionChanged();
	},
	removeFormat : function() {
		document.execCommand('removeFormat',null,null);
        this._selectionChanged();
	},
	setHTML : function(html) {
		this.element.innerHTML = html;
	},
	getHTML : function() {
		var cleaned = hui.ui.MarkupEditor.util.clean(this.element);
		return cleaned.innerHTML;
	},
    getPath : function() {
        return this.path;
    }
}

/** @namespace */
hui.ui.MarkupEditor.MSIE = {
	initialize : function(options) {
		this.element = options.element;
		this.iframe = hui.build('iframe',{style:'display:block; width: 100%; border: 0;',parent:this.element})
		hui.listen(this.iframe,'load',this._load.bind(this));
		this.controller = options.controller;
	},
	saveSelection : function() {
		this.savedRange = this.document.selection.createRange();
		//this.savedSelection = this.document.selection.createRange().getBookmark();
	},
	restoreSelection : function(callback) {
		window.setTimeout(function() {
			this.body.focus();
			this.savedRange.select();
			if (callback) {callback()};
		}.bind(this));
	},
	_load : function() {
		this.document = hui.frame.getDocument(this.iframe);
		this.body = this.document.body;
		this.body.contentEditable = true;
		hui.listen(this.body,'keyup',this._keyUp.bind(this));
		hui.listen(this.body,'mouseup',this._mouseUp.bind(this));
		this.controller.implIsReady();
	},
	_keyUp : function() {
		this.controller.implValueChanged();	
		this.saveSelection();	
	},
	_mouseUp : function() {
		this.saveSelection();
	},
	focus : function() {
		this.body.focus();
		this.controller.implFocused();
	},
	align : function(value) {
		var x = {center:'justifycenter',justify:'justifyfull',left:'justifyleft',right:'justifyright'};
		this.document.execCommand(x[value],null,null);
	},
	format : function(info) {
		if (info.key=='strong' || info.key=='em') {
			this._wrapInTag(info.key);
		} else if (info.key=='insert-table') {
			this._insertHTML('<table><tbody><tr><td>Lorem ipsum dolor</td><td>Lorem ipsum dolor</td></tr></tbody></table>');
		} else {
			this.document.execCommand(info.key,null,null);
		}
	},
	removeFormat : function() {
		this.document.execCommand('removeFormat',null,null);
	},
	colorize : function(color) {
		this.document.execCommand('forecolor',null,color);
		this.restoreSelection();
	},
	_wrapInTag : function(tag) {
		document.execCommand('inserthtml',null,'<'+tag+'>'+hui.string.escape(hui.selection.getText())+'</'+tag+'>');
	},
	_insertHTML : function(html) {
		document.execCommand('inserthtml',null,html);
	},
	setHTML : function(html) {
		this.body.innerHTML = html;
	},
	getHTML : function() {
		var cleaned = hui.ui.MarkupEditor.util.clean(this.body);
		return cleaned.innerHTML;
	},
    getPath : function() {
        return [];
    }
}

/** @namespace */
hui.ui.MarkupEditor.util = {
	clean : function(node) {
		var copy = node.cloneNode(true);
		this.replaceNodes(copy,{b:'strong',i:'em',font:'span'});

		var apples = hui.get.byClass(copy,'Apple-style-span');
		for (var i = apples.length - 1; i >= 0; i--){
			apples[i].removeAttribute('class');
		};
		this.convertAttributesToStyle(copy);
		return copy;
	},
	replaceNodes : function(node,recipe) {
		for (var key in recipe) {
			var bs = node.getElementsByTagName(key);
			for (var i = bs.length - 1; i >= 0; i--) {
				var x = bs[i];
				var replacement = document.createElement(recipe[key]);
				var color = bs[i].getAttribute('color');
				if (color) {
					replacement.style.color=color;
				}
				hui.dom.replaceNode(x,replacement);
				var children = x.childNodes;
				for (var j=0; j < children.length; j++) {
					var removed = x.removeChild(children[j]);
					replacement.appendChild(removed);
				};
			};
		}
	},
	convertAttributesToStyle : function(node) {
		var all = node.getElementsByTagName('*');
		for (var i=0; i < all.length; i++) {
			var n = all[i];
			var align = n.getAttribute('align');
			if (align) {
				n.style.textAlign = align;
				n.removeAttribute('align');
			}
		};
	}
}

/* EOF */