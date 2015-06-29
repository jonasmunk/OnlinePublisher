QUnit.test( "Basic properties", 
    function( assert ) {
      
      var el = hui.build('div');

      assert.strictEqual(hui.ui.get('myInput'), undefined,
        'Before creation there should be no component by the name');

      var input = new hui.ui.Editable({
        element : el,
        name : 'myInput',
        value : 5
      });
      
      assert.ok(typeof(input)=='object', 'The button should be an object');
      assert.equal(input.name, 'myInput', 'The name is correct');
      assert.equal(hui.ui.get('myInput'), input, 'We should be able to get it by name');

      assert.equal(input.getValue(), 5, 'The value is correct');      
      input.setValue(10);
      assert.equal(input.getValue(), 10, 'The value should be changed');
      
      assert.equal(el, input.element, 'The element is correct');
      assert.equal(el, input.getElement(), 'The element is correct');
      
      hui.ui.destroy(input);
      assert.strictEqual(hui.ui.get('myInput'), undefined,
        'After it is destroyed it should be unregistered');
   }
)

QUnit.test( "Listening", function(assert) {

  var localChangeValue;
  var globalChangeValue;

  var input = new hui.ui.Editable({
    element : hui.build('div'),
    name : 'myInput',
    value : 5,
    listen : {
      $valueChanged : function(value) {
        localChangeValue = value;
      }
    }
  });
  
  hui.ui.listen({
    $valueChanged$myInput : function(value) {
      globalChangeValue = value;
    }
  });
  
  input.setValue(20);
  assert.equal(20, localChangeValue, 'The value should be received by the local listener');
  assert.equal(20, globalChangeValue, 'The value should be received by the global listener');

})