<div class="caldera-config-group">

	<label for="{{_id}}<?php echo esc_attr( $field_num ); ?>">
		<?php echo wp_kses_post( $module->label( $field ) ); ?>
	</label>

	<div class="caldera-config-field">

		<select id="{{_id}}<?php echo esc_attr( $field_num ); ?>"
				class="field-config block-input"
				<?php if ( true === (bool) $field['required'] ) { ?>
					required
				<?php } ?>
				name="{{_name}}[<?php echo esc_attr( $key ); ?>]">

			<?php if ( false === $field['required'] && 'ownerlookup' === $field['data_type'] ) { ?>
				<option value=""><?php esc_html_e( '--None--', 'lsx-cf-zoho' ); ?></option>
			<?php } ?>

			<?php foreach ( $field['val'] as $value_key => $value_value ) : ?>

				<?php
				$label = $value_value;
				$value = $label;

				if ( is_array( $value_value ) ) {

					if ( isset( $value_value['content'] ) ) {

						$label = $value_value['content'];
						$value = $label;

					} else {

						$value = $value_value['value'];
						$label = $value_value['label'];
					}
				}
				?>

				<option value="<?php echo esc_attr( $value ); ?>" {{#is <?php echo esc_attr( $key ); ?> value="<?php echo esc_attr( $value ); ?>"}}selected="selected"{{/is}}><?php echo wp_kses_post( $label ); ?></option>

			<?php endforeach; ?>

		</select>

	</div>

</div>
