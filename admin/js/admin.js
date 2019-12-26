jQuery(document).ready(function($) {
	var myStorage = window.localStorage;
	var outputTarget = $("#output");
	var list = myStorage.getItem('checklist') !== '' ? myStorage.getItem('checklist') : 'none';

	getChecklist();

	$("#save-checklist").click(function() {
		var items = {};
		$(".checklist-item").each(function() {
			if( $(this).is(":checked") ) {
				items[$(this).attr('name')] = $(this).val();
			}
		});

		itemsStr = JSON.stringify(items);
		console.log(itemsStr);
		saveChecklist(itemsStr);
	});

	$(".checklist-item").each(function() {
		var checklist = JSON.parse( getChecklist() );
		
		if( checklist[ $(this).attr("name") ] ) {
			$(this).prop("checked", true);
		}
	});

	function saveChecklist(items) {
		myStorage.setItem('checklist', items);

		getChecklist();
	}

	function getChecklist() {
		list = myStorage.getItem('checklist');
		outputTarget.empty().append(list);

		return list;
	}

	function removeChecklist() {
		myStorage.removeItem('checklist');
	}

	function clearStorage() {
		myStorage.clear();
	}
	
});