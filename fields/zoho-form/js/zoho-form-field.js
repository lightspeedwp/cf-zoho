jQuery( document ).on( 'cf.form.init', function () {
    if ( 0 < jQuery( '.cf-zoho-modal' ).length ) {
        jQuery( '.cf-zoho-modal' ).each( function() {
            var field_id = jQuery(this).attr('data-field-id');
            jQuery( '#' + field_id ).after( jQuery(this).html() );
            jQuery(this).remove();
        });
    }
});

jQuery( document ).on( 'cf.form.submit', function (event, data ) {
    //data.$form is a jQuery object for the form that just submitted.

    //log form id
    console.log( data );

    var $form = data.$form;

    //get the form that is submiting's ID attribute
    var formId = $form.attr('id');
    console.log(formId);
    console.log(window.cfstate.hasOwnProperty( formId ) );
    if ( window.cfstate.hasOwnProperty( formId ) ) {
        var state = window.cfstate[formId];
        //log a field's value
        console.log(state);
    }

});