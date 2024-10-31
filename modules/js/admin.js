jQuery(document).ready(function($){

 
	
	// Uploading files	var file_frame;	
	// Uploading files
	var file_frame;

	  jQuery('.upload_image_csv').live('click', function( event ){

		var parent = $(this).parents('.media_upload_block');
		var if_single = $(this).attr('data-single');
	  
		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}

		// Create the media frame.
		if( if_single == 1 ){
			file_frame = wp.media.frames.file_frame = wp.media({
			  title: jQuery( this ).data( 'uploader_title' ),
			  button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			  },
			  multiple: false  // Set to true to allow multiple files to be selected
			});
		}else{
			file_frame = wp.media.frames.file_frame = wp.media({
			  title: jQuery( this ).data( 'uploader_title' ),
			  button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			  },
			  multiple: true  // Set to true to allow multiple files to be selected
			});
		}

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			if( if_single == 1 ){
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();
		  
				$('#csv_link', parent).val(  attachment.url  );
				$('.image_preview', parent).html(  '<br/><div class="alert alert-info">'+attachment.url+'</div>'  );
				// Do something with attachment.id and/or attachment.url here
			}else{
		 
			}
		});

		// Finally, open the modal
		file_frame.open();
	  });
	
	
	$('#generate_shortcode').click(function(){
		
	var shortcode = '[csv_filter label=\''+$('#input_placeholder').val()+'\' csv=\''+$('#csv_link').val()+'\' search=\''+$('#search_button_text').val()+'\']'
			$('#shortcode_preview').val( shortcode );
			
			
			var copyText = document.getElementById("shortcode_preview");

			  /* Select the text field */
			  copyText.select();

			  /* Copy the text inside the text field */
			  document.execCommand("copy");

			  copyText.blur();
			 
			
		})
	
});