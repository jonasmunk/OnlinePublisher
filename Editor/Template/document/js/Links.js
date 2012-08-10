hui.ui.listen({
	$ready : function() {
		
	},
	$click$close : function() {
		window.parent.location='Edit.php';
	},
	$clickIcon$list : function(info) {
		if (info.data.action=='deleteLink') {
			hui.ui.confirmOverlay({
				element : info.node,
				text : {en:'Are you sure?',da:'Er du sikker?'},
				okText : {en:'Yes, delete',da:'Ja, fjern'},
				cancelText : {en:'No',da:'Nej'},
				$ok : function() {
					hui.ui.request({
						url : 'data/DeleteLink.php',
						parameters : {id:info.row.id},
						onSuccess : function() {
							list.refresh();
						}
					});
				}
			});
		}
		else if (info.data.action=='editLink') {
			document.location='Editor.php?editLink='+info.row.id;
		}
		else if (info.data.action=='pageInfo') {
			parent.location='../../Tools/Sites/?pageInfo='+info.data.id;
		}
		else if (info.data.action=='editPage') {
			parent.location='../../Template/Edit.php?id='+info.data.id;
		}
		else if (info.data.action=='viewPage') {
			parent.location='../../Services/Preview/?id='+info.data.id;
		}
		else if (info.data.action=='viewFile') {
			window.open('../../../?file='+info.data.id);
		}
		else if (info.data.action=='fileInfo') {
			parent.location='../../Tools/Files/?fileInfo='+info.data.id;
		}
		else if (info.data.action=='visitUrl') {
			window.open(info.data.url);
		}
	}
})