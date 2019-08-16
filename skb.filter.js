jQuery(document).ready( function($) {

	var selected_filter = 'all';
	var singular = ucFirst($("#filter-container").data("singular"));
	var plural = ucFirst($("#filter-container").data("plural"));

	var data_list = []; var filters_list = [];

	defineFilters(); // get a list of the filters
	createFilters();

	// CLEANUP SOME JUNK THAT THE AIRPRESS PLUGIN ADDS
	$(".skb-filter-item").each(function() {
		if( $(this).siblings("p").html() === "" ) {
			$(this).siblings("p").remove();
		}

		$(".skb-filter-link").each(function() {
			if($(this).html() == "" ) {
				$(this).parent("p").remove();
			}

			/** uncomment this if you're getting empty, unecessary paragraphs added **/
			// $(this).children("p").each(function() {
			// 	if( $(this).prop("classList").length === 0 && $(this).html() === "" ) {
			// 		$(this).remove();
			// 	}
			// });
		});
	});

	// WHEN CLICKING ON A FILTER
	$(".skb-filter").click(function() {
		// clean up
		$("#skb-filter-notice").remove();

		var target_type = $(this).data("filtertype");
		var filter_tag = $(this).data("filter");
		var count = $(this).children(".skb-filter-count").text();

		$(".skb-filter-item").each(function() {
			var item = $(this);
			var match = $(this).data(target_type);

			if( match.indexOf(', ') !== -1 ) {
				match = match.split(", ");

			} else if( match.indexOf(',' !== -1) )  {
				match = match.split(",");
			}

			if( !inArrayCaseInsensitive(match, filter_tag) ) {					
				item.hide();

			} else {
				item.show();

			}

		});

		$("#filter-container").append(`<p id='skb-filter-notice'>Currently showing only <strong>${ucFirst(target_type)} : ${ucFirst(filter_tag)}</strong> (${count}) <i id='skb-remove-filter' class='fas fa-times-circle'></i></p>`);

		$("#skb-remove-filter").click(function() {
			selected_filter = "all";

			$(".skb-filter-item").each(function() {
				$(this).show();
			});

			$("#skb-filter-notice").remove();
		});
	});

	function createFilters() {

		$("#filter-container").empty(); // empty out the container

		var keys = Object.keys(filters_list);

		// add the filters to the container
		$.each(keys, function(i, key) {
			if( filters_list[key].length !== 0 ) {
				$("#filter-container").append(`<span class='skb-filter-title' data-filtertype='${key}'>${key}</span><ul class='skb-filter-list' data-filtertype='${key}'></ul>`);

				$.each( filters_list[key], function(i, obj) {
						$(`.skb-filter-list[data-filtertype='${key}']`).append(`<li class='skb-filter' data-filter="${obj.name}" data-filtertype="${key}">${obj.name} <span class='skb-filter-count'>${obj.count}</span></li>`);
				});
			}
		});
	}

	function defineFilters() {
		data_list = [];

		$("#skb-filter-target > .skb-filter-item").each(function() {
			var data = $(this).data();

			data_list.push(data);
		});

		$.each(data_list, function(i, data) {
			$.each(data, function(key, value) {
				key = lcFirst(key);
				value = ucFirst(value);

				if( !( key in filters_list ) ) {
					filters_list[key] = [];
				}

				if(value !== "" && value !== ",,,") {

					if( value.indexOf(',') === -1 ) {

						var value_obj = { "name": value, "count": 1 };

						var check = findObjByProperty(filters_list[key], value);
							if( check === null ) {
								filters_list[key].push(value_obj);

							} else {
								filters_list[key][check].count++;
							}

					} else {
						var colors = value.split(",");

						$.each(colors, function(i,color) {
							color = ucFirst(color);

							var color_obj = { "name": color, "count": 1 };

							var check = findObjByProperty(filters_list[key], color);
							if( check === null ) {
								filters_list[key].push(color_obj);

							} else {
								filters_list[key][check].count++;
							}

						});
						
					}
				}

			});
		});
	}

	$('body').click(function(event, selector, element) {

		var target = $(event.target);

		if( !target.hasClass('skb-filter') && !target.hasClass('skb-filter-title') && !target.hasClass('skb-filter-item') ) {

			$('.skb-filter-title').each(function() {
				$(this).removeClass('filter-title-active');
			});

			$(".skb-filter").each(function() { 
				if( !$(this).hasClass('hidden') ) { $(this).addClass('hidden'); }

			});
		}
	});

});