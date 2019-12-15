jQuery(document).ready( function($) {

	var selected_filter = 'all';
	var filter_type = $("#skb-filter-container").data("type");

	//var singular = $("#skb-filter-container").data("singular")
	//var plural = $("#skb-filter-container").data("plural")

	var data_list = []; var filters_list = [];

	defineFilters(); // get a list of the filters
	createFilters();

	$(".skb-filter").click(function() {
		$(this).parents(".skb-filter-list").hide();
	});

	// WHEN CLICKING ON A FILTER
	if(filter_type === "default" || filter_type === "single") {
		$(".skb-filter").click(function() {
			singleFilter( $(this) );
		});

	} else {
		$(".skb-filter").click(function() {

			$(this).toggleClass('skb-active-filter');

			multiFilter( $(this), filter_type );
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

		$.each(keys, function(n,k) {
			// sort alphabetically
			filters_list[k] = filters_list[k].sort(function(a, b){
		    if(a.name < b.name) { return -1; }
		    if(a.name > b.name) { return 1; }
		    return 0;
			});
		});

		// add the filters to the container
		$.each(keys, function(i, key) {
			if( filters_list[key].length !== 0 ) {
				
				var title = key;
				if( strpos(key, "_") ) {
					title = ucFirst(key.replace(/\_/g, " "));
				}

				$("#skb-filter-container").append(`<section id='skb-wrapper-${key}' class='skb-wrapper'><span class='skb-filter-title' data-filtertype='${key}'>${title}</span><ul class='skb-filter-list' data-filtertype='${key}'></ul></section>`);

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
	}

	function defineFilters() {
		data_list = []; var colorFilter = $("#skb-filter-container").data('colorfilter');

		$(".skb-filter-item").each(function() {
			var data = $(this).data();

			data_list.push(data);
		});

		$.each(data_list, function(i, data) {

			$.each(data, function(key, value) {
				var key = lcFirst(key);
				var value = lcFirst(value);

				if( (colorFilter !== true && !(key in filters_list)) || 
						(colorFilter === true && key !== "color" && !(key in filters_list)) ) {
					filters_list[key] = [];

				}

				if( (colorFilter === true && key !== "color" && value !== "") || (colorFilter !== true && value !== "") ) {

					if( !strpos(value, ',') ) {
						var value_obj = { "name": value, "count": 1 };

						var check = findObjByProperty(filters_list[key], value);
						if( check === null ) {
							filters_list[key].push(value_obj);

						} else {
							filters_list[key][check].count++;
						}

					} else {
						var val_list = value.split(",");
						val_list = lcTrimArray(val_list);

						$.each(val_list, function(index, prop) {
							var value_obj = { "name": prop, "count": 1 };

							var check = findObjByProperty(filters_list[key], prop);
							if( check === null ) {
								filters_list[key].push(value_obj);

							} else {
								filters_list[key][check].count++;
							}
						});

					}
				}

			}); //end $.each(data)
		});
	}

	function handleSingleFilter(el) {

		var filter_tag = el.data("filter");
		var target_type = el.data("filtertype").toLowerCase();
		var count = el.children(".skb-filter-count").text();

		selected_filter = filter_tag;

		$("#skb-remove-filter").remove();

		if( selected_filter !== "all" ) {
			$(".skb-filter-item").each(function() {
				var item = $(this);
				var match = ($(this).data(target_type)).toLowerCase();

				$(this).addClass("skb-active-filter");

				if( match.includes(filter_tag.toLowerCase()) ) {					
					item.show();
					item.parent("p").show();

				} else {
					item.hide();
					item.parent("p").hide();

				}

			});

			var target_type_title = target_type.replace(/\_/g, " ");
			target_type_title = ucFirstWords(target_type_title);


			$("#skb-filter-container").after(`<p id='skb-filter-notice'><span>Currently showing only <strong>${target_type_title} : ${ucFirstWords(filter_tag)}</strong></span><i id='skb-remove-filter' class='fas fa-times-circle'></i></p>`);

		} else {
			$('.skb-filter-item').each(function() {
				$(this).show();
			});

		}

		$("#skb-remove-filter").click(function() { clearFilter(); });
	}

	function singleFilter(el) {
		var tag = el.data("filter");

		if( selected_filter === "all" || (selected_filter !== "all" && selected_filter !== tag ) ) {
			
			clearFilter();

			el.addClass('skb-active-filter');

			handleSingleFilter( el );

		} else if( selected_filter === tag ) {

			el.removeClass("skb-active-filter");
			$("#skb-filter-notice").remove();

			$(".skb-filter-item").each(function() { $(this).show(); });

			selected_filter = "all";

		}

		$(".skb-filter-list").hide();

		$("#skb-remove-filter").click(function() { clearFilter(); });
	}

	function multiFilter(el, multiType) {
		$("#skb-filter-notice").remove();

		var filters_list = []; var filters_notice = []; var filters_message = "";

		$(".skb-active-filter").each(function() {
			filters_list.push($(this).data());
		});

		if(multiType === "add" || multiType === "additive") {
			
			$(".skb-filter-item").each(function() {
				var item = $(this);
				item.hide();

				$.each(filters_list, function(i,filters)  {
					if(!filters_notice.includes(ucFirstWords(filters.filter))) {
						filters_notice.push(ucFirstWords(filters.filter));
					}

					var item_value = (item.data(filters.filtertype)).toLowerCase();
					var filter_value = (filters.filter).toLowerCase();

					if( (item_value).includes( filter_value ) ) {
						item.show();

					}

				});
			});

			filters_notice = filters_notice.join(", ");

			filters_message = $("#skb-filter-container").after(`<p id='skb-filter-notice'><span>Currently showing only items that contain at least <em>one (1)</em> of the following tags: <strong>${ucFirstWords(filters_notice)}</strong></span><i id='skb-remove-filter' class='fas fa-times-circle'></i></p>`);

		} else {
			$(".skb-filter-item").each(function() {
				var item = $(this);

				var show = 0; var count = 0;
				$.each(filters_list, function(i,filters) {
					if(!filters_notice.includes(ucFirstWords(filters.filter))) {
						filters_notice.push(ucFirstWords(filters.filter));
					}

					var item_value = (item.data(filters.filtertype)).toLowerCase();
					var filter_value = (filters.filter).toLowerCase();

					count++;
					if( (item_value).includes( filter_value ) ) {
						show++;
					}
				});
				console.log("Show: "+ show);
				console.log("Count: "+ count);

				if(show === count) { item.show(); } else { item.hide(); }
			});

			filters_notice = filters_notice.join(", ");

			filters_message = $("#skb-filter-container").after(`<p id='skb-filter-notice'><span>Currently showing only items that contain <em>all</em> of the following tags: <strong>${ucFirstWords(filters_notice)}</strong></span><i id='skb-remove-filter' class='fas fa-times-circle'></i></p>`);
		}

		$("#skb-remove-filter").click(function() { clearFilter(); });
	}

	function getSelectedFilters() {

		var filters = [];
		$(".skb-active-filter").each(function() {

			var filter_name = $(this).data('filtertype');
			var tag = $(this).data('filter');

			if( filters.length <= 0 ) {
				filters.push( {'name': filter_name.toLowerCase(), 'tags': [tag] } );

			} else {
				$.each(filters, function(i,obj) {
					if( obj.name === filter_name.toLowerCase() ) {
						obj.tags.push( tag );
					}
				});

				if( findObjByProperty(filters, filter_name.toLowerCase()) === null ) {
					filters.push( {'name': filter_name.toLowerCase(), 'tags': [tag] } );
				}
			}
		});

		return filters;
	}

	function clearFilter() {
		$("#skb-filter-notice").remove();

		$(".skb-filter").each(function() { $(this).removeClass("skb-active-filter"); });

		$(".skb-filter-item").each(function() {
			$(this).show();
			$(this).parent("p").show();
		});

		selected_filter = "all";
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