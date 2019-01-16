<div class="lsx-cf-zoho-modal" data-field-id="<?php echo esc_attr( $values['field'] ); ?>" id="zoho-modal-<?php echo esc_attr( $form_id ) . '-' . esc_attr( $values['limit'] ); ?>">

	<?php
	$form_title = lsx_cf_zoho_get_form_title( $form_id );
	if ( '' === $form_title ) {
		$form_title = esc_attr__( 'Open', 'lsx-cf-zoho' );
	}
	?>

	<?php echo do_shortcode( '[caldera_form_modal id="' . $form_id . '" type="button" width="500"]' . $form_title . '[/caldera_form_modal]' ); ?>
</div>
