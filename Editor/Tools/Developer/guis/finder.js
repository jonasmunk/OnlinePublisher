hui.ui.listen({
    $ready : function() {
        this.$click$findImageConfig();
    },
    $click$findFile : function() {
		var finder = hui.ui.Finder.create({
			title : {en:'Select file',da:'Vælg fil'},
			list : {url : '../../../Services/Finder/FilesList.php'},
			selection : {value : 'all', parameter : 'group', url : '../../../Services/Finder/FilesSelection.php'},
			search : {parameter : 'query'}
		});
		finder.listen({
			$select : function(obj) {
				hui.ui.msg({text:obj.id,duration:3000});
                finder.hide();
			}.bind(this),
            $cancel : function() {
                hui.ui.msg({text:'Closed',duration:3000});
            }
		})
        finder.show();        
    },
    $click$findFileConfig : function() {
		var finder = hui.ui.Finder.create({
			url : '../../../Services/Finder/Files.php'
		});
		finder.listen({
			$select : function(obj) {
				hui.ui.msg({text:obj.id,duration:3000});
                finder.hide();
			}.bind(this),
            $cancel : function() {
                hui.ui.msg({text:'Closed',duration:3000});
            }
		})
        finder.show();        
    },
    $click$findImageConfig : function() {
		var finder = hui.ui.Finder.create({
			url : '../../../Services/Finder/Images.php'
		});
		finder.listen({
			$select : function(obj) {
                hui.log(obj.id)
				hui.ui.msg({text:obj.id,duration:3000});
                finder.hide();
			}.bind(this),
            $cancel : function() {
                hui.ui.msg({text:'Closed',duration:3000});
            }
		})
        finder.show();        
    },
    $resolveImageUrl : function(img,width,height) {
		return '../../../../services/images/?id='+img.id+'&width='+width+'&height='+height+'&format=jpg';
	}
})