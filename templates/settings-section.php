<p class="description">
	Go to <a href="https://accounts.zoho.eu/developerconsole" target="_blank">https://accounts.zoho.eu/developerconsole</a> or <a href="https://accounts.zoho.com/developerconsole" target="_blank">https://accounts.zoho.com/developerconsole</a> (depending on the region your Zoho CRM is registered in), and add a client ID with these settings
</p>

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">Client Name</th>
			<td>Caldera Forms App</td>
		</tr>
		<tr>
			<th>Client domain</th>
			<td><?php echo get_site_url(); ?></td>
		</tr>
		<tr>
			<th>Authorized redirect URIs</th>
			<td><?php echo cf_zoho\cf_zoho_redirect_url(); ?></td>
		</tr>
		<tr>
			<th>Client Type</th>
			<td>WEB</td>
		</tr>
	</tbody>
</table>
