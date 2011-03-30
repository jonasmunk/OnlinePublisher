ui.listen({
	
	dragDrop : [
		{drag:'news',drop:'newsgroup'}
	],
	uploadWindow : null,
	groupId : null,
	fileId : null,
	
	$selectionChanged$list : function(item) {
		if (item.kind=='news') {
			ui.get('delete').setEnabled(true);
			ui.get('info').setEnabled(true);
			ui.get('duplicate').setEnabled(true);
		}
	},
	$selectionReset$list : function() {
		ui.get('delete').setEnabled(false);
		ui.get('info').setEnabled(false);
		ui.get('duplicate').setEnabled(false);
	},
	$listRowWasOpened$list : function(obj) {
		this.loadNews(obj.id);
	},
	
	$valueChanged$search : function() {
		list.resetState();
	},
	
	$selectionChanged$selector : function() {
		list.resetState();
	},
	
	//////////////////////// Dragging ////////////////////////
	
	$drop$news$newsgroup : function(dragged,dropped) {
		In2iGui.request({url:'AddNewsToGroup.php',onSuccess:'newsMoved',json:{data:{file:dragged.id,group:dropped.value}}});
	},
	$success$newsMoved : function() {
		newsSource.refresh();
		groupSource.refresh();
	},
	
	//////////////////////// Actions /////////////////////////
	
	$click$delete : function() {
		var obj = list.getFirstSelection();
		if (obj.id===this.newsId) {
			this.closeNewsAfterDeletion = true;
		}
		ui.request({
			url:'DeleteNews.php',
			onSuccess:'fileDeleted',
			parameters:{id:obj.id},
			message : {start:'Sletter nyhed...',delay:300}
		});
	},
	$success$fileDeleted : function(response) {
		if (this.closeNewsAfterDeletion) {
			this.$click$cancelNews();
		}
		this.closeNewsAfterDeletion = false;
		newsSource.refresh();
		groupSource.refresh();
	},
	$click$info : function() {
		var obj = list.getFirstSelection();
		this.loadNews(obj.id);
	},
	
	//////////////////////// Properties //////////////////////
	
	
	$click$duplicate : function(id) {
		var obj = list.getFirstSelection();
		ui.request({
			url : 'LoadNews.php',
			onSuccess : 'duplicateLoaded',
			parameters : {id:obj.id},
			message : {start:'Opretter kopi...',delay:300}
		});
	},
	$success$duplicateLoaded : function(data) {
		this.newsId = null;
		newsFormula.setValues(data.news);
		newsGroups.setValue(data.groups);
		newsLinks.setValue(data.links);
		deleteNews.disable();
		newsWindow.show();
		newsFormula.focus();
	},
	$click$newNews : function() {
		this.newsId = null;
		newsFormula.reset();
		var selection = selector.getValue();
		if (selection.kind=='newsgroup') {
			newsGroups.setValue([selection.value]);
		}
		newsLinks.reset();
		deleteNews.disable();
		newsWindow.show();
		newsFormula.focus();
	},
	
	loadNews : function(id) {
		ui.request({
			url:'LoadNews.php',
			onSuccess:'newsLoaded',
			parameters:{id:id},
			message:{start:'Åbner nyhed...',delay:300}
		});
	},
	
	$success$newsLoaded : function(data) {
		this.newsId = data.news.id;
		newsFormula.setValues(data.news);
		newsGroups.setValue(data.groups);
		newsLinks.setValue(data.links);
		deleteNews.enable();
		newsWindow.show();
		newsFormula.focus();
	},
	$click$cancelNews : function() {
		this.newsId = null;
		newsFormula.reset();
		newsWindow.hide();
	},
	$click$updateNews : function() {
		this.$submit$newsFormula();
	},
	$submit$newsFormula : function() {
		var data = newsFormula.getValues();
		data.id = this.newsId;
		data.links = newsLinks.getValue();
		
		if (data.startdate) {
			data.startdate=Math.round(data.startdate.getTime()/1000);
		}
		if (data.enddate) {
			data.enddate=Math.round(data.enddate.getTime()/1000);
		}
		data.groups = newsGroups.getValue();
		ui.request({
			url:'SaveNews.php',
			onSuccess:'newsUpdated',
			json:{data:data},
			message:{start:'Gemmer nyhed...',delay:300}
		});
	},
	$success$newsUpdated : function() {
		this.newsId = null;
		newsFormula.reset();
		newsWindow.hide();
		newsSource.refresh();
		groupSource.refresh();
	},
	$click$deleteNews : function() {
		ui.request({
			url : 'DeleteNews.php',
			onSuccess : 'newsDeleted',
			parameters : {id:this.newsId},
			message : {start:'Sletter nyhed...',delay:300}
		});
	},
	$success$newsDeleted : function() {
		this.newsId = null;
		newsFormula.reset();
		newsWindow.hide();
		newsSource.refresh();
		groupSource.refresh();
	},
	
	////////////////////////// Group /////////////////////////
	
	$click$newGroup : function() {
		this.groupId = null;
		deleteGroup.setEnabled(false);
		groupFormula.reset();
		groupWindow.show();
		groupFormula.focus();
	},
	$click$cancelGroup : function() {
		this.groupId = null;
		groupFormula.reset();
		groupWindow.hide();
	},
	$click$saveGroup : function() {
		var values = groupFormula.getValues();
		if (n2i.isBlank(values.title)) {
			ui.showMessage({text:'Du skal angive en titel',duration:2000});
			groupFormula.focus();
		} else {
			values.id = this.groupId;
			ui.request({
				json:{data:values},
				url:'SaveGroup.php',
				onSuccess:'groupSaved',
				message:{start:'Gemmer gruppe...',delay:300}
			});
		}
	},
	$submit$groupFormula : function() {
		this.$click$saveGroup();
	},
	$success$groupSaved : function() {
		groupSource.refresh();
		this.groupId = null;
		groupFormula.reset();
		groupWindow.hide();
	},
	$selectionWasOpened$selector : function(item) {
		if (item.kind=='newsgroup') {
			ui.request({
				parameters:{id:item.value},
				url:'../../Services/Model/LoadObject.php',
				onSuccess:'loadGroup',
				message:{start:'Åbner gruppe...',delay:300}
			});
		}
	},
	$success$loadGroup : function(data) {
		this.groupId = data.id;
		groupFormula.setValues(data);
		deleteGroup.setEnabled(true);
		groupWindow.show();
		groupFormula.focus();
	},
	$click$deleteGroup : function() {
		ui.request({
			json : {data:{id:this.groupId}},
			url : '../../Services/Model/DeleteObject.php',
			onSuccess : 'deleteGroup',
			message:{start:'Sletter gruppe...',delay:300}
		});
	},
	$success$deleteGroup : function() {
		groupSource.refresh();
		this.groupId = null;
		groupFormula.reset();
		groupWindow.hide();
	},
	
	////////////////////// Articles /////////////////////
	
	$click$newArticle : function() {
		newArticleBox.show();
		articleFormula.setValues({
			linkText : 'Læs mere...',
			startdate : new Date()
		});
		articleFormula.focus();
	},
	$submit$articleFormula : function() {
		var values = articleFormula.getValues();
		if (n2i.isBlank(values.title)) {
			ui.showMessage({text:'Titlen skal udfyldes',duration:2000});
			articleFormula.focus();
			return;
		}
		if (!values.blueprint>0) {
			ui.showMessage({text:'Skabelon skal vælges',duration:2000});
		}
		ui.request({
			json : {data:values},
			url : 'CreateArticle.php',
			message : {start:'Opretter artikel...',success:'Artiklen er oprettet'},
			onSuccess : function() {
				articleFormula.reset();
				newArticleBox.hide();
			}
		});
	}
});