<?php

function sk_admin() {
	wp_enqueue_style('sk-admin-styles');

	wp_enqueue_script('sk-admin-script', SK_ROOTURL ."/admin/js/admin.js", array('jquery'), null, true);

	ob_start();

	echo "<h1>SKB Tools</h1>";
?>
<label for="item1">Feed Cat <input type="checkbox" name="item1" value="Feed Cat" class="checklist-item"></label>
<label for="item2">Walk Dog <input type="checkbox" name="item2" value="Walk Dog" class="checklist-item"></label>
<label for="item3">Get Groceries <input type="checkbox" name="item3" value="Get Groceries" class="checklist-item"></label>
<label for="item4">Item 4 <input type="checkbox" name="item4" value="item4" class="checklist-item"></label>
<label for="item5">Item 5 <input type="checkbox" name="item5" value="item5" class="checklist-item"></label>
<label for="item6">Item 6 <input type="checkbox" name="item6" value="item6" class="checklist-item"></label>
<label for="item7">Item 7 <input type="checkbox" name="item7" value="item7" class="checklist-item"></label>
<input type="button" value="Save Checklist" name="save-checklist" id="save-checklist">
<hr>
<p>I've saved your checklist to local storage:</p>
<pre id='output'></pre>
<?php
	echo ob_get_clean();
}