<p class="description">
    <?php esc_html_e( 'Contact fields.', 'cfzoho' ); ?>
</p>

<textarea id="<?php echo $id; ?>" name="<?php echo $name; ?>[contacts_fields]" class="regular-text"><?php echo $value['contacts_fields']; ?></textarea>

<p class="description">
    <?php esc_html_e( 'Leads fields.', 'cfzoho' ); ?>
</p>


<textarea id="<?php echo $id; ?>" name="<?php echo $name; ?>[leads_fields]" class="regular-text"><?php echo $value['leads_fields']; ?></textarea>

<p class="description">
    <?php esc_html_e( 'Tasks fields.', 'cfzoho' ); ?>
</p>

<textarea id="<?php echo $id; ?>" name="<?php echo $name; ?>[tasks_fields]" class="regular-text"><?php echo $value['tasks_fields']; ?></textarea>