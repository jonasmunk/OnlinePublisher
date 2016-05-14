var partController = {
	$ready : function() {
		this.buildWindow();
	},
	buildWindow : function() {
		var form = document.forms.PartForm,
			win = hui.ui.Window.create({title:{en:'Select person',da:'Vælg person'},width:400,close:false}),
			toolbar = hui.ui.Toolbar.create({labels:false}),
			searchField = hui.ui.SearchField.create({name:'search',adaptive:true}),
			list = hui.ui.List.create({name:'list',maxHeight:300});
		toolbar.add(searchField);
		win.add(toolbar);
		list.listen({
			$select : function() {
				var obj = list.getFirstSelection();
				if (obj) {
					document.forms.PartForm.personId.value = obj.id;
					partController.preview();
				}
			}
		});
		searchField.listen({
			$valueChanged : function() {list.resetState()}
		});
		win.add(list);
		win.show();
		var src = new hui.ui.Source({
			url : '../../Services/Model/ListPersons.php?windowSize=10',
			parameters : [
				{key:'query',value:'@search.value'},
				{key:'windowPage',value:'@list.window.page'},
				{key:'direction',value:'@list.sort.direction'},
				{key:'sort',value:'@list.sort.key'}
			]
		});
		list.setSource(src);
	},
	preview : function() {
		hui.log('preview')
		op.part.utils.updatePreview({
			node : 'part_person_container',
			form : document.forms.PartForm,
			type : 'person'
		});
	}
}

hui.ui.listen(partController);