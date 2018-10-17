

var cf_zoho_handle_return = function( obj ) {
    if (obj.status === 'complete') {
        if ( undefined !== obj.data.cf_id ) {
            var form_id = obj.form_id;
            var parent_field = jQuery( '.remodal[data-form-id="' + form_id + '"]').attr('data-parent-field');

            //Check for previous values
            var current_value = jQuery( '#' + parent_field ).val();
            var to_save = '';
            if ( '' !== current_value ) {
                to_save = current_value + ',';
            }
            to_save += obj.data.cf_id;
            jQuery( '#' + parent_field ).val( to_save );
        }
    }
};

/**
 * When the form initiates
 */
jQuery( document ).on( 'cf.form.init', function () {
    if ( 0 < jQuery( '.cf-zoho-modal' ).length ) {
        jQuery( '.cf-zoho-modal' ).each( function() {

            //move the button
            var field_id = jQuery(this).attr('data-field-id');
            var target_modal = jQuery(this).find('button').attr('data-remodal-target');
            jQuery( '#' + field_id ).after( jQuery(this).html() );
            jQuery(this).remove();
            jQuery( '#' + target_modal ).attr( 'data-parent-field', field_id );
        });
    }
});

jQuery( document ).on( 'cf.form.submit', function (event, data ) {
    var $form = data.$form;
    if ( 0 < $form.parents('.caldera-modal-body').length ) {
        var formId = $form.attr('id');
    }
});
