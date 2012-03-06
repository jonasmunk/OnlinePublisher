////////////////////////// Finder ///////////////////////////

/**
 * A "finder" for finding objects
 * @constructor
 */
hui.ui.Finder = function(options) {
	this.options = hui.override({title:'Finder',close:true},options);
	hui.ui.extend(this);
}

hui.ui.Finder.create = function(options) {
	return new hui.ui.Finder(options);
}

hui.ui.Finder.prototype = {
	show : function() {
		if (!this.window) {
			this._build();
		}
		this.window.show();
	},
	
	_build : function() {
		this.window = hui.ui.Window.create({title:this.options.title,width:500});

		var layout = hui.ui.Layout.create();
		this.window.add(layout);

		//var columns = hui.ui.Columns.create();
		//this.window.add(columns);
		var left = hui.ui.Overflow.create({height:400});
		//columns.addToColumn(0,left);
		layout.addToLeft(left);
		var right = hui.ui.Overflow.create({height:400});
		layout.addToCenter(right);
		//columns.addToColumn(1,right);
		//columns.setColumnWidth(0,160);
		
		this.list = hui.ui.List.create({url:this.options.listUrl});
		this.list.listen({
			$listRowWasOpened : function(row) {
				alert(0)
			},
			
			$selectionChanged : this._selectionChanged.bind(this)
		})
		right.add(this.list);
		
		this.selection = hui.ui.Selection.create();
		var src = new hui.ui.Source({url : this.options.selectionUrl});
		this.selection.addItems({source:src})
		left.add(this.selection);
		
		src.refresh();
	},
	
	_selectionChanged : function() {
		var row = this.list.getFirstSelection();
		this.fire('select',row);
	}
}

