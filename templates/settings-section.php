<p class="description">
    Go to <a href="https://accounts.zoho.eu/developerconsole" target="_blank">https://accounts.zoho.eu/developerconsole</a> or <a href="https://accounts.zoho.com/developerconsole" target="_blank">https://accounts.zoho.com/developerconsole</a> (depending on the region your Zoho CRM is registered in), and add a client ID with these settings
</p>

<table class="form-table">
    <tbody>
        <tr>
            <th scope="row"><?php esc_html_e('Client Name', 'lsx-cf-zoho'); ?></th>
            <td><?php esc_html_e('Caldera Forms App', 'lsx-cf-zoho'); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Client domain', 'lsx-cf-zoho'); ?></th>
            <td><?php echo esc_url(get_site_url()); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Authorized redirect URIs', 'lsx-cf-zoho'); ?></th>
            <td><?php echo esc_url(lsx_cf_zoho_redirect_url()); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Client Type', 'lsx-cf-zoho'); ?></th>
            <td><?php esc_html_e('WEB', 'lsx-cf-zoho'); ?></td>
        </tr>
    </tbody>
</table>
