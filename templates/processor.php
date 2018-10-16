<?php if ( $module->has_approval_mode() ) : ?>

<div class="caldera-config-group">

	<label for="{{_id}}_action"><?php esc_html_e( 'Options', 'cf-zoho' ); ?></label>

	<div class="caldera-config-field">

		<label>
			<input type="checkbox" id="{{_id}}_approval_mode" class="field-config" name="{{_name}}[_approval_mode]" {{#if _approval_mode}}checked="checked"{{/if}}> <?php esc_html_e( 'Approval Mode', 'cf-zoho' ); ?>
		</label>

		<label>
			<input type="checkbox" id="{{_id}}_workflow_mode" class="field-config" name="{{_name}}[_workflow_mode]" {{#if _workflow_mode}}checked="checked"{{/if}}> <?php esc_html_e( 'Workflow Mode', 'cf-zoho' ); ?>
		</label>

		<label>
			<input type="checkbox" id="{{_id}}_update_existing" class="field-config" value="update" name="{{_name}}[_allow_duplicates]" {{#is _allow_duplicates value="update"}}checked="checked"{{/is}}> <?php esc_html_e( 'Update existing records', 'cf-zoho' ); ?>
		</label>

	</div>

	<label for="{{_id}}_return_information"><?php esc_html_e( 'Submission', 'cf-zoho' ); ?></label>

	<div class="caldera-config-field">
		<label>
			<input type="checkbox" id="{{_id}}_return_information" class="field-config" value="update" name="{{_name}}[_return_information]" {{#is _return_information}}checked="checked"{{/is}}> <?php esc_html_e( 'Return Information', 'cf-zoho' ); ?>
		</label>
		<small><?php esc_html_e( 'Enable the option above when attaching one module to another module, (e.g attaching a contact to a task).', 'cf-zoho' ); ?></small>
	</div>

</div>

<?php endif; ?>

<div id="{{_id}}_lead">

	<?php foreach ( $module->get_module_data() as $section ) : ?>

		<h4><?php echo wp_kses_post( $section['name'] ); ?></h4>

		<?php foreach ( $section['fields'] as $field_num => $field ) : ?>

			<?php $key = sanitize_key( $field['field_label'] ); ?>

			<?php if ( ! empty( $field['val'] ) ) : ?>

				<?php include CFZ_TEMPLATE_PATH . 'zoho-user-select.php'; ?>

			<?php else : ?>

				<div class="caldera-config-group">

					<label for="{{_id}}<?php echo esc_attr( $field_num ); ?>">
						<?php echo wp_kses_post( $module->label( $field ) ); ?>
					</label>

					<div class="caldera-config-field">
						<?php include CFZ_TEMPLATE_PATH . $module->template( $field ); ?>
					</div>

				</div>

			<?php endif; ?>

		<?php endforeach; ?>

	<?php endforeach; ?>

</div>
