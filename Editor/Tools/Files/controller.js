hui.ui.listen({
	
	dragDrop : [
		{drag:'file',drop:'filegroup'}
	],
	uploadWindow : null,
	groupId : null,
	fileId : null,
	
	$ready : function() {
		//this.$click$newFile();
	},
	$selectionChanged$list : function(item) {
		hui.ui.get('delete').setEnabled(true);
		hui.ui.get('view').setEnabled(true);
		hui.ui.get('download').setEnabled(true);
		hui.ui.get('info').setEnabled(true);
		hui.ui.get('replace').setEnabled(true);
	},
	$selectionReset$list : function() {
		hui.ui.get('delete').setEnabled(false);
		hui.ui.get('view').setEnabled(false);
		hui.ui.get('download').setEnabled(false);
		hui.ui.get('info').setEnabled(false);
		hui.ui.get('replace').setEnabled(false);
	},
	$listRowWasOpened$list : function(obj) {
		this.loadFile(obj.id);
	},
	
	//////////////////////// Dragging ////////////////////////
	
	$drop$file$filegroup : function(dragged,dropped) {
		hui.ui.request({url:'AddFileToGroup.php',onSuccess:'fileMoved',json:{data:{file:dragged.id,group:dropped.value}}});
	},
	$success$fileMoved : function() {
		filesSource.refresh();
		groupSource.refresh();
	},
	
	///////////////////////// Uoload /////////////////////////
	
	$click$newFile : function() {
		uploadWindow.show();
	},
	$click$cancelUpload : function() {
		uploadWindow.hide();
	},
	$uploadDidCompleteQueue$file : function() {
		filesSource.refresh();
		typesSource.refresh();
	},
	
	////////////////////////// Fetch /////////////////////////
	
	$click$fetchFile : function() {
		fetchFile.setEnabled(false);
		hui.ui.showMessage({text:'Henter fil...',busy:true});
		hui.ui.request({url:'FetchFile.php',onSuccess:'fileFetched',parameters:fetchFormula.getValues()});
	},
	$success$fileFetched : function(data) {
		if (data.success) {
			fetchFormula.reset();
			hui.ui.showMessage({text:'Filen er hentet',icon:'common/success',duration:2000});
			filesSource.refresh();
			groupSource.refresh();
			typesSource.refresh();
		} else {
			hui.ui.showMessage({text:data.message,icon:'common/warning',duration:2000});
		}
		fetchFile.setEnabled(true);
	},
	
	//////////////////////// Actions /////////////////////////
	
	$click$delete : function() {
		var obj = list.getFirstSelection();
		hui.ui.request({
			message : {start : 'Sletter fil...', delay : 300},
			url : 'DeleteFile.php',
			onSuccess : 'fileDeleted',
			parameters : {id:obj.id}
		});
		if (obj.id===this.fileId) {
			this.$click$cancelFile();
		}
	},
	$success$fileDeleted : function(response) {
		filesSource.refresh();
		groupSource.refresh();
		typesSource.refresh();
		hui.ui.showMessage({text:'Filen er nu slettet',duration:2000});
	},
	$click$view : function() {
		var obj = list.getFirstSelection();
		window.open('../../../?file='+obj.id,"filewindow"+obj.id);
	},
	$click$download : function() {
		var obj = list.getFirstSelection();
		document.location = 'DownloadFile.php?id='+obj.id;
	},
	$click$info : function() {
		var obj = list.getFirstSelection();
		this.loadFile(obj.id);
	},
	
	//////////////////////// Properties //////////////////////
	
	loadFile : function(id) {
		hui.ui.request({
			message : {start : 'Åbner fil...',delay:300},
			url : 'LoadFile.php',
			onSuccess : 'fileLoaded',
			parameters : {id:id}
		});
	},
	
	$success$fileLoaded : function(data) {
		this.fileId = data.file.id;
		fileFormula.setValues(data.file);
		fileGroups.setValue(data.groups);
		fileWindow.show();
	},
	$click$cancelFile : function() {
		this.fileId = null;
		fileFormula.reset();
		fileWindow.hide();
	},
	$click$updateFile : function() {
		var data = fileFormula.getValues();
		data.id = this.fileId;
		hui.ui.request({
			message : {start : 'Gemmer fil...',delay:300},
			url : 'UpdateFile.php',
			onSuccess : 'fileUpdated',
			json : {data:data}
		});
		this.fileId = null;
		fileFormula.reset();
		fileWindow.hide();
	},
	$success$fileUpdated : function() {
		filesSource.refresh();
		groupSource.refresh();
		typesSource.refresh();
	},
	$click$deleteFile : function() {
		this.closeFileAfterDeletion = true;
		hui.ui.request({
			message : {start : 'Sletter fil...', delay : 300},
			url:'DeleteFile.php',
			onSuccess:'fileDeleted',
			parameters:{id:this.fileId}
		});
		this.$click$cancelFile();
	},
	
	////////////////////////// Group /////////////////////////
	
	$click$newGroup : function() {
		this.groupId = 0;
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
		if (this.groupId===null) {return};
		var values = groupFormula.getValues();
		if (hui.isBlank(values.title)) {
			hui.ui.showMessage({text:'Du skal angive en titel!',duration:2000});
			groupFormula.focus();
		} else {
			values.id = this.groupId;
			hui.ui.request({
				message : { start : 'Gemmer gruppe...', delay : 300 },
				json : {data:values},
				url : 'SaveGroup.php',
				onSuccess : function() {groupSource.refresh()}
			});
			this.groupId = null;
			groupFormula.reset();
			groupWindow.hide();
		}
	},
	$submit$groupFormula : function() {
		this.$click$saveGroup();
	},
	$selectionWasOpened$selector : function(item) {
		if (item.kind!='filegroup') {return}
		hui.ui.request({
			message : { start : 'Åbner gruppe...', delay : 300 },
			parameters:{id:item.value},
			url:'../../Services/Model/LoadObject.php',
			onSuccess:'loadGroup'
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
			message : { start : 'Sletter gruppe...', delay : 300 },
			json:{data:{id:this.groupId}},
			url:'../../Services/Model/DeleteObject.php',
			onSuccess:'deleteGroup'
		});
		this.groupId = null;
		groupFormula.reset();
		groupWindow.hide();
	},
	$success$deleteGroup : function() {
		groupSource.refresh();
	}
});