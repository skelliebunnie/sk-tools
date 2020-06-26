jQuery(document).ready(function($) {
	/**
	 * ADDRESSBOOK
	 */
	// hide the delete button if only 1 row exists

	if( $(".sk-contact-container").length === 1 ) {
		$(".sk-contact--delete").addClass("hidden");

	} else {
		var last_id = $(".sk-contact-container").last().prop("id");

		$(".sk-contact-container").each(function() {

			if( $(this).prop("id") !== "index_0" ) {
				$(this).children(".sk-contact--delete").removeClass("hidden");

			} else {
				if( !$(this).children('.sk-contact--delete').hasClass('hidden') )
					$(this).children('.sk-contact--delete').addClass('hidden');
			}

			if( $(this).prop("id") !== last_id ) 
				$(this).children(".sk-contact--add").addClass('hidden');
		});

	}

	// .live("click", function() {}) required for elements added dynamically
	$(".sk-contact--add").live("click", function() {
		add_input( $(this).parent(".sk-contact-container") );
	});

	$(".sk-contact--delete").live("click", function() {
		delete_input( $(this) );	
	});


	function add_input( item ) {
		var this_index = parseInt( item.prop("id").substring(6) );
		var next_index = this_index + 1;

		clone_container(item, this_index, next_index);
	}

	function clone_container( target, current_index, next_index ) {
		if( target.prop("id") === "index_" + current_index ) {
			var $container_copy = target.clone(true);

			var $new_container = $container_copy.prop('id', 'index_'+next_index);
			$new_container.children("input").val("");

			$new_container.find("input").each(function() {
				this.name = this.name.replace('[0]', `[${next_index}]`);

				this.name = this.name.replace(`[${current_index}]`, `[${next_index}]`);
			});

			$new_container.children(".sk-contact--delete").removeClass("hidden");
			$new_container.children(".sk-contact--add").removeClass("hidden");

			target.after($new_container);
			target.children(".sk-contact--add").addClass("hidden");
		}
	}

	function delete_input( item ) {
		var parent = item.parent(".sk-contact-container");

		var this_index = parseInt(parent.prop("id").substring(6));
		var prev_index = parseInt(parent.prev(".sk-contact-container").prop("id").substring(6));
		console.log(prev_index);

		if( parent.prop("id") === "index_" + this_index ) {
			parent.remove();
		}
		// get last item AFTER removing the item targeted for deletion
		// otherwise the "last" item will never match the "prev_index"
		var last_item = $(".sk-contact-container").last();

		if( $("#index_"+prev_index).prop("id") === last_item.prop("id") ) {
			$(this).children(".sk-contact--add").removeClass("hidden");
		}

		// $(".sk-contact-container").each(function() {
		// 	if( $(this).prop("id") === "index_" + prev_index ) {
		// 		$(this).children("i.sk-contact--add").removeClass('hidden');
		// 	}
		// });
	}

});