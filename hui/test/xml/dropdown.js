var controller = {
	$click$showValues : function() {
		var v = formula.getValues();
		alert(hui.string.toJSON(v));
	},
	$click$build : function() {
		var win = hui.ui.Window.create({width:300,padding:10});
		var form = hui.ui.Formula.create();
		win.add(form);
		var group = form.createGroup();
		var drop = hui.ui.DropDown.create({label:'Data from url',url:'data/items_loremipsum.xml'});
		group.add(drop);
		var drop2 = hui.ui.DropDown.create({label:'Data from source',source:itemsSource});
		group.add(drop2);
		var drop2 = hui.ui.DropDown.create({label:'Added items',items:[
			{value:1,title:'A'},
			{value:2,title:'B'},
			{value:3,title:'C'}
		],value:2});
		group.add(drop2);
		win.show();
		itemsSource.refresh();
	}
}