jQuery(function($) {

  var target = $(this).data('target');

  /*
   * Select/Upload image(s) event
   */
  $('body').on('click', '.misha_upload_image_button', function(e){
    if( $(this).hasClass('misha_upload_image_button') ) {
      e.preventDefault();

      target = $(this).data('target');

      var button = $(this),
      custom_uploader = wp.media({
        title: 'Insert Image',
        library : {
          // uncomment the next line if you want to attach image to the current post
          //uploadedTo : wp.media.view.settings.post.id, 
          type : 'image'
        },
        button: {
          text: 'Use this image' // button label text
        },
        multiple: false // for multiple image selection set to true
      }).on('select', function() { // it also has "open" and "close" events

        var attachment = custom_uploader.state().get('selection').first().toJSON();
        
        //$(button).removeClass('button'); // .html('<img id="movie_image" src="' + attachment.url + '/>').next().val(attachment.id)

        $("#"+ target + "_image-upload .misha_upload_image_button").html(`<i class='far fa-image'></i> Change`);
        $("#"+ target + "_image-upload .misha_remove_image_button").removeClass('hidden');
         
        $("#image-"+ target).attr("value", attachment.url);
        $("#image-"+ target).next().val(attachment.url);

        $("#imagePreview-"+ target).removeClass('hidden').attr("src", attachment.url);

      })
      .open();
    }
  });

  /*
   * Remove image event
   */
  $(".misha_remove_image_button, .misha_remove_image_button_multi").on('click', function(){
    $(this).prev().val('').prev().addClass('button').removeClass('hidden');

    $(this).addClass('hidden');

    $(this).parents('div').children(`img`).attr('src', '').addClass('hidden');

    return false;
  });

}); // end jQuery(function($){})