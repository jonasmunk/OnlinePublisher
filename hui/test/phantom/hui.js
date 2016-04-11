QUnit.test("Test isString", function(assert) {

	// Positives...
	assert.ok(hui.isString('This is a string'));
	assert.ok(hui.isString(' '));
	assert.ok(hui.isString(''));

	// Negatives...
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

QUnit.test("Test isArray", function(assert) {

	// Positives...
	assert.ok(hui.isArray([]));
	assert.ok(hui.isArray([null]));
	assert.ok(hui.isArray(Array()));

	// Negatives...
	assert.ok(!hui.isArray());
	assert.ok(!hui.isArray(null));
	assert.ok(!hui.isArray(NaN));
	assert.ok(!hui.isArray({}));
	assert.ok(!hui.isArray(""));
	assert.ok(!hui.isArray(" "));
	assert.ok(!hui.isArray("[]"));
	assert.ok(!hui.isArray(1));
	assert.ok(!hui.isArray(-1));
	assert.ok(!hui.isArray(0));

})

QUnit.test("Test isBlank", function(assert) {

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

QUnit.test("Test between", function(assert) {

	assert.equal(5, hui.between(5, 0, 10));
	assert.equal(10, hui.between(5, 20, 10));

	// Non-numbers
	assert.equal(10, hui.between(5, "20", 10));
	assert.equal(10, hui.between(5, " +20  \n", 10));

	assert.equal(5, hui.between(5, null, 10));
	assert.equal(5, hui.between(5, "", 10));
})

QUnit.test("Test fitting", function(assert) {
	! function() {
		var fitted = hui.fit({
			width: 100,
			height: 100
		}, {
			width: 20,
			height: 100
		});
		assert.equal(20, fitted.height);
		assert.equal(20, fitted.width);
	}()

	! function() {
		var fitted = hui.fit({
			width: 100,
			height: 200
		}, {
			width: 20,
			height: 100
		});
		assert.equal(40, fitted.height);
		assert.equal(20, fitted.width);
	}()

	// Same dimensions
	! function() {
		var fitted = hui.fit({
			width: 100,
			height: 100
		}, {
			width: 20,
			height: 20
		});
		assert.equal(20, fitted.height);
		assert.equal(20, fitted.width);
	}()

	! function() {
		var fitted = hui.fit({
			width: 120,
			height: 100
		}, {
			width: 20,
			height: 20
		});
		assert.equal(17, fitted.height);
		assert.equal(20, fitted.width);
	}()

	// Scaled up
	! function() {
		var fitted = hui.fit({
			width: 6,
			height: 5
		}, {
			width: 20,
			height: 20
		});
		assert.equal(17, fitted.height);
		assert.equal(20, fitted.width);
	}()

	! function() {
		var fitted = hui.fit({
			width: 200,
			height: 100
		}, {
			width: 20,
			height: 40
		});
		assert.equal(20, fitted.width);
		assert.equal(10, fitted.height);
	}()

	// Exactly same size
	! function() {
		var fitted = hui.fit({
			width: 50,
			height: 50
		}, {
			width: 50,
			height: 50
		});
		assert.equal(50, fitted.height);
		assert.equal(50, fitted.width);
	}()
})


QUnit.test("Test string.shorten", function(assert) {

	assert.equal(hui.string.shorten('This is a string',0),'...');
	assert.equal(hui.string.shorten('This is a string',2),'...');
	assert.equal(hui.string.shorten('This is a string',5),'Th...');
	assert.equal(hui.string.shorten('This is a string',6),'Thi...');
	assert.equal(hui.string.shorten('This',5),'This');

	assert.equal(hui.string.shorten(null,5),'');
	assert.equal(hui.string.shorten(undefined,5),'');
	assert.equal(hui.string.shorten([],5),'');
	assert.equal(hui.string.shorten({x:'saa'},5),'');

	assert.equal(hui.string.shorten(123456789,5),'12...');

})