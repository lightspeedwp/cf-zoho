<?php
if ( '' === $value ) {
	$value = 'https://accounts.zoho.com/oauth/v2';
} ?>

<input id="<?php echo $id; ?>" name="<?php echo $name; ?>" class="regular-text" value="<?php echo $value; ?>" placeholder="https://accounts.zoho.com/oauth/v2" />

<p class="description">
<?php esc_html_e( 'The oauth URL for your Zoho CRM.', 'cfzoho' ); ?>
</p>
