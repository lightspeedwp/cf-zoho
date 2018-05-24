<p><?php echo __('Enter the labels of the dropdown / pick list fields which you wish to force as text inputs. Allowing the use of the Caldera Forms Magic Tags in the form processor.', 'cf-zoho'); ?></p>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="contacts_fields	"><?php esc_html_e( 'Contacts' , 'cf-zoho' ); ?></label>
			</th>
			<td>
				<textarea rows="6" type="text" class="regular-text" value="{{contacts_fields}}" id="contact_fields" name="contacts_fields" data-live-sync="true">{{contacts_fields}}</textarea>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="leads_fields"><?php esc_html_e( 'Leads' , 'cf-zoho' ); ?></label>
			</th>
			<td>
				<textarea rows="6" type="text" class="regular-text" value="{{leads_fields}}" id="leads_fields" name="leads_fields" data-live-sync="true">{{leads_fields}}</textarea>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="tasks_fields"><?php esc_html_e( 'Tasks' , 'cf-zoho' ); ?></label>
			</th>
			<td>
				<textarea rows="6" type="text" class="regular-text" value="{{tasks_fields}}" id="tasks_fields" name="tasks_fields" data-live-sync="true">{{tasks_fields}}</textarea>
			</td>
		</tr>
	</tbody>
</table>
