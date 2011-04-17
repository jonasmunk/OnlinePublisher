/**
 * <p><strong>Events:</strong></p>
 * <ul>
 * <li>listRowWasOpened - When a row is double clicked (rename to open)</li>
 * <li>selectionChanged - When a row is selected (rename to select)</li>
 * <li>selectionReset - When a selection is removed</li>
 * </ul>
 * <p><strong>Bindings:</strong></p>
 * <ul>
 * <li><del>window</del></li>
 * <li>window.page</li>
 * <li>sort.direction</li>
 * <li>sort.key</li>
 * </ul>
 * <p><strong>XML:</strong></p>
 * <code>
 * &lt;list name=&quot;list&quot; source=&quot;sourcesListSource&quot; state=&quot;list&quot;/&gt;
 * <br/>
 * &lt;list name=&quot;list&quot; url=&quot;my_list_data.xml&quot; state=&quot;list&quot;/&gt;
 * </code>
 *
 * @constructor
 * @param {Object} options The options : {url:null,source:null}
 */
In2iGui.List = function(options) {
	this.options = n2i.override({url:null,source:null},options);
	this.element = n2i.get(options.element);
	this.name = options.name;
	if (this.options.source) {
		this.options.source.listen(this);
	}
	this.url = options.url;
	this.table = n2i.firstByTag(this.element,'table');
	this.head = n2i.firstByTag(this.element,'thead');
	this.body = n2i.firstByTag(this.element,'tbody');
	this.columns = [];
	this.rows = [];
	this.selected = [];
	this.navigation = n2i.firstByClass(this.element,'in2igui_list_navigation');
	this.count = n2i.firstByClass(this.navigation,'in2igui_list_count');
	this.windowPage = n2i.firstByClass(this.navigation,'window_page');
	this.windowPageBody = n2i.firstByClass(this.navigation,'window_page_body');
	this.parameters = {};
	this.sortKey = null;
	this.sortDirection = null;
	
	this.window = {size:null,page:0,total:0};
	if (options.windowSize!='') {
		this.window.size = parseInt(options.windowSize);
	}
	In2iGui.extend(this);
	if (this.url)  {
		this.refresh();
	}
}

/**
 * Creates a new list widget
 * @param {Object} options The options
 */
In2iGui.List.create = function(options) {
	options = n2i.override({},options);
	options.element = n2i.build('div',{
		'class':'in2igui_list',
		html: '<div class="in2igui_list_progress"></div><div class="in2igui_list_navigation"><div class="in2igui_list_selection window_page"><div><div class="window_page_body"></div></div></div><span class="in2igui_list_count"></span></div><div class="in2igui_list_body"'+(options.maxHeight>0 ? ' style="max-height: '+options.maxHeight+'px; overflow: auto;"' : '')+'><table cellspacing="0" cellpadding="0"><thead><tr></tr></thead><tbody></tbody></table></div>'});
	return new In2iGui.List(options);
}

