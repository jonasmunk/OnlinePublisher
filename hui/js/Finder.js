////////////////////////// Finder ///////////////////////////

/**
 * A "finder" for finding objects
 * @constructor
 */
hui.ui.Finder = function(options) {
	this.options = hui.override({title:'Finder'},options);
	hui.ui.extend(this);
}

/**
 * Creates a new finder
 * <pre><strong>options:</strong> {
 *  title : «String»,
 *  selection : {
 *      value : «String»,
 *      url : «String»,
 *      parameter : «String»,
 *      kindParameter : «String»
 *  },
 *  list : { 
 *      url : «String» 
 *  },
 *  search : { 
 *      parameter : «String» 
 *  }
 * }
 * </pre>
 */
hui.ui.Finder.create = function(options) {
	return new hui.ui.Finder(options);
}

hui.ui.Finder.prototype = {
	/** Shows the finder */
	show : function() {
		if (!this.window) {
			this._build();
		}
		this.window.show();
	},
	
	_build : function() {
		var win = this.window = hui.ui.Window.create({title:this.options.title,width:600});

		
		var layout = hui.ui.Layout.create();
		win.add(layout);

		var left = hui.ui.Overflow.create({height:400});
		layout.addToLeft(left);
		
		if (this.options.search) {
			var bar = hui.ui.Bar.create({variant:'layout'});
			var search = hui.ui.SearchField.create({expandedWidth:200});
			bar.addToRight(search);
			layout.addToCenter(bar);
		}
		
		

		var right = hui.ui.Overflow.create({height:400});
		layout.addToCenter(right);
		
		
		var list = this.list = hui.ui.List.create();
		
		this.list.listen({
			$open : function(row) {
				
			},
			
			$select : this._selectionChanged.bind(this)
		})
		right.add(this.list);
		
		this.selection = hui.ui.Selection.create({value : this.options.selection.value});
		var src = new hui.ui.Source({url : this.options.selection.url});
		this.selection.addItems({source:src})
		left.add(this.selection);
		
		var parameters = [
			{key:'windowSize',value:10},
			{key:'windowPage',value:'@'+list.name+'.window.page'},
			{key:'direction',value:'@'+list.name+'.sort.direction'},
			{key:'sort',value:'@'+list.name+'.sort.key'}
		];
		
		if (this.options.selection.parameter) {
			parameters.push({key:this.options.selection.parameter || 'text',value:'@'+this.selection.name+'.value'})
		}
		if (this.options.selection.kindParameter) {
			parameters.push({key:this.options.selection.kindParameter || 'text',value:'@'+this.selection.name+'.kind'})
		}
		
		if (this.options.search) {
			parameters.push({key:this.options.search.parameter || 'text',value:'@'+search.name+'.value'})
		}
		
		var listSource = new hui.ui.Source({
			url : this.options.list.url,
			parameters : parameters
		});
		this.list.setSource(listSource);
		
		src.refresh();
	},
	
	_selectionChanged : function() {
		var row = this.list.getFirstSelection();
		this.fire('select',row);
	}
}

