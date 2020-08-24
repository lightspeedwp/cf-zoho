<?php if ($module->has_approval_mode() ) : ?>

<div class="caldera-config-group">

    <label for="{{_id}}_action"><?php esc_html_e('Options', 'lsx-cf-zoho'); ?></label>

    <div class="caldera-config-field">

        <label>
            <input type="checkbox" id="{{_id}}_approval_mode" class="field-config" name="{{_name}}[_approval_mode]" {{#if _approval_mode}}checked="checked"{{/if}}> <?php esc_html_e('Approval Mode', 'lsx-cf-zoho'); ?>
        </label>

        <label>
            <input type="checkbox" id="{{_id}}_workflow_mode" class="field-config" name="{{_name}}[_workflow_mode]" {{#if _workflow_mode}}checked="checked"{{/if}}> <?php esc_html_e('Workflow Mode', 'lsx-cf-zoho'); ?>
        </label>

        <label>
            <input type="checkbox" id="{{_id}}_update_existing" class="field-config" value="update" name="{{_name}}[_allow_duplicates]" {{#if _allow_duplicates}}checked="checked"{{/if}}> <?php esc_html_e('Update existing records', 'lsx-cf-zoho'); ?>
        </label>

        <label>
            <input type="checkbox" id="{{_id}}_return_information" class="field-config" value="1" name="{{_name}}[_return_information]" {{#if _return_information}}checked="checked"{{/if}}> <?php esc_html_e('Return Information', 'lsx-cf-zoho'); ?>
        </label>
        <small><?php esc_html_e('Enable the option above when attaching one module to another module, (e.g attaching a contact to a task).', 'lsx-cf-zoho'); ?></small>
    </div>
</div>

<div class="caldera-config-group">
    <label for="{{_id}}_return_message">
    <?php esc_html_e('Return Message', 'lsx-cf-zoho'); ?>
    </label>
    <div class="caldera-config-field">
        <input type="text" id="{{_id}}_return_message" class="block-input field-config" name="{{_name}}[return_message]" value="{{return_message}}">
    </div>
    <p class="description" style="text-align:center;">
    <?php esc_html_e('Enter the zoho slugs you want from the attached processor. e.g [first_name] [last_name] - [email]', 'lsx-cf-zoho'); ?>
    </p>
</div>

<?php endif; ?>

<div id="{{_id}}_lead">

    <?php foreach ( $module->get_module_data() as $section ) : ?>

        <h4><?php echo wp_kses_post($section['name']); ?></h4>

        <?php foreach ( $section['fields'] as $field_num => $field ) : ?>

            <?php $field = apply_filters('lsx_cf_zoho_processor_field_render', $field); ?>

            <?php $key = sanitize_key($field['field_label']); ?>

            <?php if (! empty($field['val']) ) : ?>

                <?php include LSX_CFZ_TEMPLATE_PATH . 'zoho-user-select.php'; ?>

            <?php else : ?>

                <div class="caldera-config-group">

                    <label for="{{_id}}<?php echo esc_attr($field_num); ?>">
                <?php echo wp_kses_post($module->label($field)); ?>
                    </label>

                    <div class="caldera-config-field">
                <?php include LSX_CFZ_TEMPLATE_PATH . $module->template($field); ?>
                    </div>

                </div>

            <?php endif; ?>

        <?php endforeach; ?>

    <?php endforeach; ?>

</div>
