<?php
	$options = cf_zoho_get_caldera_forms();
?>

<input type="hidden" value="1" name="config[fields][{{_id}}][required]" class="field-config">

<p class="description" style="text-align:center;">
	<?php esc_html_e( 'Select a form below which has another Zoho processor enabled.', 'cf-zoho' ); ?>
</p>
<div class="caldera-config-group">
	<label for="{{_id}}_form_id">
		<?php esc_html_e( 'Select your form', 'cf-zoho' ); ?>
	</label>
	<div class="caldera-config-field">
		<select id="{{_id}}_form_id" class="block-input field-config required" name="{{_name}}[form_id]">
			<option value="0"><?php esc_html_e( 'Select a form', 'cf-zoho' ); ?></option>

			<?php if ( is_array( $options ) ) {
				foreach ( $options as $option_key => $option_value ) {
					echo wp_kses_post( '<option {{#is form_id value="' . $option_key . '"}}selected="selected"{{/is}} value="' . $option_key . '">' . $option_value . '</option>' );
				}
			} ?>
		</select>
	</div>
</div>

