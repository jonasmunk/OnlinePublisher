var partController = {
	$ready : function() {
		var container = this.container = hui.get('part_table');
		this.base = hui.get.firstByClass(container,'part_table') || container;
		var table = this._getTable();
		table.setAttribute('contenteditable','true');
		hui.listen(this.base,'keyup',function() {
			this._checkMarkup();
			this._syncValue();
			this._syncSource();
		}.bind(this));
		hui.listen(this.base,'contextmenu',this._onMenu.bind(this));
		this._syncInfo();
		this.showInfo();
	},
	
	
	_onMenu : function(e) {
		hui.selection.enable(false);
		hui.listenOnce(document.body,'mouseup',function() {
			hui.selection.enable(true);
		}.bind(this));
		e = hui.event(e);
		
		if (this.selectedCell) {
			this.selectedCell.style.border='';
		}
		
		var td = e.findByTag('td');
		if (td && !hui.cls.has(td,'editor_column')) {
			this.selectedCell = td;
			td.style.border='1px solid red';
		
			if (!this.menu) {
				this.menu = hui.ui.Menu.create({name:'tableMenu'});
				this.menu.addItems([
					{title:'Slet række',value:'deleteRow'},
					{title:'Flyt op',value:'moveUp'},
					{title:'Flyt ned',value:'moveDown'},
					null,
					{title:'Slet kolonne',value:'deleteColumn'},
					{title:'Flyt til venstre',value:'moveLeft'},
					{title:'Flyt til højre',value:'moveRight'}
				]);
			}
			this.menu.showAtPointer(e);
		}
	},
	$hide$tableMenu : function() {
		if (this.selectedCell) {
			this.selectedCell.style.border='';
		}
	},
	
	_setSelect : function(on) {
		hui.log('Set selection: '+on);
		document.onselectstart = on ? null : function () { return false; };
		document.body.style.webkitUserSelect = on ? null : 'none';
	},
	
	$select$tableMenu : function(value) {
		if (value === 'deleteRow') {
			var tr = hui.get.firstParentByTag(this.selectedCell, 'tr');
			if (tr) {
				hui.dom.remove(tr);
			}
		} else if (value == 'deleteColumn') {
			var index = hui.get.before(this.selectedCell).length;
			var table = this._getTable();
			var trs = table.getElementsByTagName('tr');
			for (var i=0; i < trs.length; i++) {
				var tdhs = hui.get.children(trs[i]);
				if (tdhs.length>index) {
					hui.dom.remove(tdhs[index]);
				}
			};
		} else if (value == 'moveRight') {
			var index = hui.get.before(this.selectedCell).length;
			var table = this._getTable();
			var trs = table.getElementsByTagName('tr');
			for (var i=0; i < trs.length; i++) {
				var tdhs = hui.get.children(trs[i]);
				if (tdhs.length>index) {
					var cell = tdhs[index];
					var next = hui.get.next(cell);
					if (next) {
						hui.dom.remove(next);
						hui.dom.insertBefore(cell,next);
					}
				}
			};
		} else if (value == 'moveLeft') {
			var index = hui.get.before(this.selectedCell).length;
			var table = this._getTable();
			var trs = table.getElementsByTagName('tr');
			for (var i=0; i < trs.length; i++) {
				var tdhs = hui.get.children(trs[i]);
				if (tdhs.length>index) {
					var cell = tdhs[index];
					var previous = hui.get.previous(cell);
					if (previous) {
						hui.dom.remove(cell);
						hui.dom.insertBefore(previous,cell);
					}
				}
			};
		} else if (value == 'moveUp') {
			var tr = hui.get.firstParentByTag(this.selectedCell, 'tr');
			if (tr) {
				var previous = hui.get.previous(tr);
				if (previous) {
					hui.dom.remove(tr);
					hui.dom.insertBefore(previous,tr);
				}
			}
		} else if (value == 'moveDown') {
			var tr = hui.get.firstParentByTag(this.selectedCell, 'tr');
			if (tr) {
				var next = hui.get.next(tr);
				if (next) {
					hui.dom.remove(next);
					hui.dom.insertBefore(tr,next);
				}
			}
		}
		this._syncValue();
		this._syncSource();
	},
	_checkMarkup : function() {
		var children = hui.get.children(this.base);
		if (children.length!==1) {
			this._rebuildMarkup();
		} else if (children[0].nodeName.toLowerCase()!=='table') {
			this._rebuildMarkup();
		}
	},
	_rebuildMarkup : function() {
		var found = hui.get.firstByTag(this.base,'table');
		if (found) {
			found.parentNode.removeChild(found);
			this.base.innerHTML = '';
			this.base.appendChild(found);
		} else {
			this.base.innerHTML = '';
			var table = hui.build('table',{parent:this.base});
			hui.build('tbody',{parent:table});
		}
	},
	_getTable : function() {
		var found = hui.get.firstByTag(this.base,'table');
		if (!found) {
			hui.log('Adding table');
			found = hui.build('table',{parent:this.base,html:'<tbody><tr><td>Cell</td></tr></tbody>'});
			found.setAttribute('contenteditable','true');
		}
		return found;
	},
	clean : function() {
		var table = this._getTable();
		if (!table) {return}
		var nodes = this.container.getElementsByTagName('*');
		for (var i = nodes.length - 1; i >= 0; i--){
			nodes[i].removeAttribute('style');
		};
		var nodes = this.container.getElementsByTagName('td');
		for (var i = nodes.length - 1; i >= 0; i--) {
			hui.dom.setText(nodes[i],hui.dom.getText(nodes[i]));
		};
		hui.ui.showMessage({text:'Your royalty is now clean!',duration:3000});
		this._syncValue();
		this._syncSource();
		this._syncInfo();
	},
	addRow : function() {
		var table = this._getTable();
		hui.table.addRow(table);
		this._syncValue();
		this._syncSource();
	},
	addColumn : function() {
		var table = this._getTable();
		hui.table.addColumn(table);
		this._syncValue();
		this._syncSource();
	},
	showInfo : function() {
		propertiesWindow.show();
	},
	_syncSource : function() {
		var html = this.base.innerHTML;
		html = html.replace(/\Wcontenteditable="true"/g, "");
		sourceFormula.setValues({source:html});
	},
	_syncInfo : function() {
		var table = this._getTable();
		propertiesFormula.setValues({
			width : table.style.width,
			head : hui.table.getRowCount(table,'thead'),
			foot : hui.table.getRowCount(table,'tfoot')
		});
	},
	_syncValue : function() {
		document.forms.PartForm.html.value = this.base.innerHTML;
		var table = this._getTable();
		table.setAttribute('contenteditable','true');
	},
	$valueChanged$tableHead : function(count) {
		this._changePart('thead',count);
	},
	$valueChanged$tableFoot : function(count) {
		this._changePart('tfoot',count);
	},
	_changePart : function(tag,count) {	
		var table = this._getTable();
		var head = hui.get.firstByTag(table,tag);
		var headCount = 0;
		if (!head) {
			head = hui.build(tag,{parent:table});
		}
		var trs = hui.get.byTag(head,'tr');
		if (trs.length>count) {
			for (var i=0; i < trs.length; i++) {
				if (i>=count) {
					hui.dom.remove(trs[i]);
				}
			};
		}
		var missing = count - trs.length;
		if (missing > 0) {
			var firstTr = hui.get.firstByTag(table,'tr');
			var cols = hui.get.children(firstTr).length;
			for (var i=0; i < missing; i++) {
				var tr = hui.build('tr',{parent:head});
				for (var j=0; j < cols; j++) {
					hui.build('th',{parent:tr,text:tag=='thead' ? 'Header' : 'Footer'});
				};
			}
		}
		this._syncSource();
		this._syncValue();
	},
	
	// Source...
	
	editSource : function() {
		sourceWindow.show();
		this._syncSource();
	},
	$valuesChanged$sourceFormula : function(values) {
		this.base.innerHTML = values.source;
		this._syncValue();
		this._syncInfo();
	},
	
	
	// Info...
	
	$valuesChanged$propertiesFormula : function(values) {
		var table = this._getTable();
		table.style.width = values.width;
		this._syncSource();
		this._syncValue();
	}
	
};

