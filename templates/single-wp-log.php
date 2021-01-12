<?php
if ( ! is_user_logged_in() ) {
	die( 'You have to be logged in to view logs' );
}

get_header();

while ( have_posts() ) {

	the_post();

	$content = get_the_content();

	echo wp_kses_post( '<pre>' . print_r( json_decode( $content, true ), true ) . '</pre>' );

}

get_footer();
