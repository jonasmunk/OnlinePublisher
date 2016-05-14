/**
 * @constructor
 * @param options {Object} The options
 * @param options.debug {boolean}
 * @param options.value {String} The HTML to edit
 * @param options.css {String}
 * @param options.autoHideToolbar {boolean}
 * @param options.replace {Element | String}
 */
hui.ui.MarkupEditor = function(options) {
	this.name = options.name;
	this.options = options = hui.override({debug:false,value:'',autoHideToolbar:true},options);
	if (options.replace) {
		options.replace = hui.get(options.replace);
		options.element = hui.build('div',{'class':'hui_markupeditor '+options.replace.className});
		options.replace.parentNode.insertBefore(options.element,options.replace);
		options.replace.style.display='none';
		options.value = this.options.replace.innerHTML;
	}
	this.ready = false;
	this.pending = [];
	this.element = hui.get(options.element);
	if (hui.browser.msie) {
		this.impl = hui.ui.MarkupEditor.MSIE;
	} else {
		this.impl = hui.ui.MarkupEditor.webkit;
	}
	this.impl.initialize({
        element : this.element,
        controller : this,
        $ready : this._ready.bind(this)
    });
	if (options.value) {
		this.setValue(options.value);
	}
	hui.ui.extend(this);
}

hui.ui.MarkupEditor.create = function(options) {
	options = options || {};
	options.element = hui.build('div',{className:'hui_markupeditor'});
	return new hui.ui.MarkupEditor(options);
}

hui.ui.MarkupEditor.prototype = {
    
	_ready : function() {
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
        this.bar && this.bar.setBlock(this._getFirstBlock());
    },
    _getFirstBlock : function() {
        var path = this.impl.getPath();
        var blocks = ['P','DIV','H1','H2','H3','H4','H5','H6','BLOCKQUOTE'];
        for (var i = path.length - 1; i >= 0; i--) {
            var tag = path[i].tagName;
            if (blocks.indexOf(tag)!==-1) {
                return path[i];
            }
        }
        return null;
    },
    
	/** Remove the widget from the DOM */
	destroy : function() {
		hui.dom.remove(this.element);
		if (this.options.replace) {
			this.options.replace.style.display='';
		}
        var dest = ['colorPicker','_infoWindow','bar','impl'];
        for (var i = dest.length - 1; i >= 0; i--) {
            if (this[dest[i]]) {
                this[dest[i]].destroy();
            }
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
			this.bar = new hui.ui.MarkupEditor.Bar({
				$clickButton : this._buttonClicked.bind(this),
				$changeBlock : this._changeBlock.bind(this)
			})
		}
		this.bar.show(this);
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
		this.impl._selectionChanged();
	},
    _changeBlock : function(tag) {
        var block = this._getFirstBlock();
        if (block) {
            block = hui.dom.changeTag(block,tag);
			this.impl.selectNode(block);
        }
    },
	_showColorPicker : function() {
		if (!this.colorPicker) {
			this.colorPicker = hui.ui.Window.create({title:{en:'Color',da:'Farve'}});
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
					this._valueChanged();
				}.bind(this),
				$cancel : function() {
					this._highlightNode(null)
					this.temporaryLink = null;
					this._valueChanged();
				}.bind(this),
                $remove : function() {
                    // TODO: Standardise this
                    this.impl._unWrap(this.temporaryLink);
					this.impl._selectionChanged();
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
            this._infoWindow = new hui.ui.MarkupEditor.Info({editor:this});
        }
        this._infoWindow.toggle();
        this._refreshInfoWindow();
    },
    
    _refreshInfoWindow : function() {
        if (!this._infoWindow) {return};
        this._infoWindow.updatePath(this.impl.getPath());
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
    		this.bar.place(this);
        }
    }
}








hui.ui.MarkupEditor.Bar = function(options) {
    this.options = options;
    this._initialize();
    hui.ui.extend(this);
}

hui.ui.MarkupEditor.Bar.prototype = {
    _initialize : function() {

		var things = [
			{key:'bold',icon:'edit/text_bold'},
			{key:'italic',icon:'edit/text_italic'},
			{divider:true},
			{key:'color',icon:'common/color'},
			{key:'addLink',icon:'monochrome/link'},
			{divider:true},
			{key:'align',value:'left',icon:'edit/text_align_left'},
			{key:'align',value:'center',icon:'edit/text_align_center'},
			{key:'align',value:'right',icon:'edit/text_align_right'},
			{key:'align',value:'justify',icon:'edit/text_align_justify'},
			{divider:true},
			{key:'clear',icon:'edit/clear'},
			{key:'info',icon:'monochrome/info'}
		]
		
		this.bar = hui.ui.Bar.create({absolute:true,variant:'mini',small:true});
		var drop = this.blockSelector = hui.ui.DropDown.create({focus:false,variant:'bar_mini',items:[
			{value:'h1',text:'Header 1'},
			{value:'h2',text:'Header 2'},
			{value:'h3',text:'Header 3'},
			{value:'h4',text:'Header 4'},
			{value:'h5',text:'Header 5'},
			{value:'h6',text:'Header 6'},
			{value:'p',text:'Paragraph'},
			{value:'div',text:'Division'},
			{value:'blockquote',text:'Blockquote'}
		]});
		this.bar.add(drop);
        
        drop.listen({
            $valueChanged : function(value) {
                this.options.$changeBlock(value);
            }.bind(this)
        })
        
		hui.each(things,function(info) {
			if (info.divider) {
				this.bar.addDivider();
				return
			}
			var button = new hui.ui.Bar.Button.create({icon:info.icon,stopEvents:true});
			button.listen({
				$mousedown : function() { this.options.$clickButton(info) }.bind(this)
			});
			this.bar.add(button);
		}.bind(this));
		this.bar.addToDocument();
    },
    
    show : function(widget) {
		this.bar.placeAbove(widget);
		this.bar.show();
    },
    place : function(widget) {
		this.bar.placeAbove(widget);
    },
    hide : function() {
        this.bar.hide();
    },
    setBlock : function(value) {
        if (value) {
            this.blockSelector.setValue(value.tagName.toLowerCase());            
        }
    },
    destroy : function() {
        this.bar.destroy();
    }
}





