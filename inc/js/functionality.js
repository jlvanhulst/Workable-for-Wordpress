jQuery( document ).ready(
	function () {
		// make the table rows sortable
		let $sortableList = jQuery( '#workable_job_listings tbody' );
		$sortableList.sortable(
			{
				items: '> tr:not(.not_sortable)',
				update: function( event, ui ) {
					var list_elements     = $sortableList.children();
					var active_shortcodes = [];

					jQuery.each(
						list_elements,
						function( index, value ) {
							if (jQuery( value ).hasClass( 'live' )) {
								active_shortcodes.push( jQuery( value ).data( 'shortcode' ) );
							}
						}
					);

					// take the array and put it back to a comma delimited string
					jQuery( '#field_featured_jobs' ).val( active_shortcodes.toString() );
					// submit the form
					jQuery( '#save_workable_options' ).submit();
				}
			}
		);

		/* Save the options via AJAX - fancy */
		jQuery( '#save_workable_options' ).submit(
			function() {
				jQuery( '#submit' ).after( '<span class="updating"><span class="dashicons dashicons-update"></span></span>' );
				jQuery( this ).ajaxSubmit(
					{
						success: function(){
							// show the success message
							jQuery( '#wpbody-content .wrap h1' ).after( '<div class="setting-error-settings_updated updated settings-error notice is-dismissible"><p><strong>Listing of active jobs updated.</strong></p><button type="button" class="notice-dismiss" onclick="dismiss_error(this)"><span class="screen-reader-text">Dismiss this notice.</span></button></div>' );
							jQuery( '.updating' ).remove();
						},
					}
				);

				return false;
			}
		);
	}
);

/* Automagically add the shortcode to the list of pages that need to be added */
jQuery( '.show_on_site' ).click(
	function() {
		// take the active shortcodes and put them in an array
		var active_sc_array = jQuery( '#field_featured_jobs' ).val().split( ',' );

		// if we are checking this off, add to list of shortcodes active
		var active_shortcodes = "";
		if (jQuery( this ).is( ':checked' )) {
			active_sc_array.push( jQuery( this ).data( 'shortcode' ) );
			jQuery( this ).parents( 'tr' ).addClass( 'live' );
			jQuery( this ).parents( 'tr' ).removeClass( 'not_sortable' );
		} else {
			active_sc_array.splice( jQuery.inArray( jQuery( this ).data( 'shortcode' ), active_sc_array ), 1 );
			jQuery( this ).parents( 'tr' ).removeClass( 'live' );
			jQuery( this ).parents( 'tr' ).addClass( 'not_sortable' );
		}
		// take the array and put it back to a comma delimited string.
		jQuery( '#field_featured_jobs' ).val( active_sc_array.toString() );
		// submit the form.
		jQuery( '#save_workable_options' ).submit();
	}
);

/* Toggle the show description row */
jQuery( '.see_job_description' ).click(
	function(e) {
		e.preventDefault();
		jQuery.ajax(
			{
				type : "post",
				dataType : "json",
				url : ajaxurl,
				data : {
					action: "get_specific_job_description",
					shortcode : jQuery( this ).data( 'shortcode' )
				},
				success: function(data) {
					jQuery( '.modal-content .inner' ).html( data );
					jQuery( '#job_description_modal' ).fadeIn();
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log( xhr.status );
					console.log( thrownError );
				}
			}
		);
	}
);

jQuery( '#close_modal' ).click(
	function(){
		jQuery( '#job_description_modal' ).fadeOut();
	}
);

/* Dismiss the settings updated box */
function dismiss_error(display_box) {
	jQuery( display_box ).parents( '.updated' ).slideUp( 'fast' );
}
