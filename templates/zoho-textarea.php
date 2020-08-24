<textarea 
    id="{{_id}}<?php echo esc_attr($field_num); ?>"
    class="field-config block-input magic-tag-enabled 
    <?php
    if (true === (bool) $field['required'] ) {
        ?>
required<?php } ?>" 
    name="{{_name}}[<?php echo esc_attr($key); ?>]"
>{{<?php echo esc_attr($key); ?>}}</textarea>
