
<input type="checkbox" <?php if (null !== $value && false !== $value ) {
    ?> checked="checked" <?php 
                       } ?> id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="1" /> <?php esc_html_e('Enable logging of your requests', 'lsx-cf-zoho'); ?>
