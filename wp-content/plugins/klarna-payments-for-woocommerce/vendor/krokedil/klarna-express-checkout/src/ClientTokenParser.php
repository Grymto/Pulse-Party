<?php
namespace Krokedil\KlarnaExpressCheckout;

defined( 'ABSPATH' ) || exit;

/**
 * Class to parse the client token and validate it.
 *
 * @package Krokedil\KlarnaExpressCheckout
 */
class ClientTokenParser {
	/**
	 * The credentials secret from Klarna for KEC.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * ClientTokenParser constructor.
	 *
	 * @param Settings $settings The credentials secret from Klarna for KEC.
	 */
	public function __construct( $settings ) {
		$this->settings = $settings;
	}


	/**
	 * Parse the client token and validate it.
	 *
	 * @param string $client_token The client token.
	 *
	 * @return array
	 * @throws \Exception If the client token could not be parsed.
	 */
	public function parse( $client_token ) {
		// Split the token on the dot.
		$token_parts = explode( '.', $client_token );

		// If the token does not have 3 parts, it is not a valid token.
		if ( 3 !== count( $token_parts ) ) {
			throw new \Exception( 'Could not parse client token.' );
		}

		// Decode the token parts.
		$decoded_client_token = array_map( 'base64_decode', $token_parts );

		// If the token could not be decoded, it is not a valid token.
		if ( in_array( false, $decoded_client_token, true ) ) {
			throw new \Exception( 'Could not parse client token.' );
		}

		// Decode the header and payload.
		$header  = json_decode( $decoded_client_token[0], true );
		$payload = json_decode( $decoded_client_token[1], true );

		// If the header or payload could not be decoded, it is not a valid token.
		if ( ! $header || ! $payload ) {
			throw new \Exception( 'Could not parse client token.' );
		}

		// Return the header and payload.
		return array(
			'header'  => $header,
			'payload' => $payload,
		);
	}
}
