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
			this.window = hui.ui.Window.create({title:this.options.title,width:500});
			var columns = hui.ui.Columns.create();
			this.window.add(columns);
			var left = hui.ui.Overflow.create({height:400});
			columns.addToColumn(0,left);
			var right = hui.ui.Overflow.create({height:400});
			columns.addToColumn(1,right);
			columns.setColumnWidth(0,160);
			
			this.list = hui.ui.List.create();
			this.list.setUrl(this.options.listUrl);
			right.add(this.list);
		}
		this.window.show();
	}
}

