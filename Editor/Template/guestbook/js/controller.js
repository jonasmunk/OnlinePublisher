var controller = {
	id : null,
	
	$ready : function() {
		hui.ui.request({
			url : 'data/Load.php',
			parameters : {id:this.id},
			onJSON : function(values) {
				formula.setValues(values);
				formula.focus();
			}.bind(this)
		});
	},
	$valuesChanged$formula : function() {
		save.enable();
		//hui.ui.stress(save);
	},
	$click$save : function() {
		this.$submit$formula();
	},
	$submit$formula : function() {
		save.disable();
		var values = formula.getValues();
		values.id = this.id;
		hui.ui.request({
			url : 'data/Save.php',
			parameters : values,
			onSuccess : function(obj) {
				hui.ui.showMessage({text:'Ændringerne er nu gemt',icon:'common/success',duration:2000});
				window.parent.frames[0].controller.markChanged();
			}
		});
	},
	
	$clickIcon$list : function(info) {
		hui.ui.confirmOverlay({element:info.node,text:'Er du sikker?',okText:'Ja, slet',cancelText:'Nej',onOk : function() {
			hui.ui.request({
				url : 'data/DeleteItem.php',
				parameters : {id : info.row.id},
				onSuccess : function() {
					listSource.refresh();
				}
			});
		}})
	}
};

hui.ui.listen(controller);