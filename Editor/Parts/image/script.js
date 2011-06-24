var partController = {
	$ready : function() {
		var form = document.forms.PartForm;
		if (form.imageId.value==='0') {
			this.showChooserWindow();
		}
		this.suppressLink();
	},
	showChooserWindow : function() {
		if (!this.chooserWindow) {
			var form = document.forms.PartForm;
			var win = this.chooserWindow = hui.ui.Window.create({title:'Vælg billede',width:400,close:true});
			var toolbar = hui.ui.Toolbar.create({labels:false});
			var searchField = hui.ui.SearchField.create({name:'search',adaptive:true});
			toolbar.add(searchField);
			win.add(toolbar);
			var overflow = hui.ui.Overflow.create({height:200});
			var list = hui.ui.Gallery.create({name:'list',maxHeight:300});
			list.listen({
				$selectionChanged:function(list) {
					var id = list.getFirstSelection().value;
					form.imageId.value = id;
					partController.preview();
				},
				$resolveImageUrl : function(obj,width,height) {
					return '../../../services/images/?id='+obj.value+'&width='+width+'&height='+height;
				}
			});
			overflow.add(list);
			win.add(overflow);
		
			list.setSource(new hui.ui.Source({
				url:'../../Services/Model/Items.php?type=image',
				parameters:[
					{key:'query',value:'@search.value'}
				]
			}));
		}
		this.chooserWindow.show();
	},
	preview : function() {
		var self = this;
		op.part.utils.updatePreview({
			node : hui.get('part_image_container'),
			form : document.forms.PartForm,
			type : 'image',
			delay : 500
		});
	},
	suppressLink : function() {
		var container = hui.get('part_image_container');
		var a = container.getElementsByTagName('a');
		if (a) {
			a.href='javascript:void(0)';
		}
		var img = hui.firstByTag(container,'img');
		if (img) {
			hui.ui.listen(img,'click',this.showChooserWindow.bind(this));
		}
	},
	showUploadWindow : function() {
		if (!this.uploadWindow) {
			var win = this.uploadWindow = hui.ui.Window.create({title:'Tilføj billede',width:300});
			var tabs = hui.ui.Tabs.create({small:true,centered:true});
			var uploadTab = tabs.createTab({title:'Fra computer',padding:10});
			win.add(tabs);
			var buttons = hui.ui.Buttons.create({top: 10,align:'center'});
			var cancel = hui.ui.Button.create({title:'Annuller'});
			cancel.onClick(win.hide.bind(win));
			var choose = hui.ui.Button.create({text:'Vælg billede...'});
			buttons.add(choose);
			buttons.add(cancel);
			var upload = hui.ui.Upload.create({
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
			var form = hui.ui.Formula.create({name:'urlForm'});
			form.buildGroup({above:true},[
				{type:'Text',options:{label:'Internetaddresse:',key:'url'}}
			]);
			linkTab.add(form);
			var create = hui.ui.Button.create({name:'createFromUrl',title:'Hent'});
			linkTab.add(create);
		}
		this.uploadWindow.show();
	},
	$uploadDidCompleteQueue$imageUpload : function() {
		//hui.ui.showMessage({text:'Billedet er tilføjet!',duration:2000});
		hui.ui.request({'url':'../../Parts/image/UploadStatus.php',onJSON:function(status) {
			document.forms.PartForm.imageId.value = status.id;
			this.preview();
		}.bind(this)});
	},
	$click$createFromUrl : function() {
		var form = hui.ui.get('urlForm');
		var url = form.getValues()['url'];
		hui.ui.showMessage({text:'Henter billede...'});
		hui.ui.request({
			url : '../../Parts/image/Fetch.php',
			parameters : {url:url},
			onJSON : function(status) {
				if (status.success) {
					hui.ui.showMessage({text:'Billedet er nu hentet',duration:2000});
					document.forms.PartForm.imageId.value = status.id;
					this.preview();
				} else {
					hui.ui.showMessage({text:'Det lykkedes ikke at hente billedet',duration:2000});
				}
			}.bind(this)
		});
		
	},
	isPasteSupported : function() {
		return hui.ui.ImagePaster.isSupported();
	},
	paste : function() {
		hui.ui.showMessage({text:'Pasting...',busy:true});
		if (!this.paster) {
			this.paster = hui.ui.ImagePaster.create({invisible:true});
			this.paster.listen({
				$imageWasPasted : function(data) {
					hui.ui.showMessage({text:'Pasted!',icon:'common/success',duration:2000});
					this._updateWithData(data);
				}.bind(this),
				$imagePasteFailed : function(code) {
					hui.ui.showMessage({text:'Paste failed: '+code,icon:'common/warning',duration:2000});
				}
			})
		}
		hui.log('Telling paster to paste');
		this.paster.paste();
	},
	_updateWithData : function(data) {
		hui.ui.request({
			url : '../../Services/Images/Create.php',
			parameters : {data:data,title:'Udklipsholder'},
			onFailure : function() {
				hui.ui.showMessage({text:'Det lykkedes ikke at lave et billede fra udklipsholderen',icon:'common/warning',duration:2000});
			},
			onJSON : function(response) {
				hui.ui.showMessage({text:'Billedet er nu indsat',icon:'common/success',duration:2000});
				document.forms.PartForm.imageId.value = response.id;
				this.preview();
			}.bind(this)
		});
	}
}

hui.ui.listen(partController);