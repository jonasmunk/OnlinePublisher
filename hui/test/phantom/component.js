QUnit.test( "Basic properties", 
    function( assert ) {
      

      var el = hui.build('div');

      var input = new hui.ui.Editable({
        element : el,
        name : 'myInput',
        value : 5
      });
      
      assert.ok(typeof(input)=='object', 'The button is an object');
      assert.equal(input.name, 'myInput', 'The name is correct');
      assert.equal(input.getValue(), 5, 'The value is correct');
      
      input.setValue(10);
      assert.equal(input.getValue(), 10);
      
      assert.equal(el, input.element, 'The element is correct');
      assert.equal(el, input.getElement(), 'The element is correct');
   }
)