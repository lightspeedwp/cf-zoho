<?php echo wp_kses_post( $wrapper_before ); ?>
<?php echo wp_kses_post( $field_label ); ?>

<?php
if ( false !== strpos( $field_input_class, 'has-error' ) ) {
	echo '<span class="has-error">';
		echo wp_kses_post( $field_caption );
	echo '</span>';
}
?>
<?php echo wp_kses_post( $field_before ); ?>

<div class="alert-wrapper">

</div>

<?php
	// $field_structure['field_required'] = true;

	echo wp_kses_post( Caldera_Forms_Field_Input::html( $field, $field_structure, $form ) );


if ( isset( $field_structure['field']['config']['form_id'] ) && ( 0 !== $field_structure['field']['config']['form_id'] && '' !== $field_structure['field']['config']['form_id'] && '0' !== $field_structure['field']['config']['form_id'] ) ) {
	$value = $field_structure['field']['config']['form_id'];
	$field_base_id = Caldera_Forms_Field_Util::get_base_id( $field, null, $form );

	$limit = 1;
	if ( isset( $field_structure['field']['config']['limit'] ) ) {
		$limit = $field_structure['field']['config']['limit'];
	}

	if ( '' !== $field_structure['field']['config']['button_text'] ) {
		$title = $field_structure['field']['config']['button_text'];
	} else {
		$title = $field_structure['field']['label'];
	}

	if ( '' === $title ) {
		$title = lsx_cf_zoho_get_form_title( $field_structure['field']['config']['form_id'] );
	}
	if ( '' === $title ) {
		$title = esc_attr__( 'Open', 'lsx-cf-zoho' );
	}

	lsx_cf_zoho_register_modal( $value, $field_base_id, $limit, $title );
}
?>

<?php echo wp_kses_post( $field_caption ); ?>

<?php echo wp_kses_post( $field_after ); ?>

<?php
	echo wp_kses_post( $wrapper_after );
