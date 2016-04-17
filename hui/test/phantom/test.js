QUnit.asyncTest( "a basic test example", function( assert ) {
  expect(2);
  
  syn.click({},document.getElementById('hey'),function() {
    assert.equal(document.getElementById('hey').style.color,'red','Color is rouge');
    QUnit.start();
  });
  var value = "hello";
  assert.equal( value, "hello", "We expect value to be hello" );
});


QUnit.test( "Test getting", function( assert ) {
  var dog = hui.get.byClass(document.body,'dog');
  assert.equal(dog.length,1);

  var cat = hui.get.byClass('cat');
  assert.ok(cat.length === 0);
    
	var built = hui.build('div',{'class':'hippodippelidoo golbetop dypludido',text:'this is the text',parent:document.body});
	var found = hui.get.firstByClass(document.body,'hippodippelidoo');
	assert.equal(built,found)
    
	assert.equal('this is the text',hui.dom.getText(built));
	hui.dom.setText(built,'Fermentum Lorem Parturient Cursus');
	assert.equal('Fermentum Lorem Parturient Cursus',hui.dom.getText(built));
})

QUnit.test( "Test finding", function( assert ) {
  var dog = hui.find('.dog');
  assert.ok(dog);
  var pInsideFooter = hui.find('footer .common');
  assert.ok(pInsideFooter);
  assert.equal('p-inside-footer',pInsideFooter.getAttribute('data'));
});