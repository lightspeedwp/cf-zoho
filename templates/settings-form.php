<div class="wrap">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form action="options.php" method="post">

		<?php settings_fields( 'cfzoho' ); ?>

		<?php do_settings_sections( 'cfzoho' ); ?>

		<?php submit_button( 'Save Settings' ); ?>

	</form>
</div>
