<?php echo $wrapper_before; ?>
<?php echo $field_label; ?>

<?php 
	if(false !== strpos($field_input_class, 'has-error')){
		echo '<span class="has-error">';
			echo $field_caption;
		echo '</span>';
	}
	if( empty( $field['config']['public_key'] ) ){
		$field['config']['public_key'] = null;
	}
?>
<?php echo $field_before; ?>

<?php echo Caldera_Forms_Field_Input::html( $field, $field_structure, $form ); ?>

<?php
	if( isset( $field_structure['field']['config']['form_id'] ) && ( 0 !== $field_structure['field']['config']['form_id'] || '' !== $field_structure['field']['config']['form_id'] ) ){
		$value = $field_structure['field']['config']['form_id'];
		$field_base_id = Caldera_Forms_Field_Util::get_base_id( $field, null, $form );

		echo '<input class="btn btn-primary btn-lg" data-zoho-form-id="' . $value . '" type="button" name="' . $field_structure['name'] . '" id="' . $field_base_id . '" value="' . $field_structure['field']['label'] . '" data-field="' . $field[ 'ID'] . '">';
	}
?>

<?php //echo do_shortcode( '[caldera_form ajax="true" modal="true" id="' . $value . '"]' ); ?>

<?php echo $field_caption; ?>

<?php echo $field_after; ?>

<?php 
	echo $wrapper_after;
