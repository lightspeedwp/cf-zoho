<input 
    type="text" 
    id="{{_id}}<?php echo $field_num; ?>" 
    class="field-config block-input magic-tag-enabled <?php if ( true === (bool) $field['required'] ) { ?>required<?php } ?>" 
    name="{{_name}}[<?php echo $key; ?>]" 
    value="{{<?php echo $key; ?>}}" 
/>