hui.ui.MarkupEditor.Info = function(options) {
    this.options = options;
    this._initialize();
}

hui.ui.MarkupEditor.Info.prototype = {
    _initialize : function() {
        this._window = hui.ui.Window.create({title:'Info',width:400});
        this._css = hui.ui.CodeInput.create();
        this._css.listen({
            $valueChanged : function(value) {
                if (!this.tag) {return;}
                this.tag.setAttribute('style',value);
            }.bind(this)
        })
        this._window.add(this._css);
        this._path = hui.build('div',{'class':'hui_markupeditor_path'});
        this._window.add(this._path);
    },
    toggle : function() {
       this._window.toggle({avoid:this.options.editor.element});
    },
    updatePath : function(path) {
        var html = '';
        for (var i = path.length - 1; i >= 0; i--) {
            html+='<a data-index="' + i + '" href="javascript://">' + path[i].tagName + '</a> ';
        }
        this._path.innerHTML = html;
        this.tag = path[0];
        this._css.setValue(this.tag ? this.tag.getAttribute('style') : '');
    },
    destroy : function() {
        this._window.destroy();
    }
}




/** @namespace */
hui.ui.MarkupEditor.webkit = {
    
    path : [],
    
	initialize : function(options) {
		this.element = options.element;
        hui.style.set(this.element,options.controller.options.style);
		this.element.style.overflow='auto';
		this.element.contentEditable = true;
		var ctrl = this.controller = options.controller;
		hui.listen(this.element,'focus',function() {
			ctrl.implFocused();
		});
		hui.listen(this.element,'blur',function() {
			ctrl.implBlurred();
		});
		hui.listen(this.element,'keyup',this._change.bind(this));
		hui.listen(this.element,'mouseup',this._change.bind(this));
		options.$ready();
	},
	saveSelection : function() {
		
	},
	restoreSelection : function(callback) {
		if (callback) {callback()}
	},
	focus : function() {
		this.element.focus();
        this._selectionChanged();
        this.controller.implFocused();
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
				this.selectNode(node);
            } else if (node.tagName=='I') {
                node = hui.dom.changeTag(node,'em');
				this.selectNode(node);
            }
			this.controller._valueChanged();
		}
        this._selectionChanged();
	},
	selectNode : function(node) {
		window.getSelection().selectAllChildren(node);
        this._selectionChanged();
	},
	getOrCreateLink : function() {
		var node = this._getSelectedNode();
		if (node && node.tagName.toLowerCase()=='a') {
			return node;
		}
		document.execCommand('createLink',null,'#');
        this._selectionChanged();
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
        this._updateInlinePanel();
	},
	_change : function() {
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
	_buildInlinePanel : function() {
        this._inlinePanel = hui.ui.BoundPanel.create({variant:'light'});
        var content = hui.build('div',{
            'class' : 'hui_markupeditor_inlinepanel',
            html : '<a href="javascript://" data="bold"><strong>Bold</strong></a><a href="javascript://" data="italic"><em>Italic</em></a>'
        });
        hui.listen(content,'mousedown',function(e) {
            e = hui.event(e);
            e.stop();
            var a = e.findByTag('a');
            if (a) {
                this.saveSelection();
                this.format({key:a.getAttribute('data')})
                this.restoreSelection();
				this._selectionChanged();
            }
        }.bind(this))
        this._inlinePanel.add(content);
	},
    _updateInlinePanel : function() {
		var selection = window.getSelection();
		this._inlinePanel || this._buildInlinePanel();
		if (selection.rangeCount < 1) {
            this._inlinePanel.hide();
            return;
        }
		var range = selection.getRangeAt(0);
        if (range.startOffset==range.endOffset) {
            this._inlinePanel.hide();
            return;            
        }
        var rects = range.getClientRects();
        if (rects.length > 0) {
            var rect = rects[0];
            this._inlinePanel.position({rect:rect,position:'vertical'});
            this._inlinePanel.show();
        }
        
    },
	_selectionChanged : function() {
		var sel = window.getSelection();
		var hash = this._hash(sel);
		var node = sel.anchorNode ? sel.anchorNode.parentNode : null;
		var latest = this._latestSelection;
		if (latest) {
			if (node == latest.node && latest.hash == hash) {
				return;
			}
		}
		this._latestSelection = {node:node,hash:hash};
        var path = [],
            tag = this._getAncestor();
        while (tag && tag !== this.element) {
            path.push(tag);
            tag = tag.parentNode;
        }
        this.path = path;
        this.controller.implSelectionChanged();
        this._updateInlinePanel();
	},
	_storeSelection : function(selection) {
		
	},
	_hash : function(sel) {
		return sel.anchorOffset+':'+sel.baseOffset+':'+sel.extentOffset;
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
    },
    destroy : function() {
        if (this._inlinePanel) {
            this._inlinePanel.destroy();
        }
    }
}






















/** @namespace */
hui.ui.MarkupEditor.MSIE = {
	initialize : function(options) {
		this.element = options.element;
		this.iframe = hui.build('iframe',{style:'display:block; width: 100%; border: 0;',parent:this.element})
		hui.listen(this.iframe,'load',function() {
            this._load();
            options.$ready();
        }.bind(this));
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
    },
    destroy : function() {
        
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