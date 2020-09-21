jQuery(document).ready( function($) {
	let filters = $(".sk-filter-advanced").data('filters').split(",");
	let filter_restrictions = $(".sk-filter-advanced").data('filter-type');

	if( filter_restrictions == 'sub' || filter_restrictions == 'subtractive' ) {
		filter_restrictions = "and";
	} else {
		filter_restrictions = "or";
	}

	$(".sk-filter-item").click( function() {
		$(".sk-filter-adv-msg").remove();

		$(this).toggleClass("highlight");

		let filter_type, term, terms = [], filter = [];

		$(".highlight").each(function() {
			if( !$(this).hasClass('sk-custom-filter') ) {
				filter_type = $(this).data('filter-type');
				term = $(this).data('filter');

				terms.push(term);
				
				filter.push((`${filter_type}-${term}`).toLowerCase());

			} else {
				filter_type = $(this).data('filter-type');
				filter_type = filter_type.split(',');
				term = $(this).data('filter');

				terms.push(term);

				$.each(filter_type, function(i,v) {
					filter.push((`${v}-${term}`).toLowerCase());
				});

			}
		});

		if( filter.length <= 0 )
			filter.push("all");

		let results = filter_results(filter);

		if( !results )
			no_results_message(terms);

	});

	function filter_results(filter) {
		$("#sk-filter-adv-msg").remove();

		let matches = [];
		$(".search-results-entry").each(function() {
			let has_all_filters = true;
			let has_some_filters = false;

			if( !filter.includes("all") ) {
				let result = $(this);
				let classes = result.attr("class").split(/\s+/);

				$(this).hide();

				$.each(filter, function(i,v) {
					
					if( result.hasClass(v) )
						has_some_filters = true;

					if( !result.hasClass(v) )
						has_all_filters = false;
				});

			} else {
				$(this).show();

			}

			if( (filter_restrictions == "and" && has_all_filters == true) || (filter_restrictions == "or" && has_some_filters == true) ) {
				matches.push( $(this) );
				$(this).show();
			}

		});

		if( !filter.includes("all") && matches.length == 0 )
			return false;

		return true;
	}

	function no_results_message(terms) {
		let terms_list = [];

		$.each(terms, function(i,v) {
			let term = v.replace(/-/g, ' ');
			terms_list.push(toTitleCase(term));
		});

		let last_term = terms_list.pop();
		let join = ", ";
		if( terms_list.length < 2)
			join = " ";

		let message = "<p class='sk-filter-adv-msg sk-msg-warn'>No results found for " + terms_list.join(", ") + join + `<strong>${filter_restrictions}</strong>` + " " + last_term + ".</p>";

		$("#results").prepend(message);
	}
	
} );