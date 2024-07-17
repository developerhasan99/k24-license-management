<?php 

function generate_license_key( $length = 12, $special_chars = true, $extra_special_chars = false, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' ) {
	
	if ( $special_chars ) {
		$chars .= '!@#$%^&*()';
	}
	if ( $extra_special_chars ) {
		$chars .= '-_ []{}<>~`+=,.;:/?|';
	}

	$password = '';
	for ( $i = 0; $i < $length; $i++ ) {
		$password .= substr( $chars, wp_rand( 0, strlen( $chars ) - 1 ), 1 );
	}

	/**
	 * Filters the randomly-generated password.
	 *
	 * @since 3.0.0
	 * @since 5.3.0 Added the `$length`, `$special_chars`, and `$extra_special_chars` parameters.
	 *
	 * @param string $password            The generated password.
	 * @param int    $length              The length of password to generate.
	 * @param bool   $special_chars       Whether to include standard special characters.
	 * @param bool   $extra_special_chars Whether to include other special characters.
	 */
	return apply_filters( 'generate_license_key', $password, $length, $special_chars, $extra_special_chars );
} 