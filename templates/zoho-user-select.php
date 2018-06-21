<div class="caldera-config-group">

    <label for="{{_id}}<?php echo $field_num; ?>">
        <?php echo $module->label( $field );?>
    </label>

    <div class="caldera-config-field">

        <select id="{{_id}}<?php echo $field_num; ?>" class="field-config block-input" <?php if( true === (bool) $field['required'] ) { ?>required<?php } ?> name="{{_name}}[<?php echo $key; ?>]">

            <?php if ( false  === $field['required'] && 'ownerlookup' === $field['data_type'] ) { ?>
                <option value="">--None--</option>
            <?php } ?>

            <?php foreach( $field['val'] as $value_key => $value_value ) : ?>
                
                <?php
                $value = $label = $value_value;

                if( is_array( $value_value ) ) {

                    if ( isset( $value_value['content'] ) ) {
                        $value = $label = $value_value['content'];
                    } else {
                        $value = $value_value['value'];;
                        $label = $value_value['label'];
                    }
                }
                ?>

                <option value="<?php echo $value; ?>" {{#is <?php echo $key; ?> value="<?php echo $value; ?>"}}selected="selected"{{/is}}><?php echo $label; ?></option>
            
            <?php endforeach; ?>

        </select>

    </div>

</div>