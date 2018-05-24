<?php
/**
 * Caldera Forms Zoho processor - task config template
 *
 * @package   cf_zoho
 * @author    David Cramer <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link
 * @copyright 2014 David Cramer <david@digilab.co.za>
 */


$config_object = get_option( "_uix_cf-zoho", array() );

if( !empty( $config_object['main']['token'] ) ){
	$connect = new \CF_Zoho_CRM( $config_object['main']['token'] );
	$fields = cf_zoho_get_fields( false );
	if( !empty($fields['error'] ) ){
		echo '<div class="error"><p>Got an error: ' . $fields['error'] .' - Please check plugin is setup.</p></div>';
		return;
	}
	$zoho_users = $connect->get_users();
	if( is_string( $zoho_users ) ){
		echo '<div class="error"><p>Got an error: ' . $zoho_users .' - Please check plugin is setup.</p></div>';
		return;
	}	
	$users = array();
	if ( ! empty( $zoho_users ) ) {

		/* Modify $zoho_users depending on user count. */
		if(isset($zoho_users['users'])){
			$zoho_users = $zoho_users['users'];
		}				
		$array_keys = array_keys( $zoho_users['user'] );

		if ( is_numeric( $array_keys[0] ) ) {
			$zoho_users = $zoho_users['user'];
		}

		foreach ( $zoho_users as $user ) {
			
			$users[] = array(
				'label' => $user['content'],
				'value' => $user['id']	
			);
			
		}
		
	}

}

$ignore_fields = array(
	"Industry",
	"Who Id",
	"What Id",
	"Send Notification Email",
	"Modified By",
	"Remind At",
	"Recurring Activity",
	"Created By",
	"Created Time",
	"Modified Time",
	"Closed Time"
);

$force_text_input = array( );
if( !empty( $config_object['fields']['contacts_fields'] ) ){
	$force_text_input = explode( "\n", $config_object['fields']['tasks_fields'] );
}

?>

<div class="caldera-config-group">
	<label for="{{_id}}_parent"><?php esc_html_e( 'Parent', 'cf-zoho' ); ?></label>
	<div class="caldera-config-field">
		{{{_field slug="parent" type="zoho_task"}}}
	</div>
</div>
	
	

<div id="{{_id}}_lead">
	<?php
	foreach( $fields['task']['section'] as $section ){
		if( !empty( $section['FL'] ) && !isset( $section['FL'][0] ) ){
			$section['FL'][0] = $section['FL'];
		}
	?>

	<h4><?php echo $section['name']; ?></h4>
	<?php foreach( $section['FL'] as $field_num=>$field ){ 
		if( !is_array( $field ) || in_array( $field['label'], $ignore_fields) ){
			continue;
		}

		if( strtolower( $field['type'] ) == 'lookup' && strtolower( $field['label'] ) != 'subject' ){
			$field['val'] = $users;	
		}
		$key = sanitize_key( $field['label'] );

		if( ! empty( $field['val'] ) && ! empty( $force_text_input ) && ! in_array( $field['label'], $force_text_input ) ){
			?>
			<div class="caldera-config-group">
				<label for="{{_id}}<?php echo $field_num; ?>"><?php echo $field['label']; ?><?php if( $field['req'] == 'true'){ ?> <span style="color:#ff0000;">*</span> <?php } ?></label>
				<div class="caldera-config-field">
					<select id="{{_id}}<?php echo $field_num; ?>" class="field-config block-input" <?php if( $field['req'] == 'true'){ ?>required<?php } ?> name="{{_name}}[<?php echo $key; ?>]">
					<?php if('false' === $field['req'] && 'Lookup' === $field['type']){ ?>
						<option value="">-None-</option>
					<?php } ?>

					<?php foreach( $field['val'] as $value_key => $value_value ){ 
						$value = $label = $value_value;
						if( is_array( $value_value ) ){
							if(isset($value_value['content'])){
								$value = $label = $value_value['content'];
							}else{
								$value = $value_value['value'];;
								$label = $value_value['label'];
							}
						}
					?>
					<option value="<?php echo $value; ?>" {{#is <?php echo $key; ?> value="<?php echo $value; ?>"}}selected="selected"{{/is}}><?php echo $label; ?></option>
					<?php } ?>
					</select>
				</div>
			</div>

			<?php
		}else{
			?>
			<div class="caldera-config-group">
				<label for="{{_id}}<?php echo $field_num; ?>"><?php echo $field['label']; ?><?php if( $field['req'] == 'true'){ ?> <span style="color:#ff0000;">*</span> <?php } ?></label>
				<div class="caldera-config-field">
				<?php if( strtolower( $field['type'] ) == 'textarea' ){ ?>
					<textarea id="{{_id}}<?php echo $field_num; ?>" class="field-config block-input magic-tag-enabled <?php if( $field['req'] == 'true'){ ?>required<?php } ?>" name="{{_name}}[<?php echo $key; ?>]">{{<?php echo $key; ?>}}</textarea>
				<?php }else{ ?>
					<input type="<?php if( $field['type'] == 'Date' ){?>number<?php }else{ ?>text<?php } ?>" id="{{_id}}<?php echo $field_num; ?>" class="<?php if( $field['type'] == 'Date' ){?>zoho_date_picker<?php }else{ ?>magic-tag-enabled block-input<?php } ?>  field-config <?php if( $field['req'] == 'true'){ ?>required<?php } ?>" name="{{_name}}[<?php echo $key; ?>]" value="{{<?php echo $key; ?>}}">
					<?php if( $field['type'] == 'Date' ){?>
					<p class="description"><?php esc_html_e( 'Number of days this will be due after submission. Leave blank for no date.' , 'cf-zoho' ); ?></p>
					<?php }?>
				<?php } ?>
				</div>
			</div>
			<?php 
			}
		}
	}
	?>
</div>
