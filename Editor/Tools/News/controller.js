hui.ui.listen({
	
	dragDrop : [
		{drag:'news',drop:'newsgroup'}
	],
	uploadWindow : null,
	groupId : null,
	fileId : null,
	
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('tool:News');
		}
		var newsInfo = hui.location.getInt('newsInfo');
		if (newsInfo) {
			this.loadNews(newsInfo);
		}
	},
	
	$select$list : function(item) {
		if (item.kind=='news') {
			hui.ui.get('delete').setEnabled(true);
			hui.ui.get('info').setEnabled(true);
			hui.ui.get('duplicate').setEnabled(true);
		}
	},
	$selectionReset$list : function() {
		hui.ui.get('delete').setEnabled(false);
		hui.ui.get('info').setEnabled(false);
		hui.ui.get('duplicate').setEnabled(false);
	},
	$open$list : function(obj) {
		this.loadNews(obj.id);
	},
	
	$valueChanged$search : function() {
		list.resetState();
	},
	
	$select$selector : function(item) {
		list.resetState();
		if (item.kind=='newssource') {
			hui.ui.changeState('source');
		} else if (item.kind=='newsgroup') {
			hui.ui.changeState('group');
			groupHeader.setText(item.title);
		} else {
			hui.ui.changeState('default');
		}
	},
	
	//////////////////////// Dragging ////////////////////////
	
	$drop$news$newsgroup : function(dragged,dropped) {
		hui.ui.request({
			url : 'actions/AddNewsToGroup.php',
			message : {start:{en:'Adding to group...', da:'Tilføjer til gruppe...'},delay:300,success:{en:'The news item has been added to the group',da:'Nyheden er blevet tilføjet til gruppen'}},
			json : {data:{file:dragged.id,group:dropped.value}},
			onSuccess : function() {
				newsSource.refresh();
				groupSource.refresh();
			}
		});
	},
	
	//////////////////////// Actions /////////////////////////
	
	$click$delete : function() {
		var obj = list.getFirstSelection();
		if (obj.id===this.newsId) {
			this.closeNewsAfterDeletion = true;
		}
		hui.ui.request({
			url : 'actions/DeleteNews.php',
			onSuccess : 'fileDeleted',
			parameters : {id:obj.id},
			message : {start:{en:'Deleting news item', da:'Sletter nyhed...'},delay:300}
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
		hui.ui.request({
			url : 'data/LoadNews.php',
			onSuccess : 'duplicateLoaded',
			parameters : {id:obj.id},
			message : {start:{en:'Creating copy...',da:'Opretter kopi...'},delay:300}
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
		hui.ui.request({
			url:'data/LoadNews.php',
			onSuccess:'newsLoaded',
			parameters:{id:id},
			message:{start:{en:'Loading news item...',da:'Åbner nyhed...'},delay:300}
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
		data.groups = newsGroups.getValue();
		hui.ui.request({
			url : 'actions/SaveNews.php',
			onSuccess : 'newsUpdated',
			json : {data:data},
			message : {start:{en:'Saving news item...',da:'Gemmer nyhed...'},delay:300}
		});
		this.newsId = null;
		newsFormula.reset();
		newsWindow.hide();
	},
	$success$newsUpdated : function() {
		newsSource.refresh();
		groupSource.refresh();
	},
	$click$deleteNews : function() {
		hui.ui.request({
			url : 'actions/DeleteNews.php',
			onSuccess : 'newsDeleted',
			parameters : {id:this.newsId},
			message : {start:{en:'Deleting news item...',da:'Sletter nyhed...'},delay:300}
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
		if (hui.isBlank(values.title)) {
			hui.ui.showMessage({text:{en:'The title is required',da:'Titlen er krævet'},duration:2000});
			groupFormula.focus();
		} else {
			values.id = this.groupId;
			hui.ui.request({
				json : {data:values},
				url : 'actions/SaveGroup.php',
				onSuccess : 'groupSaved',
				message : {start:{en:'Saving group...',da:'Gemmer gruppe...'},delay:300}
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
	$open$selector : function(item) {
		if (item.kind=='newsgroup') {
			this.loadGroup(item.value);
		}
	},
	$click$groupInfo : function() {
		var item = selector.getValue();
		this.loadGroup(item.value);
	},
	loadGroup : function(id) {
		hui.ui.request({
			parameters:{id:id},
			url:'../../Services/Model/LoadObject.php',
			onSuccess:'loadGroup',
			message:{start:{en:'Loading group...',da:'Åbner gruppe...'},delay:300}
		});
	},
	$success$loadGroup : function(data) {
		this.groupId = data.id;
		groupFormula.setValues(data);
		deleteGroup.setEnabled(true);
		groupWindow.show();
		groupFormula.focus();
	},
	$click$deleteGroup : function() {
		hui.ui.request({
			json : {data:{id:this.groupId}},
			url : '../../Services/Model/DeleteObject.php',
			onSuccess : 'deleteGroup',
			message:{start:{en:'Deleting group...',da:'Sletter gruppe...'},delay:300}
		});
	},
	$success$deleteGroup : function() {
		groupSource.refresh();
		this.groupId = null;
		groupFormula.reset();
		groupWindow.hide();
	},
	$click$groupRSS : function() {
		var item = selector.getValue();
		window.open('../../../services/news/rss/?group='+item.value);
	},
	
	////////////////////// Articles /////////////////////
	
	$click$newArticle : function() {
		newArticleBox.show();
		articleFormula.setValues({
			linkText : hui.ui.language=='da' ? 'Læs mere...' : 'Read more...',
			startdate : new Date()
		});
		articleBlueprint.selectFirst();
		articleFormula.focus();
	},
	$submit$articleFormula : function() {
		var values = articleFormula.getValues();
		if (hui.isBlank(values.title)) {
			hui.ui.showMessage({text:{en:'The title is required',da:'Titlen er krævet'},duration:2000});
			articleFormula.focus();
			return;
		}
		if (!values.blueprint) {
			hui.ui.showMessage({text:{en:'The template is required',da:'Skabelonen er krævet'},duration:2000});
			return;
		}
		if (values.startdate) {
			values.startdate=Math.round(values.startdate.getTime()/1000);
		}
		if (values.enddate) {
			values.enddate=Math.round(values.enddate.getTime()/1000);
		}
		hui.ui.request({
			json : {data:values},
			url : 'actions/CreateArticle.php',
			message : {start:{en:'Creating article',da:'Opretter artikel...'},success:{en:'The article has been created',da:'Artiklen er oprettet'}},
			onSuccess : function() {
				articleFormula.reset();
				newArticleBox.hide();
				list.refresh();
				groupSource.refresh();
			}
		});
	}
});