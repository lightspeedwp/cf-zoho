<?php echo wp_kses_post( $wrapper_before ); ?>
<?php echo wp_kses_post( $field_label ); ?>

<?php
	if ( false !== strpos( $field_input_class, 'has-error' ) ) {
		echo '<span class="has-error">';
			echo wp_kses_post( $field_caption );
		echo '</span>';
	}
	if ( empty( $field['config']['public_key'] ) ) {
		$field['config']['public_key'] = null;
	}
?>
<?php echo wp_kses_post( $field_before ); ?>

<?php echo wp_kses_post( Caldera_Forms_Field_Input::html( $field, $field_structure, $form ) ); ?>

<?php
    $field_structure['field']['config']['form_id'] = 'CF55546c4c7957c';
	if ( isset( $field_structure['field']['config']['form_id'] ) && ( 0 !== $field_structure['field']['config']['form_id'] && '' !== $field_structure['field']['config']['form_id'] && '0' !== $field_structure['field']['config']['form_id'] )  ) {
		$value = $field_structure['field']['config']['form_id'];
		$field_base_id = Caldera_Forms_Field_Util::get_base_id( $field, null, $form );

		echo wp_kses_post( '<input class="btn btn-primary btn-lg hidden" style="display:none; type="button" value="' . $field_structure['field']['label'] . '">' );

		cf_zoho_register_modal( $value, $field_base_id );
	}
?>

<?php echo wp_kses_post( $field_caption ); ?>

<?php echo wp_kses_post( $field_after ); ?>

<?php
	echo wp_kses_post( $wrapper_after );
