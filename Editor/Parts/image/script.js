var partController = {
	$interfaceIsReady : function() {
		var form = document.forms.PartForm;
		if (form.imageId.value==='0') {
			this.showChooserWindow();
		}
		this.suppressLink();
	},
	showChooserWindow : function() {
		if (!this.chooserWindow) {
			var form = document.forms.PartForm;
			var win = this.chooserWindow = ui.Window.create({title:'Vælg billede',width:400,close:true});
			var toolbar = ui.Toolbar.create({labels:false});
			var searchField = ui.SearchField.create({name:'search',adaptive:true});
			toolbar.add(searchField);
			win.add(toolbar);
			var overflow = ui.Overflow.create({height:200});
			var list = ui.Gallery.create({name:'list',maxHeight:300});
			list.listen({
				$selectionChanged:function(list) {
					var id = list.getFirstSelection().value;
					form.imageId.value = id;
					partController.preview();
				},
				$resolveImageUrl : function(obj,width,height) {
					return '../../../util/images/?id='+obj.value+'&maxwidth='+width+'&maxheight='+height;
				}
			});
			overflow.add(list);
			win.add(overflow);
		
			list.setSource(new ui.Source({
				url:'../../Services/Model/Items.php?type=image',
				parameters:[
					{key:'query',value:'@search.value'}
				]
			}));
		}
		this.chooserWindow.show();
	},
	preview : function() {
		op.part.utils.updatePreview({
			node:$('part_image_container'),
			form:$(document.forms.PartForm),
			type:'image',
			delay: 500,
			onComplete:this.suppressLink.bind(this)
		});
	},
	suppressLink : function() {
		var a = $('part_image_container').select('a')[0];
		if (a) {
			a.href='javascript:void(0)';
		}
		var img = $('part_image_container').select('img')[0];
		if (img) {
			img.observe('click',this.showChooserWindow.bind(this));
		}
	},
	showUploadWindow : function() {
		if (!this.uploadWindow) {
			var win = this.uploadWindow = ui.Window.create({title:'Tilføj billede',width:300});
			var tabs = ui.Tabs.create({small:true,centered:true});
			var uploadTab = tabs.createTab({title:'Fra computer',padding:10});
			win.add(tabs);
			var buttons = ui.Buttons.create({top: 10,align:'center'});
			var cancel = ui.Button.create({title:'Annuller'});
			cancel.onClick(win.hide.bind(win));
			var choose = ui.Button.create({text:'Vælg billede...'});
			buttons.add(choose);
			buttons.add(cancel);
			var upload = ui.Upload.create({
				name:'imageUpload',
				useFlash : false,
				url:'../../Parts/image/Upload.php',
				widget:choose,
				placeholder:{title:'Vælg en billedfil...',text:'Filen skal være i formatet JPEG, PNG eller GIF'},
				parameters:{hep:'hey!'}
			});
			uploadTab.add(upload);
			uploadTab.add(buttons);
			var linkTab = tabs.createTab({title:'Fra nettet',padding:10});
			var form = ui.Formula.create({name:'videoUrlForm'});
			form.buildGroup({above:true},[
				{type:'Text',options:{label:'Internetaddresse (URL):',key:'url'}}
			]);
			linkTab.add(form);
			var create = ui.Button.create({name:'createFromUrl',title:'Hent'});
			linkTab.add(create);
		}
		this.uploadWindow.show();
	},
	$uploadDidCompleteQueue$imageUpload : function() {
		//ui.showMessage({text:'Billedet er tilføjet!',duration:2000});
		ui.request({'url':'../../Parts/image/UploadStatus.php',onJSON:function(status) {
			document.forms.PartForm.imageId.value = status.id;
			this.preview();
		}.bind(this)});
	},
	$click$createFromUrl : function() {
		ui.showMessage({text:'Denne funktion virker endnu ikke :-(',duration:2000});
	}
}

ui.listen(partController);