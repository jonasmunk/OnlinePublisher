<?php
require_once '../../../Include/Private.php';
header('Content-type: text/javascript');

$controllers = PartService::getAllControllers();
?>

hui.ui.listen({
	$ready : function() {
		var editor = hui.ui.Editor.get();
		editor.setOptions({
			rowClass : 'document_row',
			columnClass : 'document_column',
			partClass : 'part_section'
		});
		<?php
		foreach ($controllers as $controller) {
			if ($controller->isLiveEnabled()) {
				echo "editor.addPartController('".$controller->getType()."','".$controller->getType()."',op.Editor.".ucfirst($controller->getType()).");\n";
			}
		}
		?>
		editor.ignite();
		editor.activate();
	}
})

<?php
foreach ($controllers as $controller) {
	if ($controller->isLiveEnabled()) {
		require_once '../../../Parts/'.$controller->getType().'/live.js';
		echo "\n\n\n\n";
	}
}
require_once '../live/live.js';
?>