hui.table = {
	getRowCount : function(table,part) {
		var head = hui.get.firstByTag(table,part);
		var headCount = 0;
		if (head) {
			headCount = hui.get.byTag(head,'tr').length;
		}
		return headCount;
	},
	addRow : function(table) {
		var trs = hui.get.byTag(table,'tr');
		if (trs.length>0) {
			var last = trs[trs.length-1];
			var tr = hui.build('tr');
			hui.dom.insertAfter(last,tr);
			var cells = hui.get.children(last);
			for (var i=0; i < cells.length; i++) {
				hui.build(cells[i].nodeName,{parent:tr,html:cells[i].innerHTML});
			};
		} else {
			var body = hui.get.firstByTag(table,'tbody') || table;
			hui.build('tr',{parent:body,html:'<td>Cell</td>'});
		}
	},
	addColumn : function(table) {
		var trs = hui.get.byTag(table,'tr');
		if (trs.length==0) {
			var body = hui.get.firstByTag(table,'tbody') || table;
			hui.build('tr',{parent:body,html:'<td>Cell</td>'});
			return;
		}
		for (var i=0; i < trs.length; i++) {
			var tr = trs[i];
			var cells = hui.get.children(tr);
			var last = cells[cells.length-1];
			hui.build(last.nodeName,{parent:tr,html:last.innerHTML});
		};
	}
}

hui.ui.listen(partController);