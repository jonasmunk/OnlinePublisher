QUnit.test( "Basic properties", 
    function( assert ) {
        var editor = hui.ui.MarkupEditor.create({text:'My button'});
        assert.ok(typeof(editor)=='object','The editor is an object');
    }
)