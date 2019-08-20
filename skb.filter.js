jQuery(document).ready( function($) {

	$("#skb-filter-container").append("Hello?");

	var selected_filter = 'all';
	var filter_type = $("#skb-filter-container").data("type");

	var data_list = []; var filters_list = [];

	defineFilters(); // get a list of the filters
	createFilters();

	// WHEN CLICKING ON A FILTER
	if(filter_type === "default" || filter_type === "single") {
		$(".skb-filter").click(function() {
			singleFilter( $(this) );
		});

	} else {
		$(".skb-filter").click(function() {
			multiFilterAdd( $(this) );
		});
	}

	$(".skb-filter-title").click(function() {
		var target = $(this).data("filtertype");

		if( $(this).siblings(".skb-filter-list").is(":visible") ) {
			$(".skb-filter-list").each(function() { $(this).hide(); });

		} else {
			$(this).parent().siblings(".skb-wrapper").children(".skb-filter-list").hide();
			$(this).siblings(".skb-filter-list").show();

		}
	});

	function createFilters() {

		$("#skb-filter-container").empty(); // empty out the container

		var keys = Object.keys(filters_list);

		// add the filters to the container
		$.each(keys, function(i, key) {
			if( filters_list[key].length !== 0 ) {
				$("#skb-filter-container").append(`<section id='skb-wrapper-${key}' class='skb-wrapper'><span class='skb-filter-title' data-filtertype='${key}'>${key}</span><ul class='skb-filter-list' data-filtertype='${key}'></ul></section>`);

				$.each( filters_list[key], function(i, obj) {
					if( obj.name !== "" && obj.name !== " " ) {
						$(`.skb-filter-list[data-filtertype='${key}']`).append(`<li class='skb-filter' data-filter="${obj.name}" data-filtertype="${key}">${obj.name} <span class='skb-filter-count'>${obj.count}</span></li>`);
					}
				});
			}

			if( $(`.skb-filter-list[data-filtertype='${key}'`).length > 0 ) {
				$(`.skb-filter-list[data-filtertype='${key}'`).hide();
			}
		});

		//console.log(filters_list);
	}

	function defineFilters() {
		data_list = [];

		$(".skb-filter-item").each(function() {
			var data = $(this).data();

			data_list.push(data);
		});

		$.each(data_list, function(i, data) {
			$.each(data, function(key, value) {
				key = lcFirst(key);
				value = lcFirst(value);

				if( !( key in filters_list ) ) {
					filters_list[key] = [];
				}

				if( value !== "" ) {

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
							color = lcFirst(color);

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

	function handleFilter(el) {
		var filter_tag = el.data("filter");
		var target_type = (el.data("filtertype")).toLowerCase();
		var count = el.children(".skb-filter-count").text();

		$("#skb-filter-notice").remove();

		if( selected_filter !== "all" ) {
			$(".skb-filter-item").each(function() {
				var item = $(this);
				var match = $(this).data(target_type);

				$(this).addClass("skb-active-filter");

				if( match.indexOf(', ') !== -1 ) {
					match = match.split(", ");

				} else if( match.indexOf(',' !== -1) )  {
					match = match.split(",");
				}

				if( inArrayCaseInsensitive(match, filter_tag) ) {					
					item.show();
					item.parent("p").show();

				} else {
					item.hide();
					item.parent("p").hide();

				}

			});

			$("#skb-filter-container").after(`<p id='skb-filter-notice'><span>Currently showing only <strong>${ucFirst(target_type)} : ${ucFirst(filter_tag)}</strong></span><i id='skb-remove-filter' class='fas fa-times-circle'></i></p>`);

		} else {
			$('.skb-filter-item').each(function() {
				$(this).show();
			});

		}

		$("#skb-remove-filter").click(function() {
			selected_filter = "all";

			$(".skb-filter-item").each(function() {
				$(this).show();
				$(this).parent("p").show();
			});

			$(".skb-active-filter").removeClass('skb-active-filter');

			$("#skb-filter-notice").remove();
		});
	}

	function singleFilter(el) {
		var tag = el.data("filter");

		if( selected_filter === "all" || (selected_filter !== "all" && selected_filter !== tag ) ) {
			selected_filter = tag;

			el.addClass('skb-active-filter');

			if( selected_filter !== "all" ) {
				$(".skb-filter").each(function() { el.removeClass('skb-active-filter'); });

				$(this).addClass('skb-active-filter');
			}

			handleFilter( $(this) );

		} else if( selected_filter === tag ) {
			el.removeClass("skb-active-filter");
			$("#skb-filter-notice").remove();

			$(".skb-filter-item").each(function() { $(this).show(); });

		}

		$(".skb-filter-list").hide();
	}

	function multiFilterAdd(el) {
		if( el.hasClass('skb-active-filter') ) {
			$(this).removeClass('skb-active-filter');

		} else {
			el.addClass('skb-active-filter');
		}

		var filters_list = getSelectedFilters();
		
		$(".skb-filter-item").each(function() {

			var item = $(this);
			var colors = $(this).data("color");
			if( strpos(colors, ',') ) {
				colors = colors.split(',');
			}
			// var 

			$.each(filters_list, function(i,v) {
				// if( v.type !== "color" &&  === v.tag ) {
				// 	item.show();

				// } else {
				// 	item.hide();
				// }
			});

		});

	}

	function getSelectedFilters() {
		var filters = [];
		$(".skb-active-filter").each(function() {
			filters.push( {'type': ($(this).data('filtertype')).toLowerCase(), 'tag': ($(this).data('filter')).toLowerCase() } );
		});

		return filters;
	}

	$('body').click(function(event, selector, element) {

		var target = $(event.target);

		if( !target.hasClass('skb-filter') && !target.hasClass('skb-filter-title') && !target.hasClass('skb-filter-item') ) {

			$('.skb-filter-title').each(function() {
				$(this).removeClass('filter-title-active');
			});

			$(".skb-filter-list").each(function() { 
				if( $(this).is(':visible') ) { $(this).hide(); }

			});
		}
	});

});