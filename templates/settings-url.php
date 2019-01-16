<?php
if ( '' === $value ) {
	$value = 'https://accounts.zoho.com/oauth/v2';
} ?>

<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="regular-text" value="<?php echo esc_attr( $value ); ?>" placeholder="https://accounts.zoho.com/oauth/v2" />

<p class="description">
<?php esc_html_e( 'The oauth URL for your Zoho CRM.', 'lsx-cf-zoho' ); ?>
</p>
