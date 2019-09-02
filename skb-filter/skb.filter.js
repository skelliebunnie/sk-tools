jQuery(document).ready( function($) {

	var selected_filter = 'all';
	var filter_type = $("#skb-filter-container").data("type");

	//var singular = $("#skb-filter-container").data("singular")
	//var plural = $("#skb-filter-container").data("plural")

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

			if( $(this).hasClass('skb-active-filter') ) {
				$(this).removeClass('skb-active-filter');

				multiFilter( $(this), filter_type );

			} else {
				$(this).addClass('skb-active-filter');

				multiFilter( $(this), filter_type );
			}
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

		console.log(filters_list["colors"]);

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

		//console.log(filters_list);
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
				var match = $(this).data(target_type);

				$(this).addClass("skb-active-filter");

				if( strpos(match, ', ') ) {
					match = match.split(", ");

				} else if( strpos(match, ',') )  {
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

		var filters_list = getSelectedFilters();
		var filters_notice = [];

		if( filters_list.length > 0 ) {
			$(".skb-filter-item").each(function() {

				var item = $(this);
				var data = item.data();

				var filter_name, tags_list;

				item.hide();

				if(multiType === "add" || multiType === "additive") {
					$.each(filters_list, function(i, filter) {
						filter_name = filter.name;
						tags_list = filter.tags;

						$.each(tags_list, function(k,t) {
							t = t;
							if( !filters_notice.includes(t) ) {
								filters_notice.push(t);
							}
						});

						$.each(data, function(n, obj) {
							if( strpos(obj, ', ') ) {
								obj = obj.split(', ');

								$.each(obj, function(k, val) {
									val = val;
									if( tags_list.includes(val) ) {
										item.show();

									}
								});

							} else {
								obj = obj;
								if( tags_list.includes(obj) ) {
									item.show();
								}
							}

						});

					});

				} else {
					var target_list = [], item_list = [];

					$.each(filters_list, function(i, filter) {
						filter_name = filter.name;
						tags_list = filter.tags;

						$.each(tags_list, function(k,t) {
							t = t;
							if( !filters_notice.includes(t) ) {
								filters_notice.push(t);
							}

							if( !target_list.includes(t) ) {
								target_list.push(t);
							}
						});

						$.each(data, function(n, obj) {
							if( strpos(obj, ', ') ) {
								obj = obj.split(', ');

								$.each(obj, function(k, val) {
									val = val;
									if( tags_list.includes(val) ) {
										item_list.push(val);
									}
								});

							} else {
								obj = obj;
								if( tags_list.includes(obj) ) {
									item_list.push(obj);
								}
							}

						});

					});

					if( compArrays(target_list, item_list) ) {
						item.show();
					}

				}

			});

			var tag_count = "the tag";
			if( filters_notice.length > 1 ) { tag_count = "the tags"; }

			$("#skb-filter-container").after(`<p id='skb-filter-notice'><span>Currently filtering by ${tag_count} <strong>${filters_notice.join(", ")}</strong></span><i id='skb-remove-filter' class='fas fa-times-circle'></i></p>`);
		} else {
			$("#skb-filter-notice").remove();

			$(".skb-filter-item").each(function() { $(this).show(); selected_filter = "all"; });
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