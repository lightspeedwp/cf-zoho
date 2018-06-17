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

	</div>

	<div class="caldera-config-field">

		<label>
			<input type="radio" id="{{_id}}_allow_duplicates" class="field-config" value="allow" name="{{_name}}[_allow_duplicates]" {{#is _allow_duplicates value=""}}checked="checked"{{/is}} {{#is _allow_duplicates value="allow"}}selected="selected"{{/is}}> <?php esc_html_e( 'Allow duplicate records', 'cf-zoho' ); ?>
		</label>

		<label>
			<input type="radio" id="{{_id}}_update_existing" class="field-config" value="update" name="{{_name}}[_allow_duplicates]" {{#is _allow_duplicates value="update"}}checked="checked"{{/is}}> <?php esc_html_e( 'Update existing records', 'cf-zoho' ); ?>
		</label>

		<label>
			<input type="radio" id="{{_id}}_no_updates" class="field-config" value="none" name="{{_name}}[_allow_duplicates]" {{#is _allow_duplicates value="none"}}checked="checked"{{/is}}> <?php esc_html_e( 'No duplicates, no updates', 'cf-zoho' ); ?>
		</label>

	</div>

</div>

<?php endif; ?>

<div id="{{_id}}_lead">
	
	<?php foreach ( $module->get_module_data() as $section ) : ?>

		<h4><?php echo $section['name']; ?></h4>

		<?php foreach ( $section['fields'] as $field_num => $field ) : ?>
			
			<?php $key = sanitize_key( $field['field_label'] ); ?>

			<?php if ( ! empty( $field['val'] ) ) : ?>
				
				<?php include CFZ_TEMPLATE_PATH . 'zoho-user-select.php'; ?>

			<?php else : ?>
				
				<div class="caldera-config-group">

					<label for="{{_id}}<?php echo $field_num; ?>">
						<?php echo $module->label( $field );?>
					</label>
					
					<div class="caldera-config-field">
						<?php include CFZ_TEMPLATE_PATH . $module->template( $field ); ?>
					</div>

				</div>

			<?php endif; ?>

		<?php endforeach; ?>

	<?php endforeach; ?>

</div>
