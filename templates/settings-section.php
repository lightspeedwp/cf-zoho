<p class="description">
	Go to <a href="https://accounts.zoho.eu/developerconsole" target="_blank">https://accounts.zoho.eu/developerconsole</a> or <a href="https://accounts.zoho.com/developerconsole" target="_blank">https://accounts.zoho.com/developerconsole</a> (depending on the region your Zoho CRM is registered in), and add a client ID with these settings
</p>

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><?php _e( 'Client Name', 'cf-zoho' ); ?></th>
			<td><?php _e( 'Caldera Forms App', 'cf-zoho' ); ?></td>
		</tr>
		<tr>
			<th><?php _e( 'Client domain', 'cf-zoho' ); ?></th>
			<td><?php echo esc_url( get_site_url() ); ?></td>
		</tr>
		<tr>
			<th><?php _e( 'Authorized redirect URIs', 'cf-zoho' ); ?></th>
			<td><?php echo esc_url( cf_zoho\cf_zoho_redirect_url() ); ?></td>
		</tr>
		<tr>
			<th><?php _e( 'Client Type', 'cf-zoho' ); ?></th>
			<td><?php _e( 'WEB', 'cf-zoho' ); ?></td>
		</tr>
	</tbody>
</table>
