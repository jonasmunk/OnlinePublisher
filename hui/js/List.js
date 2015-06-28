/**
 * A list
 * <pre><strong>options:</strong> {
 *  element : «Element | ID»,
 *  name : «String»,
 *  url : «String»,
 *  source : «hui.ui.Source»,
 *  selectable : «<strong>true</strong> | false»,
 *  dropFiles : «true | <strong>false</strong>»,
 *  indent : «Integer»
 * }
 *
 * <strong>Events:</strong>
 * $open(row) - When a row is double clicked (rename to open)
 * $select(firstRow) - When a row is (un)selected/(un)checked
 * $clickButton({row:row,button:button}) - When a button is clicked
 * $clickIcon({row:row,data:data,node:node}) - When an icon is clicked
 *
 * <strong>Bindings:</strong>
 * <del>window</del>
 * window.page
 * sort.direction
 * sort.key
 * </pre>
 *
 * @constructor
 * @param {Object} options The options : {url:null,source:null,selectable:«boolean»}
 */
hui.ui.List = function(options) {
	this.options = hui.override({url:null,source:null,selectable:true,indent:null,selectMany:false,rememberSelection:false},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	if (this.options.source) {
		this.options.source.listen(this);
	}
	this.url = options.url;
	this.table = hui.get.firstByTag(this.element,'table');
	this.head = hui.get.firstByTag(this.element,'thead');
	this.body = hui.get.firstByTag(this.element,'tbody');
	this.columns = [];
	this.rows = [];
	this.selected = [];
	this.checked = [];
	this.navigation = hui.get.firstByClass(this.element,'hui_list_navigation');
	this.count = hui.get.firstByClass(this.navigation,'hui_list_count');
	this.windowPage = hui.get.firstByClass(this.navigation,'window_page');
	this.windowPageBody = hui.get.firstByClass(this.navigation,'window_page_body');
	this.parameters = {};
	this.sortKey = null;
	this.sortDirection = null;
	
	this.window = {size:null,page:0,total:0};
	if (options.windowSize!='') {
		this.window.size = parseInt(options.windowSize);
	}
	hui.ui.extend(this);
	if (options.dropFiles) {
		this._addDrop();
	}
	if (this.url)  {
		this.refresh();
	}
}

/**
 * Creates a new list widget
 * <pre><strong>options:</strong> {
 *  maxHeight : «Integer»,
 *
 *  name : «String»,
 *  url : «String»,
 *  source : «hui.ui.Source»,
 *  selectable : «<strong>true</strong> | false»,
 *  dropFiles : «true | <strong>false</strong>»,
 *  indent : «Integer»
 * }
 * @param {Object} options The options
 */
hui.ui.List.create = function(options) {
	options = options || {};
	var cls = 'hui_list';
	if (options.variant) {
		cls+=' hui_list_'+options.variant;
	}
	options.element = hui.build('div',{
		'class' : cls,
		html: '<div class="hui_list_progress"></div><div class="hui_list_navigation"><div class="hui_list_selection window_page"><div><div class="window_page_body"></div></div></div><span class="hui_list_count"></span></div><div class="hui_list_body"'+(options.maxHeight>0 ? ' style="max-height: '+options.maxHeight+'px; overflow: auto;"' : '')+'><table cellspacing="0" cellpadding="0"><thead><tr></tr></thead><tbody></tbody></table></div>'});
	return new hui.ui.List(options);
}

hui.ui.List.prototype = {
	_addDrop : function() {
		hui.drag.listen({
			element : this.element,
			hoverClass : 'hui_list_drop',
			$dropFiles : function(files) {
				this.fire('filesDropped',files);
			}.bind(this),
			$dropURL : function(url) {
				this.fire('urlDropped',url);
			}.bind(this)
		})
	},
	/** Hides the list */
	hide : function() {
		this.element.style.display='none';
	},
	
	/** Shows the list */
	show : function() {
		this.element.style.display='block';
		this.refresh();
	},
	
	/** @private */
	registerColumn : function(column) {
		this.columns.push(column);
	},
	
	/** Gets an array of selections
	 * @returns {Array} The selected rows
	 */
	getSelection : function() {
		if (this.selected.length>0) {
			return this._getRowsByIndexes(this.selected);
		}
		return this._getRowsByIndexes(this.checked);
	},
	/** Gets the first selection or null
	 * @returns {Object} The first selected row
	 */
	getFirstSelection : function() {
		var items = this.getSelection();
		if (items.length>0) {
			return items[0];
		}
		return null;
	},
	getSelectionSize : function() {
		return this.selected.length+this.checked.length;
	},
	getSelectionIds : function() {
		var selection = this.getSelection(),
			ids = [];
		for (var i=0; i < selection.length; i++) {
			ids.push(selection[i].id);
		};
		return ids;
	},
	_getRowsByIndexes : function(indexes) {
		var items = [];
		for (var i=0; i < indexes.length; i++) {
			items.push(this.rows[indexes[i]]);
		};
		return items;
	},
	
	/** Gets all rows of the list
	 * @returns {Array} The all rows
	 */
	getRows : function() {
		return this.rows;
	},
	/** Add a parameter 
	 * @param {String} key The key
	 * @param {String} value The value
	 */
	setParameter : function(key,value) {
		this.parameters[key]=value;
	},
	/** @private */
	loadData : function(url) {
		this.setUrl(url);
	},
	/**
	 * Sets the lists data source and refreshes it if it is new
	 * @param {hui.ui.Source} source The source
	 */
	setSource : function(source) {
		if (this.options.source!=source) {
			if (this.options.source) {
				this.options.source.unListen(this);
			}
			source.listen(this);
			this.options.source = source;
			source.refresh();
		}
	},
	/**
	 * Set an url to load data from, and load the data
	 * @param {String} url The url
	 */
	setUrl : function(url) {
		if (this.options.source) {
			this.options.source.unListen(this);
			this.options.source=null;
		}
		this.url = url;
		this.selected = [];
		this.checked = [];
		this.sortKey = null;
		this.sortDirection = null;
		this.resetState();
		this.refresh();
	},
	/** Clears the data of the list and removes its data source */
	clear : function() {
		this._empty();
		if (this.options.source) {
			this.options.source.unListen(this);
		}
		this.options.source = null;
		this.url = null;
	},
	clearSelection : function() {
		this._changeSelection([]);
	},
	_empty : function() {
		this.selected = [];
		this.checked = [];
		this.columns = [];
		this.rows = [];
		this.navigation.style.display='none';
		hui.dom.clear(this.body);
		hui.dom.clear(this.head);
	},
	/** Resets the window state of the navigator */
	resetState : function() {
		this.window = {size:null,page:0,total:0};
		hui.ui.firePropertyChange(this,'window',this.window);
		hui.ui.firePropertyChange(this,'window.page',this.window.page);
	},
	/** @private */
	valueForProperty : function(p) {
		if (p=='window.page') return this.window.page;
		else if (p=='sort.key') return this.sortKey;
		else if (p=='sort.direction') return (this.sortDirection || 'ascending');
		else if (p=='selection.id') {
			var s = this.getFirstSelection();
			if (s) {
				return s.id;
			}
			return null;
		}
		else return this[p];
	},
	/** @private */
	$sourceShouldRefresh : function() {
		return hui.dom.isVisible(this.element);
	},
	/** @private */
	$visibilityChanged : function() {
		if (this.options.source && hui.dom.isVisible(this.element)) {
			// If there is a source, make sure it is initially 
			this.options.source.refreshFirst();
		}
	},
	/** @private */
	refresh : function() {
		if (this.options.source) {
			this.options.source.refreshLater();
			return;
		}
		if (!this.url) return;
		var url = this.url;
		if (typeof(this.window.page)=='number') {
			url+=url.indexOf('?')==-1 ? '?' : '&';
			url+='windowPage='+this.window.page;
		}
		if (this.window.size) {
			url+=url.indexOf('?')==-1 ? '?' : '&';
			url+='windowSize='+this.window.size;
		}
		if (this.sortKey) {
			url+=url.indexOf('?')==-1 ? '?' : '&';
			url+='sort='+this.sortKey;
		}
		if (this.sortDirection) {
			url+=url.indexOf('?')==-1 ? '?' : '&';
			url+='direction='+this.sortDirection;
		}
		for (var key in this.parameters) {
			url+=url.indexOf('?')==-1 ? '?' : '&';
			url+=key+'='+this.parameters[key];
		}
		this._setBusy(true);
		hui.ui.request({
			url:url,
			$object : function(obj) {this._setBusy(false);this.$objectsLoaded(obj)}.bind(this),
			$xml : function(obj) {this._setBusy(false);this.$listLoaded(obj)}.bind(this)
		});
	},
	/** @private */
	sort : function(index) {
		var key = this.columns[index].key;
		if (key==this.sortKey) {
			this.sortDirection = this.sortDirection=='ascending' ? 'descending' : 'ascending';
			hui.ui.firePropertyChange(this,'sort.direction',this.sortDirection);
		} else {
			hui.ui.firePropertyChange(this,'sort.key',key);
		}
		this.sortKey = key;
	},
	_debug : function(obj) {
		//hui.log(obj);
	},

	/** @private */
	$listLoaded : function(doc) {
		this._debug('List loaded');
		this._setError(false);
		var hadSelection = this.selected.length>0 || this.checked.length>0;
		var previousSelection;
		if (this.options.rememberSelection) {
			previousSelection = this.getSelectionIds();
		}
		this.selected = [];
		this.checked = [];
		this._parseWindow(doc);
		this._buildNavigation();
		hui.dom.clear(this.head);
		hui.dom.clear(this.body);
		this.rows = [];
		this.columns = [];
		this.checkboxMode = doc.documentElement.getAttribute('checkboxes')==='true';
		var movable = doc.documentElement.getAttribute('ordering')==='true';
		var headTr = document.createElement('tr');
		var sort = doc.getElementsByTagName('sort');
		this.sortKey=null;
		this.sortDirection=null;
		if (sort.length>0) {
			this.sortKey = sort[0].getAttribute('key');
			this.sortDirection = sort[0].getAttribute('direction');
		}
		if (this.checkboxMode) {
			var th = hui.build('th',{className:'list_check',parent:headTr});
			var a = hui.build('a',{className:'list_check_all',parent:th})
			hui.listen(th,'click',this._checkAll.bind(this));
		}
    if (movable) {
      hui.build('th',{'class':'hui_list_order_header',parent:headTr});
    }
		var headers = doc.getElementsByTagName('header');
		var i;
		for (i=0; i < headers.length; i++) {
			var className = '';
			var th = document.createElement('th');
			var width = headers[i].getAttribute('width');
			var key = headers[i].getAttribute('key');
			var sortable = headers[i].getAttribute('sortable')=='true';
			if (width && width!='') {
				th.style.width=width+'%';
			}
			if (headers[i].getAttribute('align')) {
				th.style.textAlign=headers[i].getAttribute('align');
			}
			if (sortable) {
				var self = this;
				th.huiIndex = i;
				th.onclick=function() {self.sort(this.huiIndex)};
				className+='sortable';
			}
			if (this.sortKey && key==this.sortKey) {
				className+=' sort_'+this.sortDirection;
			}
			th.className=className;
			var span = document.createElement('span');
			th.appendChild(span);
			span.appendChild(document.createTextNode(headers[i].getAttribute('title') || ''));
			headTr.appendChild(th);
			this.columns.push({'key':key,'sortable':sortable,'width':width});
		};
		this.head.appendChild(headTr);
		var frag = document.createDocumentFragment();
		var rows = doc.getElementsByTagName('row');
		for (i=0; i < rows.length; i++) {
			var cells = rows[i].getElementsByTagName('cell');
			var row = document.createElement('tr');
			row.setAttribute('data-index',i);
			
			if (this.checkboxMode) {
				var td = hui.build('td',{parent:row,className:'hui_list_checkbox'});
				hui.build('a',{className:'hui_list_checkbox',parent:td});
			}
      if (movable) {
				var td = hui.build('td',{parent:row,className:'hui_list_order'});        
				hui.build('a',{className:'hui_list_order_handler',parent:td});
      }
			
			var icon = rows[i].getAttribute('icon');
			var title = rows[i].getAttribute('title');
			var level = rows[i].getAttribute('level');
			for (var j=0; j < cells.length; j++) {
				var td = document.createElement('td');
				if (cells[j].getAttribute('wrap')=='false') {
					td.style.whiteSpace='nowrap';
				}
				if (cells[j].getAttribute('vertical-align')) {
					td.style.verticalAlign=cells[j].getAttribute('vertical-align');
				}
				if (cells[j].getAttribute('width')) {
					td.style.width=cells[j].getAttribute('width')+'%';
				}
				if (cells[j].getAttribute('align')) {
					td.style.textAlign=cells[j].getAttribute('align');
				}
				if (cells[j].getAttribute('class')) {
					hui.cls.add(td,cells[j].getAttribute('class'));
				}
				if (cells[j].getAttribute('dimmed')=='true') {
					td.className='hui_list_dimmed';
				}
				if (j==0 && level>1) {
					td.style.paddingLeft = ((parseInt(level)-1)*16+5)+'px';
				} else if (j==0 && this.options.indent!=null) {
					td.style.paddingLeft = this.options.indent+'px';
				}
				this._parseCell(cells[j],td);
				row.appendChild(td);
				if (!title) {
					title = hui.dom.getText(cells[j]);
				}
				if (!icon) {
					icon = cells[j].getAttribute('icon');
				}
			};
			var info = {id:rows[i].getAttribute('id'),kind:rows[i].getAttribute('kind'),icon:icon,title:title,index:i,data:this._getData(rows[i])};
			row.dragDropInfo = info;
			this._addRowBehavior(row,i);
			frag.appendChild(row);
			this.rows.push(info);
		};
		this.body.appendChild(frag);
		this._setEmpty(rows.length==0);
		if (this.options.rememberSelection) {
			hui.log('Previous:',previousSelection);
			var s = [];
			for (var i=0; i < previousSelection.length; i++) {
				for (var j=0; j < this.rows.length; j++) {
					if (this.rows[j].id===previousSelection[i]) {
						s.push(j);
					}
				};
			};
			this._changeSelection(s);
		} else {
			this.fire('selectionReset');
			if (hadSelection) {
				this.fire('select',null);
			}			
		}
		this.fireSizeChange();
	},
	_parseCell : function(node,cell) {
		var variant = node.getAttribute('variant');
		if (variant!=null && variant!='') {
			cell = hui.build('div',{parent:cell,className : 'hui_list_cell_'+variant});
		}
		var icon = node.getAttribute('icon');
		if (icon!=null && icon!='') {
			cell.appendChild(hui.ui.createIcon(icon,16));
			cell = hui.build('div',{parent:cell,style:'margin-left: 21px; padding-top: 1px;'});
		}
		for (var i=0; i < node.childNodes.length; i++) {
			var child = node.childNodes[i];
			if (hui.dom.isDefinedText(child)) {
				hui.dom.addText(cell,child.nodeValue);
			} else if (hui.dom.isElement(child,'break')) {
				cell.appendChild(document.createElement('br'));
			} else if (hui.dom.isElement(child,'icon')) {
				var size = child.getAttribute('size') || 16;
				var icon = hui.ui.createIcon(child.getAttribute('icon'),size);
				if (child.getAttribute('hint')) {
					icon.setAttribute('title',child.getAttribute('hint'));
				}
				if (child.getAttribute('action')=='true') {
					hui.cls.add(icon,'hui_list_icon_action');
				}
				var data = child.getAttribute('data');
				if (data) {
					icon.setAttribute('data',data);
				}
				if (child.getAttribute('revealing')==='true') {
					hui.cls.add(icon,'hui_list_revealing');
				}
				cell.appendChild(icon);
			} else if (hui.dom.isElement(child,'line')) {
				var line = hui.build('p',{'class':'hui_list_line'});
				if (child.getAttribute('dimmed')=='true') {
					hui.cls.add(line,'hui_list_dimmed')
				}
				if (child.getAttribute('minor')=='true') {
					hui.cls.add(line,'hui_list_minor')
				}
				if (child.getAttribute('mini')=='true') {
					hui.cls.add(line,'hui_list_mini')
				}
				if (child.getAttribute('class')) {
					hui.cls.add(line,child.getAttribute('class'))
				}
				if (child.getAttribute('top')) {
					line.style.paddingTop=child.getAttribute('top')+'px';
				}
				cell.appendChild(line);
				this._parseCell(child,line);
			} else if (hui.dom.isElement(child,'object')) {
				var obj = hui.build('div',{'class':'hui_list_object'});
				if (child.getAttribute('icon')) {
					obj.appendChild(hui.ui.createIcon(child.getAttribute('icon'),16));
				}
				if (child.firstChild && child.firstChild.nodeType===3 && child.firstChild.nodeValue.length>0) {
					hui.dom.addText(obj,child.firstChild.nodeValue);
				}
				cell.appendChild(obj);
			} else if (hui.dom.isElement(child,'icons')) {
				var icons = hui.build('span',{'class':'hui_list_icons'});
				if (child.getAttribute('left')) {
					icons.style.marginLeft=child.getAttribute('left')+'px';
				}
				this._parseCell(child,icons);
				cell.appendChild(icons);
			} else if (hui.dom.isElement(child,'button')) {
				var button = hui.ui.Button.create({text:child.getAttribute('text'),small:true,rounded:true,data:this._getData(child)});
				button.click(this._buttonClick.bind(this))
				cell.appendChild(button.getElement());
			} else if (hui.dom.isElement(child,'wrap')) {
                if (hui.browser.wordbreak) {
                    hui.cls.add(cell,'hui_list_wrap');
                    hui.dom.addText(cell,hui.dom.getText(child));
                } else {
    				hui.dom.addText(cell,this._wrap(hui.dom.getText(child)));                    
                }
			} else if (hui.dom.isElement(child,'delete')) {
				this._parseCell(child,hui.build('del',{parent:cell}));
			} else if (hui.dom.isElement(child,'strong')) {
				this._parseCell(child,hui.build('strong',{parent:cell}));
			} else if (hui.dom.isElement(child,'badge')) {
				this._parseCell(child,hui.build('span',{className:'hui_list_badge',parent:cell}));
			} else if (hui.dom.isElement(child,'progress')) {
				var progress = hui.build('span',{className:'hui_list_progress',parent:cell});
				var percent = Math.round(parseFloat(child.getAttribute('value'))*100);
				hui.build('span',{style:{width:percent+'%'},parent:progress});
				this._parseCell(child,progress);
			} else if (hui.dom.isElement(child,'html')) {
				for (var j = 0; j < child.childNodes.length; j++) {
					var clone = child.childNodes[j].cloneNode(true);
					clone.namespaceURI = child.namespaceURI;
					cell.appendChild(clone);
				}
			}
		};
	},
	
	/** @private */
	$objectsLoaded : function(data) {
		var hadSelection = this.selected.length>0 || this.checked.length>0;
		this._setError(false);
		if (data==null) {
			// NOOP
		} else if (data.constructor == Array) {
			this.setObjects(data);
		} else {
			this.setData(data);
		}
		this.fire('selectionReset');
		if (hadSelection) {
			this.fire('select');
		}
		this.fireSizeChange();
	},
	/** @private */
	$sourceIsBusy : function() {
		this._debug('$sourceIsBusy');
		this._setBusy(true);
	},
	/** @private */
	$sourceIsNotBusy : function() {
		this._debug('$sourceIsNotBusy');
		this._setBusy(false);
	},
	/** @private */
	$sourceFailed : function() {
		hui.log('The source failed!');
		this._setError(true);
	},
	_setError : function(error) {
		hui.cls.set(this.element,'hui_list_error',error);
	},
	_setBusy : function(busy) {
		this.busy = busy;
		window.clearTimeout(this.busytimer);
		if (busy) {
			var e = this.element;
			this.busytimer = window.setTimeout(function() {
				hui.cls.add(e,'hui_list_busy');
				if (e.parentNode.className=='hui_overflow') {
					hui.cls.add(e,'hui_list_busy_large');
				}
			},300);
		} else {
			hui.cls.remove(this.element,'hui_list_busy');
			if (this.element.parentNode.className=='hui_overflow') {
				hui.cls.remove(this.element,'hui_list_busy_large');
			}
		}
	},
	_setEmpty : function(empty) {
		var lmnt = hui.get.firstByClass(this.element,'hui_list_empty');
		if (lmnt) {
			lmnt.style.display = empty ? 'block' : '';
		}
	},
	
	_wrap : function(str) {
		var out = '';
		var count = 0;
		for (var i=0; i < str.length; i++) {
			if (str[i]===' ' || str[i]==='-') {
				count=0;
			} else {
				count++;
			}
			out+=str[i];
			if (count>10) {
				out+='\u200B';
				count=0;
			}
		};
		return out;
	},
	_getData : function(node) {
		var data = node.getAttribute('data');
		if (data) {
			return hui.string.fromJSON(data);
		}
		return null;
	},
	_buttonClick : function(button) {
		var row = hui.get.firstParentByTag(button.getElement(),'tr');
		var obj = this.rows[parseInt(row.getAttribute('data-index'),10)];
		this.fire('clickButton',{row:obj,button:button});
	},
	_parseWindow : function(doc) {
		var wins = doc.getElementsByTagName('window');
		if (wins.length>0) {
			var win = wins[0];
			this.window.total = parseInt(win.getAttribute('total'));
			this.window.size = parseInt(win.getAttribute('size'));
			this.window.page = parseInt(win.getAttribute('page'));
		} else {
			this.window.total = 0;
			this.window.size = 0;
			this.window.page = 0;
		}
	},
	_buildNavigation : function() {
		var self = this;
		var pages = this.window.size>0 ? Math.ceil(this.window.total/this.window.size) : 0;
		if (pages<2) {
			this.navigation.style.display='none';
			return;
		}
		this.navigation.style.display='block';
		var from = ((this.window.page)*this.window.size+1);
		hui.dom.setText(this.count,(from+'-'+Math.min((this.window.page+1)*this.window.size,this.window.total)+' / '+this.window.total));
		var pageBody = this.windowPageBody;
		pageBody.innerHTML='';
		if (pages<2) {
			this.windowPage.style.display='none';	
		} else {
			var indices = this._buildPages(pages,this.window.page);
			for (var i=0; i < indices.length; i++) {
				var index = indices[i];
				if (index==='') {
					pageBody.appendChild(hui.build('span',{text:'·'}));
				} else {
					var a = document.createElement('a');
					a.appendChild(document.createTextNode(index+1));
					a.setAttribute('data-index',index);
					a.onmousedown = function() {
						self._onPageClick(this);
						return false;
					}
					if (index==self.window.page) {
						a.className='hui_list_selected';
					}
					pageBody.appendChild(a);
				}
			}
			this.windowPage.style.display='block';
		}
	},
	_buildPages : function(count,selected) {
		var pages = [];
		var x = false;
		for (var i=0;i<count;i++) {
			if (i<1 || i>count-2 || Math.abs(selected-i)<5) {
				pages.push(i);
				x=false;
			} else {
				if (!x) {
					pages.push('')
				};
				x=true;
			}
		}
		return pages;
	},
	/** @private */
	setData : function(data) {
		this.selected = [];
		this.checked = [];
		var win = data.window || {};
		this.window.total = win.total || 0;
		this.window.size = win.size || 0;
		this.window.page = win.page || 0;
		this._buildNavigation();
		this._buildHeaders(data.headers);
		this._buildRows(data.rows);
		this._setEmpty(!data.rows || data.rows.length==0);
	},
	/** @private */
	_buildHeaders : function(headers) {
		hui.dom.clear(this.head);
		this.columns = [];
		var tr = hui.build('tr',{parent:this.head});
		hui.each(headers,function(h,i) {
			var th = hui.build('th');
			if (h.width) {
				th.style.width = h.width+'%';
			}
			if (h.sortable) {
				hui.listen(th,'click',function() {this.sort(i)}.bind(this));
				hui.cls.add(th,'sortable');
			}
			th.appendChild(hui.build('span',{text:h.title}));
			tr.appendChild(th);
			this.columns.push(h);
		}.bind(this));
	},
	/** @private */
	_buildRows : function(rows) {
		var self = this;
		hui.dom.clear(this.body);
		this.rows = [];
		if (!rows) return;
		hui.each(rows,function(r,i) {
			var tr = hui.build('tr');
			var icon = r.icon;
			var title = r.title;
			hui.each(r.cells,function(c) {
				var td = hui.build('td');
				if (c.icon) {
					td.appendChild(hui.ui.createIcon(c.icon,16));
					icon = icon || c.icon;
				}
				if (c.text) {
					td.appendChild(document.createTextNode(c.text))
					title = title || c.text;
				}
				tr.appendChild(td);
			})
			self.body.appendChild(tr);
			// TODO: Memory leak!
			var info = {id:r.id,kind:r.kind,icon:icon,title:title,index:i};
			tr.dragDropInfo = info;
			hui.log(this._getData(tr))
			self.rows.push({id:r.id,kind:r.kind,icon:icon,title:title,index:i,data:r.data});
			this._addRowBehavior(tr,i);
		}.bind(this));
	},
	/** @private */
	setObjects : function(objects) {
		objects = objects || [];
		this.selected = [];
		this.checked = [];
		hui.dom.clear(this.body);
		this.rows = [];
		for (var i=0; i < objects.length; i++) {
			var row = hui.build('tr');
			var obj = objects[i];
			var title = null;
			for (var j=0; j < this.columns.length; j++) {
				var cell = hui.build('td');
				if (this.builder) {
					cell.appendChild(this.builder.buildColumn(this.columns[j],obj));
				} else {
					var value = obj[this.columns[j].key] || '';
					if (hui.isArray(value)) {
						for (var k=0; k < value.length; k++) {
							if (value[k].constructor == Object) {
								cell.appendChild(this._createObject(value[k]));
							} else {
								cell.appendChild(hui.build('div',{text:value}));
							}
						};
					} else if (value.constructor == Object) {
						cell.appendChild(this._createObject(value[j]));
					} else {
						hui.dom.addText(cell,value);
						title = title==null ? value : title;
					}
				}
				row.appendChild(cell);
			};
			var info = {id:obj.id,kind:obj.kind,title:title};
			row.dragDropInfo = info;
			this.body.appendChild(row);
			this._addRowBehavior(row,i);
			this.rows.push(obj);
		};
	},
	/** @private */
	_createObject : function(object) {
		var node = hui.build('div',{'class':'hui_list_object'});
		if (object.icon) {
			node.appendChild(hui.ui.createIcon(object.icon,16));
		}
		hui.dom.addText(node,object.text || object.name || '')
		return node;
	},
	/** @private */
	_addRowBehavior : function(row,index) {
		var self = this;
		row.onmousedown = function(e) {
			if (self.busy) {return};
			var event = hui.event(e);
			var action = event.findByClass('hui_list_icon_action');
			if (!action) {
				var check = event.findByClass('hui_list_checkbox');
				if (check) {
					self._toggleChecked(index);
				} else {
					self._onRowDown(index,event);
					if (hui.ui.startDrag) {
						// Only if available
						hui.ui.startDrag(e,row);
					}
					return false;
				}
			}
		}
		row.ondblclick = function(e) {
			if (self.busy) {return};
			self._onRowDoubleClick(index,e);
			hui.selection.clear();
			return false;
		}
		row.onclick = function(e) {
			if (self.busy) {return};
			self._onRowClick(index,e);
			return false;
		}
	},
	_checkAll : function() {
		for (var i=0; i < this.rows.length; i++) {
			hui.array.flip(this.checked,i);
		};
		this._drawChecks();
		this._changeSelection([]);
	},
	_toggleChecked : function(index) {
		hui.array.flip(this.checked,index);
		this._drawChecks();
		this._changeSelection([]);
	},
	_drawChecks : function() {
		var rows = this.body.getElementsByTagName('tr');
		for (var i=0; i < rows.length; i++) {
			hui.cls.set(rows[i],'hui_list_checked',hui.array.contains(this.checked,i));
		};
	},
	_clearChecked : function() {
		this.checked = [];
		this._drawChecks();
		this.fire('checkedReset');
	},
	_changeSelection : function(indexes) {
		var rows = this.body.getElementsByTagName('tr'),
			i;
		for (i = 0 ; i < this.selected.length; i++) {
			hui.cls.remove(rows[this.selected[i]],'hui_list_selected');
		}
		for (i = 0; i < indexes.length; i++) {
			hui.cls.add(rows[indexes[i]],'hui_list_selected');
		}
		this.selected = indexes;
		if (indexes.length > 0) {
			this.fire('select',this.rows[indexes[0]]);
			hui.ui.firePropertyChange(this,'selection.id',this.rows[indexes[0]].id);
			this._clearChecked();
		} else {
			this.fire('select');
		}
	},
	_onRowClick : function(index,e) {
		e = hui.event(e);
		var a = e.findByClass('hui_list_icon_action');
		if (a) {
			var data = a.getAttribute('data');
			if (data) {
				data = hui.string.fromJSON(data);
			}
			this.fire('clickIcon',{row:this.rows[index],data:data,node:a});
		}
	},
	_onRowDown : function(index,event) {
		if (!this.options.selectable) {
			return;
		}
		this.checked = [];
		if (event.metaKey && this.options.selectMany) {
			var newSelection = this.selected.slice(0);
			hui.array.flip(newSelection,index);
			this._changeSelection(newSelection);
		} else {
			this._changeSelection([index]);
		} 
	},
	_onRowDoubleClick : function(index,e) {
		e = hui.event(e);
		if (!e.findByClass('hui_list_icon_action')) {
			var row = this.getFirstSelection();
			if (row) {
				this.fire('open',row);
			}			
		}
	},
	/** @private */
	_onPageClick : function(tag) {
		var index = parseInt(tag.getAttribute('data-index'));
		this.window.page = index;
		hui.ui.firePropertyChange(this,'window',this.window);
		hui.ui.firePropertyChange(this,'window.page',this.window.page);
		var as = this.windowPageBody.getElementsByTagName('a');
		for (var i = as.length - 1; i >= 0; i--){
			as[i].className = as[i]==tag ? 'hui_list_selected' : '';
		};
	}
};

/* EOF */