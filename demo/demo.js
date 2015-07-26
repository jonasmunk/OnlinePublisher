var demo = {
	_go : function(method,time) {
		window.setTimeout(this[method].bind(this),time)
	},
	$ready : function() {
		hui.ui.msg({text:'Ready...',busy:true});
		this._go('start2',500)
	},
	start2 : function() {
		hui.ui.msg({text:'...set...',busy:true});
		this._go('start3',500)
	},
	start3 : function() {
		hui.ui.msg({text:'GO!',icon:'common/success'});
		this._go('showWindow',1000)
	},
	showWindow : function() {
		var win = this.win = hui.ui.Window.create({title:'This is a window',width:500});
		win.add(hui.build('div',{html:'<p style="text-align: center; font: 13px Arial;">This is a simple window</p>'}))
		win.addToBack(hui.build('div',{html:'<p style="text-align: center; font: 13px Arial; color: #fff;">A window has a back side :-)</p>'}))
		win.show();
		hui.ui.hideMessage();
		this._go('darkWindow',2000)
	},
	darkWindow : function() {
		this.win.setVariant('dark')
		this._go('flipWindow',2000)
	},
	flipWindow : function() {
		this.win.flip();
		this._go('afterWindow',2000)
	},
	afterWindow : function() {
		this.win.hide();
		hui.ui.confirmOverlay({text:'Next is animation... are you ready?',$ok : function() {
			demo.animation();
		}.bind(this)})		
	},

	animation : function() {
		hui.ui.showMessage({text:'I can animate...'});
		var node = hui.build('div',{
      text : 'Sem Cras Amet Purus Euismod',
      style : {
        top:'0px',
        left:'0px',
        position:'absolute',
        fontFamily: 'Arial', 
        border: '1px solid #fff',
        fontSize: '0px', color: '#333',
        background:'#fff',
        padding: '2px 10px'
      },
      parent:document.body
    });
		hui.animate({
      node:node,
      css:{left:'40px',top:'50px',fontSize:'30px'},
      duration:2000,
      ease:hui.ease.fastSlow,
      $complete:function() {
			  hui.animate({
          node:node,
          css:{left:'20px',top:'50px',fontSize:'50px',color:'#468',backgroundColor:'#eee'},
          duration:500,
          ease : hui.ease.slowFastSlow,
          $complete:function() {
			
    				hui.animate({
              node:node,
              css: {
                left : '20px',
                top : '30px',
                borderWidth : '4px',
                borderColor : '#aaa',
                fontSize : '40px',
                color : '#864',
                backgroundColor : '#eee'
              },
              duration : 1500,
              ease : hui.ease.elastic,
              $complete : function() {
                hui.ui.hideMessage();
            		hui.ui.confirmOverlay({text:'Want some more?',$ok : function() {
          				hui.animate({
                    node:node,
                    css: {
                      transform : 'rotate(360deg)'
                    },
                    duration:3000,
                    ease : hui.ease.backOut,
                  });
            		}})		
              }
            });
			    }
        })
	    }
    })
	}
}
hui.ui.listen(demo);
