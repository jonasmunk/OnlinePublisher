op.part.List = {
	objects : null,
	objectOptions : [],
	setData : function(data) {
		this.data = data;
	},
	$interfaceIsReady : function() {
		this.buildWindow();
	},
	buildWindow : function() {
		var form = $(document.forms.PartForm);
		var win = ui.Window.create({title:'Liste',width:300,close:false});
		var tabs = ui.Tabs.create({small:true,centered:true});
		win.add(tabs);
		var settings = tabs.createTab({title:'Settings',padding:5});
		var formula = ui.Formula.create({name:'formula'});
		formula.buildGroup(null,[
			{type:'Text',options:{label:'Titel:',key:'title',value:form.title.value,min:10,max:512}},
			{type:'Number',options:{key:'time_count',label:'Dage:',value:parseInt(form.time_count.value,10)}},
			{type:'Number',options:{key:'maxitems',label:'Maksimalt antal:',value:parseInt(form.maxitems.value,10)}},
			{type:'Checkbox',options:{key:'show_source',label:'Vis kilde:',value:form.show_source.value=='true'}}/*,
			{type:'Checkboxes',options:{key:'objects',label:'Kilder:',vertical:true,items:this.objectOptions,value:this.objects}}*/
		]);
		settings.add(formula);

		// Data tab
		
		var dataTab = tabs.createTab({title:'Data',padding:5});
		var overflow = ui.Overflow.create({height:200});
		dataTab.add(overflow);
		var dataForm = ui.Formula.create({name:'dataFormula'});
		dataForm.buildGroup({labels:'above'},[
			{type:'Checkboxes',options:{key:'newsgroup',label:'Nyhedsgrupper:',vertical:true,items:this.data.newsgroupOptions,value:this.data.newsgroupValues}},
			{type:'Checkboxes',options:{key:'calendar',label:'Kalendere:',vertical:true,items:this.data.calendarOptions,value:this.data.calendarValues}},
			{type:'Checkboxes',options:{key:'calendarsource',label:'Kalenderkilder:',vertical:true,items:this.data.calendarsourceOptions,value:this.data.calendarsourceValues}}
		]);
		overflow.add(dataForm);
		n2i.log(dataForm.getValues());
		win.show();
	},
	$valueChanged$group : function(value) {
		this.preview();
	},
	$valuesChanged$formula : function(values) {
		document.forms.PartForm.title.value = values.title;
		document.forms.PartForm.time_count.value = values.time_count;
		document.forms.PartForm.maxitems.value = values.maxitems;
		document.forms.PartForm.show_source.value = values.show_source;
		this.preview();
	},
	$valuesChanged$dataFormula : function(values) {
		var objects = [values.newsgroup,values.calendarsource,values.calendar].flatten();
		document.forms.PartForm.objects.value = objects.join(',');
		this.preview();
	},
	preview : function() {
		op.part.utils.updatePreview({
			node:$('part_list_container'),
			form:$(document.forms.PartForm),
			type:'list'
		});
	}
}

ui.listen(op.part.List);