var partController = {
	$ready : function() {
		//this.buildWindow();
	},
	buildWindow : function() {
		var form = document.forms.PartForm;
		var win = In2iGui.Window.create({title:'Billedgalleri',width:300,close:false,padding:5,variant:'dark'});
		var formula = In2iGui.Formula.create({name:'formula'});
		formula.buildGroup(null,[
			{type:'DropDown',options:{name:'group',label:'Billedgruppe:',url:'../../Services/Model/Items.php?type=imagegroup',value:form.group.value}},
			{type:'Number',options:{label:'Højde:',name:'height',value:parseInt(form.height.value,10),min:10,max:512}},
			{type:'Checkbox',options:{label:'Indrammet',key:'framed',value:form.framed.value=='true'}},
			{type:'Checkbox',options:{label:'Vis titel',key:'showTitle',value:form.showTitle.value=='true'}}
		]);
		win.add(formula);
		win.show();
	},
	$valueChanged$group : function(value) {
		this.preview();
	},
	$valuesChanged$formula : function(values) {
		document.forms.PartForm.height.value = values.height;
		document.forms.PartForm.group.value = values.group;
		document.forms.PartForm.framed.value = values.framed;
		document.forms.PartForm.showTitle.value = values.showTitle;
		this.preview();
	},
	preview : function() {
		var url = controller.context+'Editor/Services/Parts/Preview.php?type=imagegallery';
		var parms = $(document.forms.PartForm).serialize(true);
		new Ajax.Request(url,{parameters:parms,onSuccess:function(t) {
			$('part_imagegallery_container').update(t.responseText);
		}});
	}
}

In2iGui.listen(partController);