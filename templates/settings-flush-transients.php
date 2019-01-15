<p class="description">
	<?php esc_html_e( 'In order for this plugin to work at efficient speeds, Zoho CRM fields and users data are cached. If you have added new users or fields to your Zoho CRM, select below to remove cached data.', 'cf-zoho' ); ?>
</p>

<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" /> <?php esc_html_e( 'Flush Cache', 'cf-zoho' ); ?>
