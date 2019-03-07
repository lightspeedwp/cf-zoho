var CF_ZOHO_FIELD = {

    init: function() {
        this.init_primary_form();


        //Set the field Limits
        this.field = jQuery('input.zoho-form-field');
        this.limit = this.field.attr('data-limit');

        this.watch_form_submit();
        this.watch_button_click();
        this.watch_delete_button();
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

                //jQuery( '.caldera-forms-modal.modal-open' ).removeClass('modal-open').addClass('hidden').addClass('submitted');

                console.log( obj.return_message );
                if ( undefined !== obj.return_message && null !== obj.return_message && '' !== obj.return_message ) {
                    jQuery( '#' + parent_field ).parent('div').find('.alert-wrapper').append( obj.return_message );
                }

                this.close_modal();
                this.increase_limit();
                this.check_limit();
            }
        } else if ( obj.status === 'error' ) {
            jQuery('body').unblock();
        }
    },

    /**
     * When the form initiates
     */
    init_primary_form: function( ) {
        jQuery( document ).on( 'cf.form.init', function () {
            if ( 0 < jQuery( '.lsx-cf-zoho-modal' ).length ) {
                var modal_counter = 0;
                jQuery( '.lsx-cf-zoho-modal' ).each( function() {
                    modal_counter++;
                    //move the button
                    var field_id = jQuery(this).attr('data-field-id');
                    var target_modal = jQuery(this).find('button').attr('data-remodal-target');

                    //Hide all other buttons after the first one.
                    if ( 1 < modal_counter ) {
                        jQuery(this).find('button.caldera-forms-modal').addClass('hidden');
                    }
                    jQuery(this).removeClass('hidden');

                    jQuery( '#' + field_id ).after( jQuery(this).html() );
                    jQuery(this).remove();
                    jQuery( '#' + target_modal ).attr( 'data-parent-field', field_id );
                });
            }
        });
    },

    /**
     * Watch the button clicks
     */
    watch_form_submit: function( ) {
        jQuery( document ).on( 'cf.form.submit', function (event, data ) {
            var $form = data.$form;
            if ( 0 < $form.parents('.caldera-modal-body').length ) {
                var formId = $form.attr('id');
            }
        });
    },


    watch_button_click: function( ) {
        this.watch_modal_open();
        this.watch_modal_close();

    },

    watch_modal_open: function( ) {
        jQuery( document ).on( 'click', '.caldera-forms-modal', function (event) {
            jQuery( this ).addClass( 'modal-open' );
        });
    },
    watch_modal_close: function( ) {
        jQuery( document ).on( 'click', '.remodal-close', function (event) {
            jQuery( '.caldera-forms-modal.modal-open' ).removeClass( 'modal-open' );
        });
    },
    watch_delete_button: function () {
        var $this = this;
        jQuery( document ).on( 'click', '.alert-wrapper .close', function (event) {
            $this.remove_value( jQuery( this ).parent().attr('data-entry-id') );
            $this.decrease_limit();
            $this.reset_modal_button();
            $this.check_limit();
        });
    },

    /**
     * Handles the Limits for the button
     */
    check_limit: function() {
        var count = this.field.attr('data-count');
        var limit = this.field.attr('data-limit');
        /*if ( parseInt( count ) <= parseInt( limit ) ) {
            jQuery('button.caldera-forms-modal.hidden:not(.submitted)').first().removeClass('hidden');
        }*/

        console.log('triggering the limit');
        if ( parseInt( count ) < parseInt( limit ) ) {
            jQuery('.caldera-form-page[data-formpage="1"] .zoho-form-field-validation input').prop('checked', false);
            jQuery( 'button.caldera-forms-modal' ).removeClass('modal-open').removeClass('hidden').removeClass('submitted');
        } else {
            jQuery('.caldera-form-page[data-formpage="1"] .zoho-form-field-validation input').prop('checked', 'checked');
            jQuery( 'button.caldera-forms-modal' ).removeClass('modal-open').addClass('hidden').addClass('submitted');
        }
        jQuery('body').unblock();
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

    reset_modal_button: function() {
        //If there are no buttons showing unhide one
        console.log( jQuery('.caldera-forms-modal:not(.hidden)') );


        if ( 0 >= jQuery('.caldera-forms-modal:not(.hidden)') ) {
            jQuery('.caldera-forms-modal.hidden').first().removeClass('hidden').removeClass('submitted');
        } else {
            jQuery('.caldera-forms-modal.hidden').first().removeClass('submitted');
        }
        //If there is a button showing leave it.
    },

    remove_value :function( entry_id ) {
        var value = this.field.val();

        var arr = value.split(',');

        var index = arr.indexOf( entry_id );
        if (index !== -1) arr.splice(index, 1);

        this.field.val( arr.join(',') );
    },

    cf_form_validation: function() {
        /* Allow the form to update th passenger field on validation*/
        jQuery( document ).on(  'cf.validate.FormError', function( event, obj ){
            if ( false == obj.inst.validationResult ) {
                jQuery('.remodal').unblock();
            }

            if ( jQuery('.form-group.zoho-form-field-validation').hasClass('has-error') ) {
                if ( 0 === $('.btn.cf-form-trigger').parent().find('.parsley-required').length ) {
                    jQuery('.btn.cf-form-trigger').parent().append(passenger_alert);
                }
            }
        });
    },

    close_modal: function () {
        jQuery( '.remodal-wrapper.remodal-is-opened' ).find('.remodal-close').click();
    }
};

jQuery(document).ready(function() {
    CF_ZOHO_FIELD.init();
});

var lsx_cf_zoho_handle_return = function( obj ) {
    CF_ZOHO_FIELD.handle_form_return( obj );
};
