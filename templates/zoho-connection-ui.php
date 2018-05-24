<table class="form-table">
	<tbody>
	<tr>
		<th scope="row">
			<label for="token"><?php esc_html_e( 'API Url' , 'cf-zoho' ); ?></label>
		</th>
		<td>
			<input type="text" class="regular-text" value="{{#if api_url}}{{api_url}}{{else}}https://crm.zoho.com{{/if}}" id="api_url" name="api_url" data-live-sync="true">
		</td>
	</tr>
		<tr>
			<th scope="row">
				<label for="token"><?php esc_html_e( 'Auth Token' , 'cf-zoho' ); ?></label>
			</th>
			<td>
				<input type="text" class="regular-text" value="{{token}}" id="token" name="token" data-live-sync="true">
				<a onclick="window.open( 'https://accounts.zoho.com/apiauthtoken/create?SCOPE=ZohoCRM/crmapi&DISPLAY_NAME=CalderaForms', '_blank', 'toolbar=no,scrollbars=yes,resizable=yes,width=590,height=700' );return false;" class="button" href="https://accounts.zoho.com/apiauthtoken/create?SCOPE=ZohoCRM/crmapi"><?php esc_html_e( 'Get Token' , 'cf-zoho' ); ?></a>
			</td>
		</tr>

		<?php do_action( 'cf_zoho_settings_tab', 'api' ); ?>

	</tbody>
</table>
