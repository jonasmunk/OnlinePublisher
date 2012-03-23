op.part.File = {
	$ready : function() {
		this.buildWindow();
		this.showFinder();
	},
	showFinder : function() {
		var finder = hui.ui.Finder.create({
			title : 'Vælg fil',
			listUrl : '../../Services/Model/ListFiles.php?windowSize=10',
			selectionUrl : '../../Tools/Files/GroupItems.php'
		});
		finder.listen({
			$select : function(obj) {
				document.forms.PartForm.fileId.value=obj.id;
				this.preview();
			}.bind(this)
		})
		finder.show();
	},
	buildWindow : function() {
		var form = document.forms.PartForm;
		var win = hui.ui.Window.create({title:'Vælg fil',width:400,close:false});
		var toolbar = hui.ui.Toolbar.create({labels:false});
		var searchField = hui.ui.SearchField.create({name:'search',adaptive:true});
		toolbar.add(searchField);
		win.add(toolbar);
		var list = hui.ui.List.create({name:'list',maxHeight:300});
		list.listen({$selectionChanged:function(obj) {
			document.forms.PartForm.fileId.value=obj.id;
			this.preview();
		}.bind(this)})
		searchField.listen({$valueChanged:function() {list.resetState()}});
		win.add(list);
		win.show();
		var src = new hui.ui.Source({
			url:'../../Services/Model/ListFiles.php?windowSize=10',
			parameters:[
				{key:'query',value:'@search.value'},
				{key:'windowPage',value:'@list.window.page'},
				{key:'direction',value:'@list.sort.direction'},
				{key:'sort',value:'@list.sort.key'}
			]
		});
		list.setSource(src);
	},
	preview : function() {
		op.part.utils.updatePreview({
			node : 'part_file_container',
			form : document.forms.PartForm,
			type : 'file'
		});
	}
}

hui.ui.listen(op.part.File);