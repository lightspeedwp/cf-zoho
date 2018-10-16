<div class="modal" id="zoho-modal-<?php echo esc_attr( $form_id ); ?>">
	<div class="modal-dialog">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<?php
					$form_title = cf_zoho_get_form_title( $form_id );
					if ( '' !== $form_title ) {
						?>
							<h4 class="modal-title"><?php echo esc_html( $form_title ); ?></h4>
						<?php
					}
					?>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<?php echo wp_kses_post( apply_filters( 'the_content', '[caldera_form ajax="true" id="' . $form_id . '"]' ) ); ?>
			</div>

			<!-- Modal footer -->
			<?php /*<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><?php esc_html_e( 'Close', 'cf-zoho' ); ?></button>
			</div> */ ?>

		</div>
	</div>
</div>