In2iGui.List.prototype = {
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
		var items = [];
		for (var i=0; i < this.selected.length; i++) {
			items.push(this.rows[this.selected[i]]);
		};
		return items;
	},
	/** Gets the first selection or null
	 * @returns {Object} The first selected row
	 */
	getFirstSelection : function() {
		var items = this.getSelection();
		if (items.length>0) return items[0];
		else return null;
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
	 * @param {In2iGui.Source} source The source
	 */
	setSource : function(source) {
		if (this.options.source!=source) {
			if (this.options.source) {
				this.options.source.removeDelegate(this);
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
			this.options.source.removeDelegate(this);
			this.options.source=null;
		}
		this.url = url;
		this.selected = [];
		this.sortKey = null;
		this.sortDirection = null;
		this.resetState();
		this.refresh();
	},
	/** Clears the data of the list */
	clear : function() {
		this.selected = [];
		this.columns = [];
		this.rows = [];
		this.navigation.style.display='none';
		n2i.dom.clear(this.body);
		n2i.dom.clear(this.head);
		if (this.options.source) {
			this.options.source.removeDelegate(this);
		}
		this.options.source = null;
		this.url = null;
	},
	/** Resets the window state of the navigator */
	resetState : function() {
		this.window = {size:null,page:0,total:0};
		In2iGui.firePropertyChange(this,'window',this.window);
		In2iGui.firePropertyChange(this,'window.page',this.window.page);
	},
	/** @private */
	valueForProperty : function(p) {
		if (p=='window.page') return this.window.page;
		if (p=='window.page') return this.window.page;
		else if (p=='sort.key') return this.sortKey;
		else if (p=='sort.direction') return (this.sortDirection || 'ascending');
		else return this[p];
	},
	/** @private */
	$sourceShouldRefresh : function() {
		return this.element.style.display!='none';
	},
	/** @private */
	refresh : function() {
		if (this.options.source) {
			this.options.source.refresh();
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
		In2iGui.request({
			url:url,
			onJSON : function(obj) {this._setBusy(false);this.$objectsLoaded(obj)}.bind(this),
			onXML : function(obj) {this._setBusy(false);this.$listLoaded(obj)}.bind(this)
		});
	},
	/** @private */
	sort : function(index) {
		var key = this.columns[index].key;
		if (key==this.sortKey) {
			this.sortDirection = this.sortDirection=='ascending' ? 'descending' : 'ascending';
			In2iGui.firePropertyChange(this,'sort.direction',this.sortDirection);
		} else {
			In2iGui.firePropertyChange(this,'sort.key',key);
		}
		this.sortKey = key;
	},

	/** @private */
	$listLoaded : function(doc) {
		this.selected = [];
		this.parseWindow(doc);
		this.buildNavigation();
		n2i.dom.clear(this.head);
		n2i.dom.clear(this.body);
		this.rows = [];
		this.columns = [];
		var headTr = document.createElement('tr');
		var sort = doc.getElementsByTagName('sort');
		this.sortKey=null;
		this.sortDirection=null;
		if (sort.length>0) {
			this.sortKey=sort[0].getAttribute('key');
			this.sortDirection=sort[0].getAttribute('direction');
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
			if (sortable) {
				var self = this;
				th.in2iguiIndex = i;
				th.onclick=function() {self.sort(this.in2iguiIndex)};
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
			var icon = rows[i].getAttribute('icon');
			var title = rows[i].getAttribute('title');
			for (var j=0; j < cells.length; j++) {
				var td = document.createElement('td');
				if (cells[j].getAttribute('wrap')=='false') {
					td.style.whiteSpace='nowrap';
				}
				this.parseCell(cells[j],td);
				row.appendChild(td);
				if (!title) {
					title = n2i.dom.getText(cells[j]);
				}
				if (!icon) {
					icon = cells[j].getAttribute('icon');
				}
			};
			var info = {id:rows[i].getAttribute('id'),kind:rows[i].getAttribute('kind'),icon:icon,title:title,index:i};
			row.dragDropInfo = info;
			this.addRowBehavior(row,i);
			frag.appendChild(row);
			this.rows.push(info);
		};
		this.body.appendChild(frag);
		this.fire('selectionReset');
	},
	
	/** @private */
	$objectsLoaded : function(data) {
		if (data==null) {
			// NOOP
		} else if (data.constructor == Array) {
			this.setObjects(data);
		} else {
			this.setData(data);
		}
		this.fire('selectionReset');
	},
	/** @private */
	$sourceIsBusy : function() {
		this._setBusy(true);
	},
	/** @private */
	$sourceIsNotBusy : function() {
		this._setBusy(false);
	},
	
	_setBusy : function(busy) {
		this.busy = busy;
		window.clearTimeout(this.busytimer);
		if (busy) {
			var e = this.element;
			this.busytimer = window.setTimeout(function() {
				n2i.addClass(e,'in2igui_list_busy');
			},300);
		} else {
			n2i.removeClass(this.element,'in2igui_list_busy');
		}
	},
	
	/** @private 
	filter : function(str) {
		var len = 20;
		var regex = new RegExp("[\\w]{"+len+",}","g");
		var match = regex.exec(str);
		if (match) {
			for (var i=0; i < match.length; i++) {
				var rep = '';
				for (var j=0; j < match[i].length; j++) {
					rep+=match[i][j];
					if ((j+1)%len==0) rep+='\u200B';
				};
				str = str.replace(match[i],rep);
			};
		}
		return str;
	},*/
	
	/** @private */
	parseCell : function(node,cell) {
		var icon = node.getAttribute('icon');
		if (icon!=null && icon!='') {
			cell.appendChild(In2iGui.createIcon(icon,1));
		}
		for (var i=0; i < node.childNodes.length; i++) {
			var child = node.childNodes[i];
			if (n2i.dom.isDefinedText(child)) {
				n2i.dom.addText(cell,child.nodeValue);
			} else if (n2i.dom.isElement(child,'break')) {
				cell.appendChild(document.createElement('br'));
			} else if (n2i.dom.isElement(child,'icon')) {
				cell.appendChild(In2iGui.createIcon(child.getAttribute('icon'),1));
			} else if (n2i.dom.isElement(child,'line')) {
				var line = n2i.build('p',{'class':'in2igui_list_line'});
				if (child.getAttribute('dimmed')=='true') {
					n2i.addClass(line,'in2igui_list_dimmed')
				}
				cell.appendChild(line);
				this.parseCell(child,line);
			} else if (n2i.dom.isElement(child,'object')) {
				var obj = n2i.build('div',{'class':'object'});
				if (child.getAttribute('icon')) {
					obj.appendChild(In2iGui.createIcon(child.getAttribute('icon'),1));
				}
				if (child.firstChild && child.firstChild.nodeType==n2i.TEXT_NODE && child.firstChild.nodeValue.length>0) {
					n2i.dom.addText(obj,child.firstChild.nodeValue);
				}
				cell.appendChild(obj);
			} else if (n2i.dom.isElement(child,'icons')) {
				var icons = n2i.build('span',{'class':'in2igui_list_icons'});
				this.parseCell(child,icons);
				cell.appendChild(icons);
			} else if (n2i.dom.isElement(child,'button')) {
				var button = In2iGui.Button.create({text:child.getAttribute('text'),small:true,rounded:true});
				button.click(function() {
					this._buttonClick(button);
				}.bind(this))
				cell.appendChild(button.getElement());
			}
		};
	},
	_buttonClick : function(button) {
		var row = n2i.firstParentByTag(button.getElement(),'tr');
		var obj = this.rows[parseInt(row.getAttribute('data-index'),10)];
		this.fire('buttonClick',obj,button);
	},
	/** @private */
	parseWindow : function(doc) {
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
	/** @private */
	buildNavigation : function() {
		var self = this;
		var pages = this.window.size>0 ? Math.ceil(this.window.total/this.window.size) : 0;
		if (pages<2) {
			this.navigation.style.display='none';
			return;
		}
		this.navigation.style.display='block';
		var from = ((this.window.page)*this.window.size+1);
		n2i.dom.setText(this.count,(from+'-'+Math.min((this.window.page+1)*this.window.size,this.window.total)+' / '+this.window.total));
		var pageBody = this.windowPageBody;
		pageBody.innerHTML='';
		if (pages<2) {
			this.windowPage.style.display='none';	
		} else {
			var indices = this.buildPages(pages,this.window.page);
			for (var i=0; i < indices.length; i++) {
				var index = indices[i];
				if (index==='') {
					pageBody.appendChild(n2i.build('span',{text:'·'}));
				} else {
					var a = document.createElement('a');
					a.appendChild(document.createTextNode(index+1));
					a.setAttribute('data-index',index);
					a.onmousedown = function() {
						self.windowPageWasClicked(this);
						return false;
					}
					if (index==self.window.page) {
						a.className='selected';
					}
					pageBody.appendChild(a);
				}
			}
			this.windowPage.style.display='block';
		}
	},
	/** @private */
	buildPages : function(count,selected) {
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
		var win = data.window || {};
		this.window.total = win.total || 0;
		this.window.size = win.size || 0;
		this.window.page = win.page || 0;
		this.buildNavigation();
		this.buildHeaders(data.headers);
		this.buildRows(data.rows);
	},
	/** @private */
	buildHeaders : function(headers) {
		n2i.dom.clear(this.head);
		this.columns = [];
		var tr = n2i.build('tr',{parent:this.head});
		n2i.each(headers,function(h,i) {
			var th = n2i.build('th');
			if (h.width) {
				th.style.width = h.width+'%';
			}
			if (h.sortable) {
				n2i.listen(th,'click',function() {this.sort(i)}.bind(this));
				n2i.addClass(th,'sortable');
			}
			th.appendChild(n2i.build('span',{text:h.title}));
			tr.appendChild(th);
			this.columns.push(h);
		}.bind(this));
	},
	/** @private */
	buildRows : function(rows) {
		var self = this;
		n2i.dom.clear(this.body);
		this.rows = [];
		if (!rows) return;
		n2i.each(rows,function(r,i) {
			var tr = n2i.build('tr');
			var icon = r.icon;
			var title = r.title;
			n2i.each(r.cells,function(c) {
				var td = n2i.build('td');
				if (c.icon) {
					td.appendChild(In2iGui.createIcon(c.icon,1));
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
			self.rows.push({id:r.id,kind:r.kind,icon:icon,title:title,index:i,data:r.data});
			this.addRowBehavior(tr,i);
		}.bind(this));
	},
	/** @private */
	setObjects : function(objects) {
		this.selected = [];
		n2i.dom.clear(this.body);
		this.rows = [];
		for (var i=0; i < objects.length; i++) {
			var row = n2i.build('tr');
			var obj = objects[i];
			var title = null;
			for (var j=0; j < this.columns.length; j++) {
				var cell = n2i.build('td');
				if (this.builder) {
					cell.appendChild(this.builder.buildColumn(this.columns[j],obj));
				} else {
					var value = obj[this.columns[j].key] || '';
					if (n2i.isArray(value)) {
						for (var k=0; k < value.length; k++) {
							if (value[k].constructor == Object) {
								cell.appendChild(this.createObject(value[k]));
							} else {
								cell.appendChild(n2i.build('div',{text:value}));
							}
						};
					} else if (value.constructor == Object) {
						cell.appendChild(this.createObject(value[j]));
					} else {
						n2i.dom.addText(cell,value);
						title = title==null ? value : title;
					}
				}
				row.appendChild(cell);
			};
			var info = {id:obj.id,kind:obj.kind,title:title};
			row.dragDropInfo = info;
			this.body.appendChild(row);
			this.addRowBehavior(row,i);
			this.rows.push(obj);
		};
	},
	/** @private */
	createObject : function(object) {
		var node = n2i.build('div',{'class':'object'});
		if (object.icon) {
			node.appendChild(In2iGui.createIcon(object.icon,1));
		}
		n2i.dom.addText(node,object.text || object.name || '')
		return node;
	},
	/** @private */
	addRowBehavior : function(row,index) {
		var self = this;
		row.onmousedown = function(e) {
			if (self.busy) {return};
			self.rowDown(index);
			In2iGui.startDrag(e,row);
			return false;
		}
		row.ondblclick = function() {
			if (self.busy) {return};
			self.rowDoubleClick(index);
			return false;
		}
	},
	/** @private */
	changeSelection : function(indexes) {
		var rows = this.body.getElementsByTagName('tr'),
			i;
		for (i=0;i<this.selected.length;i++) {
			n2i.removeClass(rows[this.selected[i]],'selected');
		}
		for (i=0;i<indexes.length;i++) {
			n2i.addClass(rows[indexes[i]],'selected');
		}
		this.selected = indexes;
		this.fire('selectionChanged',this.rows[indexes[0]]);
	},
	/** @private */
	rowDown : function(index) {
		this.changeSelection([index]);
	},
	/** @private */
	rowDoubleClick : function(index) {
		this.fire('listRowWasOpened',this.getFirstSelection());
	},
	/** @private */
	windowPageWasClicked : function(tag) {
		var index = parseInt(tag.getAttribute('data-index'));
		this.window.page = index;
		In2iGui.firePropertyChange(this,'window',this.window);
		In2iGui.firePropertyChange(this,'window.page',this.window.page);
	}
};

/* EOF */