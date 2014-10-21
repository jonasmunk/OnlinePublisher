hui.ui.listen({
	
	dragDrop : [
		{drag:'file',drop:'filegroup'}
	],
	uploadWindow : null,
	groupId : null,
	fileId : null,
	
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Files');
		var fileInfo = hui.location.getInt('fileInfo');
		if (fileInfo) {
			this.loadFile(fileInfo);
		}
	},
	$select$list : function(item) {
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
	$open$list : function(obj) {
		this.loadFile(obj.id);
	},
	$valueChanged$search : function() {
		list.resetState();
	},
	$select$selector : function() {
		list.resetState();
	},
	
	//////////////////////// Dragging ////////////////////////
	
	$drop$file$filegroup : function(dragged,dropped) {
		hui.ui.request({
			url : 'actions/AddFileToGroup.php',
			message : {start:{en:'Adding to group...', da:'Tilføjer til gruppe...'},delay:300,success:{en:'The file has been added to the group',da:'Filen er blevet tilføjet til gruppen'}},
			json : {data:{file:dragged.id,group:dropped.value}},
			$success : function() {
				filesSource.refresh();
				groupSource.refresh();
			}
		});
	},
	
	///////////////////////// Uoload /////////////////////////
	
	
	$filesDropped$list : function(files) {
		uploadWindow.show();
		file.uploadFiles(files);
	},
	
	$click$newFile : function() {
		uploadWindow.show();
	},
	$click$cancelUpload : function() {
		uploadWindow.hide();
	},
	$uploadDidFail$file : function() {
		hui.ui.showMessage({icon:'common/warning',text:{en:'Unable to add file. It may be too large.',da:'Det lykkedes ikke at filføje filen. Den er måske for stor.'},duration:5000});
	},
	$uploadDidCompleteQueue$file : function() {
		filesSource.refresh();
		typesSource.refresh();
	},
	
	////////////////////////// Fetch /////////////////////////
	
	$click$fetchFile : function() {
		fetchFile.setEnabled(false);
		hui.ui.showMessage({text:{en:'Fetching file...',da:'Henter fil...'},busy:true});
		hui.ui.request({url:'actions/FetchFile.php',$success:'fileFetched',parameters:fetchFormula.getValues()});
	},
	$success$fileFetched : function(data) {
		if (data.success) {
			fetchFormula.reset();
			hui.ui.showMessage({text:{da:'The file has been fetched',da:'Filen er hentet'},icon:'common/success',duration:2000});
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
			message : {start : {en:'Deleting file...',da:'Sletter fil...'}, delay : 300},
			url : 'actions/DeleteFile.php',
			$success : 'fileDeleted',
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
		hui.ui.showMessage({text:{en:'The file has been deleted',da:'Filen er nu slettet'},icon:'common/success',duration:2000});
	},
	$click$view : function() {
		var obj = list.getFirstSelection();
		window.open('../../../?file='+obj.id,"filewindow"+obj.id);
	},
	$click$download : function() {
		var obj = list.getFirstSelection();
		document.location = 'actions/DownloadFile.php?id='+obj.id;
	},
	$click$info : function() {
		var obj = list.getFirstSelection();
		this.loadFile(obj.id);
	},
	
	//////////////////////// Properties //////////////////////
	
	loadFile : function(id) {
		hui.ui.request({
			message : {start : 'Åbner fil...',delay:300},
			url : 'data/LoadFile.php',
			$success : 'fileLoaded',
			parameters : {id:id}
		});
	},
	
	$success$fileLoaded : function(data) {
		this.fileId = data.file.id;
		fileFormula.setValues(data.file);
		fileGroups.setValue(data.groups);
		fileWindow.show();
		fileFormula.focus();
	},
	$click$cancelFile : function() {
		this.fileId = null;
		fileFormula.reset();
		fileWindow.hide();
	},
	$submit$fileFormula : function() {
		var data = fileFormula.getValues();
		data.id = this.fileId;
		hui.ui.request({
			message : {start : {en:'Saving file...',da:'Gemmer fil...'},delay:300},
			url : 'actions/UpdateFile.php',
			$success : 'fileUpdated',
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
			message : {start : {en:'Deleting file...',da:'Sletter fil...'}, delay : 300},
			url : 'actions/DeleteFile.php',
			$success : 'fileDeleted',
			parameters : {id:this.fileId}
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
			hui.ui.showMessage({text:{en:'The title is required',da:'Titlen er krævet'},icon:'common/warning',duration:2000});
			groupFormula.focus();
		} else {
			values.id = this.groupId;
			hui.ui.request({
				message : { start : {en:'Saving group...',da:'Gemmer gruppe...'}, delay : 300 },
				json : {data:values},
				url : 'actions/SaveGroup.php',
				$success : function() {groupSource.refresh()}
			});
			this.groupId = null;
			groupFormula.reset();
			groupWindow.hide();
		}
	},
	$submit$groupFormula : function() {
		this.$click$saveGroup();
	},
	$open$selector : function(item) {
		if (item.kind!='filegroup') {return}
		hui.ui.request({
			message : { start : {en:'Loading group...',da:'Åbner gruppe...'}, delay : 300 },
			parameters : {id:item.value},
			url : '../../Services/Model/LoadObject.php',
			$success : 'loadGroup'
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
			message : { start : {en:'Deleting group...',da:'Sletter gruppe...'}, delay : 300 },
			json : {data:{id:this.groupId}},
			url : '../../Services/Model/DeleteObject.php',
			$success : 'deleteGroup'
		});
		this.groupId = null;
		groupFormula.reset();
		groupWindow.hide();
	},
	$success$deleteGroup : function() {
		groupSource.refresh();
	}
});