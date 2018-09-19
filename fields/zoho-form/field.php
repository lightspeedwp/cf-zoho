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

<?php echo $field_caption; ?>

<?php echo $field_after; ?>

<?php 
	echo $wrapper_after;
