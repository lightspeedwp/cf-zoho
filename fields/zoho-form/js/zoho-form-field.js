var CF_ZOHO_FIELD = {

    init: function() {
        this.init_primary_form();


        //Set the field Limits
        this.field = jQuery('input.zoho-form-field');
        this.limit = this.field.attr('data-limit');

        this.check_limit();
        this.watch_form_submit();

    },

    handle_form_return: function( obj ) {
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

                this.increase_limit();
                this.check_limit();
            }
        }
    },

    /**
     * When the form initiates
     */
    init_primary_form: function( ) {
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
    },

    watch_form_submit: function( ) {
        jQuery( document ).on( 'cf.form.submit', function (event, data ) {
            var $form = data.$form;
            if ( 0 < $form.parents('.caldera-modal-body').length ) {
                var formId = $form.attr('id');
            }
        });
    },


    /**
     * Handles the Limits for the button
     */

    check_limit: function() {
        console.log( this.field );

        var current_entries = this.field.val();
        current_entries = current_entries.split(',');
        console.log( current_entries );
    },

    increase_limit: function() {
        var count = this.field.attr('data-count');
        if ( '' === count ) {
            count = 1;
        } else {
            count++;
        }
        this.field.attr( 'data-count', count );
    },

    decrease_limit: function() {
        var count = this.field.attr('data-count');
        if ( '' === count ) {
            count = 0;
        } else {
            count--;
        }

        if ( 0 > count ) {
            count = 0;
        }
        this.field.attr( 'data-count', count );
    },

};

jQuery(document).ready(function() {
    CF_ZOHO_FIELD.init();
});

var cf_zoho_handle_return = function( obj ) {
    CF_ZOHO_FIELD.handle_form_return( obj );
};
