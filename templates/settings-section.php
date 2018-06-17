<p class="description">
    Go to <a href="https://accounts.zoho.eu/developerconsole" target="_blank">https://accounts.zoho.eu/developerconsole</a> or <a href="https://accounts.zoho.com/developerconsole" target="_blank">https://accounts.zoho.com/developerconsole</a> (depending on the region your Zoho CRM is registered in), and add a client ID with these settings
</p>

<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">Client Name</th>
            <td><input type="text" value="Caldera Forms App" readonly="readonly" class="regular-text" onClick="this.select();" /></td>
        </tr>
        <tr>
            <th>Client domain</th>
            <td><input type="text" value="<?php echo get_site_url();?>" readonly="readonly" class="regular-text" onClick="this.select();" size="30" /></td>
        </tr>
        <tr>
            <th>Authorized redirect URIs</th>
            <td><input type="text" value="<?php menu_page_url( 'cfzoho' );?>" readonly="readonly" class="regular-text" onClick="this.select();" size="100" /></td>
        </tr>
        <tr>
            <th>Client Type</th>
            <td>WEB</td>
        </tr>
    </tbody>
</table>