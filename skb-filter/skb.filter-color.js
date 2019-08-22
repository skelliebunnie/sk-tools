jQuery(document).ready( function($) {
	var color_blocks = [];

	$(".skb-filter-item").each(function() {
		var item_colors = $(this).data('color');
		if(item_colors === null || item_colors === undefined) { item_colors = $(this).data('colors'); }
		item_colors = item_colors.split(",");

		$.each(item_colors, function(i, color) {
			var name = color.toLowerCase().trim();

			if( !color_blocks.includes(name) && isColor(name) ) {
				color_blocks.push(name);
			}
		});
	});

	$("#skb-filter-container").after(`<div id='skb-filter-colorblocks-container'></div>`);

	$.each(color_blocks, function(i, name) {

		$("#skb-filter-colorblocks-container").append(`<i class='skb-colorblock' style='background-color: ${name}' data-color='${name}'></i>`);
	});

	var filter_list;

	$(".skb-colorblock").click(function() {
		filter_list = [];

		if( $(this).hasClass('skb-active-colorblock') ) {
			$(this).removeClass('skb-active-colorblock');

		} else {
			$(this).addClass('skb-active-colorblock');

		}

		$(".skb-colorblock").each(function() {
			if( $(this).hasClass('skb-active-colorblock') ) {
				filter_list.push($(this).data('color'));
			}
		});

		if( filter_list.length <= 0 ) {
			filter_list = "all";
		}

		filterByColor( filter_list );
	});

	function filterByColor(color) {
	
		if(color === 'all') {
			$(".skb-filter-item").each(function() { $(this).show(); });

		} else {
			$('.skb-filter-item').each(function() {
				var item = $(this);
				var item_colors = item.data('color');

				$(this).hide();

				if( item_colors.length > 0 ) {

					if(strpos(item_colors, ', ')) {
						item_colors = item_colors.split(', ');

					} else if(strpos(item_colors, ',')) {
						item_colors = item_colors.split(',');

					}

					item_colors = lcTrimArray(item_colors);

					if( findMatchInArray(color, item_colors) ) {
						item.show();
					}
				}


			});
		}
	}

});