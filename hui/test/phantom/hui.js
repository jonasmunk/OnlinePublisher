QUnit.test( "Test isString", function( assert ) {
    
    var str = "This is a string"

    assert.ok(hui.isString(str));

    assert.ok(hui.isString(''));
    assert.ok(!hui.isString());
    assert.ok(!hui.isString(null));
    assert.ok(!hui.isString(NaN));
    assert.ok(!hui.isString({}));
    assert.ok(!hui.isString([]));
    assert.ok(!hui.isString(['']));
    assert.ok(!hui.isString(['dada']));
    assert.ok(!hui.isString(1));
    assert.ok(!hui.isString(-1));
    assert.ok(!hui.isString(0));
    
})

QUnit.test( "Test isBlank", function( assert ) {
    
    assert.ok(hui.isBlank(''));
    assert.ok(hui.isBlank(' '));
    assert.ok(hui.isBlank('      '));

    assert.ok(hui.isBlank(null));
    assert.ok(hui.isBlank());

    assert.ok(hui.isBlank("\t")); // Tab
    
    // Newlines are blank
    assert.ok(hui.isBlank("\n"));
    assert.ok(hui.isBlank("\n \n"));
    
    // Not blank
    assert.ok(!hui.isBlank("a"));
    assert.ok(!hui.isBlank(" a "));
    
    // Only strings are blank
    assert.ok(!hui.isBlank([]));
    assert.ok(!hui.isBlank({}));
    assert.ok(!hui.isBlank(1));
    assert.ok(!hui.isBlank(0));
    assert.ok(!hui.isBlank(NaN));
})

QUnit.test( "Test between", function( assert ) {

    assert.equal(5,hui.between(5,0,10));
    assert.equal(10,hui.between(5,20,10));
    
    // Non-numbers
    assert.equal(10,hui.between(5,"20",10));
    assert.equal(10,hui.between(5," +20  \n",10));

    assert.equal(5,hui.between(5,null,10));
    assert.equal(5,hui.between(5,"",10));
})