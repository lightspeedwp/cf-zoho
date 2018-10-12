<h4><?php esc_html_e( 'Errors', 'cf-zoho' ); ?></h4>

<p><?php esc_html_e( 'This processor is causing the following errors:', 'cf-zoho' ); ?></p>

<ul>
<?php foreach ( $errors as $error ) : ?>

	<li><?php echo wp_kses_post( $error ); ?></li>

<?php endforeach; ?>

</ul>
