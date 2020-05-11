jQuery(document).ready( function($) {
	let filters = $(".sk-filter-advanced").data('filters').split(",");
	// console.log(filters);

	/*
		EXAMPLE OF SEARCH-RESULTS-ENTRY CLASSES
		search-results-entry post-2726 lesson type-lesson status-publish has-post-thumbnail hentry lesson-tag-6-8 lesson-tag-astronomy lesson-tag-galaxy lesson-tag-k-2 lesson-tag-milky-way lesson-tag-spiral-galaxy lesson-tag-stars post
		***
		We're interested in these (types of) classes:
		type-<post_type>
		<taxonomy>-<term>
	*/

	$(".sk-filter-item").click( function() {
		let filter = '';

		$(this).toggleClass("highlight");

		console.log( $(this).hasClass('highlight') );

		if( $(this).hasClass('highlight') ) {

			let filter_type = $(this).data('filter-type');
			let term = $(this).data('filter');
			
			filter = `${filter_type}-${term}`;

		} else {
			let filter = 'all';

		}

		filter_results(filter);

	});

	function filter_results(filter) {
		console.log(filter);

		$(".search-results-entry").each(function() {
			$(this).show();

			if( filter !== 'all' && filter !== "" && !$(this).hasClass(filter) )
				$(this).hide();

			if( filter === "all" || filter === "" )
				$(this).show();
		});
	}
	
} );