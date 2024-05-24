<?php
function wp_json_encode( $data ) {
	return json_encode( $data );
}

function wp_create_nonce( $action ) {
	return 'test_nonce';
}

function plugin_dir_url( $path ) {
	return 'http://example.org/wp-content/plugins/krokedil/klarna-express-checkout/';
}
