<?php
use Krokedil\KlarnaExpressCheckout\ClientTokenParser;
use WP_Mock\Tools\TestCase;

class ClientTokenParserTest extends TestCase {
	/**
	 * @var ClientTokenParser
	 */
	private $client_token_parser;

	public function setUp(): void {
		$settings                  = Mockery::mock( 'Krokedil\KlarnaExpressCheckout\Settings' );
		$this->client_token_parser = new ClientTokenParser( $settings );
	}

	public function tearDown(): void {
		unset( $this->client_token_parser );
	}

	public function testParseToken() {
		// Dummy token with headers alg, typ. And payload with keys type, iat, exp.
		$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJrZXkiOiJrZXkiLCJ0eXBlIjoiY2xpZW50IiwiaWF0IjoxNTg0NjQ0NjQ3LCJleHAiOjE1ODQ2NDQ3MDd9.zsXVi7hgzqT8rEtSFcaiVHKPlaymT6x1GdObIP4V4_4';

		$parsed_token = $this->client_token_parser->parse( $token );

		$this->assertIsArray( $parsed_token );
		$this->assertArrayHasKey( 'header', $parsed_token );
		$this->assertArrayHasKey( 'payload', $parsed_token );
		$this->assertArrayHasKey( 'alg', $parsed_token['header'] );
		$this->assertArrayHasKey( 'typ', $parsed_token['header'] );
		$this->assertArrayHasKey( 'key', $parsed_token['payload'] );
		$this->assertArrayHasKey( 'type', $parsed_token['payload'] );
		$this->assertArrayHasKey( 'iat', $parsed_token['payload'] );
		$this->assertArrayHasKey( 'exp', $parsed_token['payload'] );
	}

	public function testParseInvalidTokenThrowsException() {
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Could not parse client token.' );

		$this->client_token_parser->parse( 'invalid_token' );
	}

	public function testParseInvalidTokenThrowsException2() {
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Could not parse client token.' );

		$this->client_token_parser->parse( 'invalid.token' );
	}

	public function testParseInvalidTokenThrowsExceptionBase64Decode() {
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Could not parse client token.' );

		$this->client_token_parser->parse( 'invalid.token.invalid' );
	}

	public function testParseInvalidTokenThrowsExceptionEmptyHeader() {
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Could not parse client token.' );

		$this->client_token_parser->parse( '.eyJrZXkiOiJrZXkiLCJ0eXBlIjoiY2xpZW50IiwiaWF0IjoxNTg0NjQ0NjQ3LCJleHAiOjE1ODQ2NDQ3MDd9.zsXVi7hgzqT8rEtSFcaiVHKPlaymT6x1GdObIP4V4_4' );
	}

	public function testParseInvalidTokenThrowsExceptionEmptyPayload() {
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Could not parse client token.' );

		$this->client_token_parser->parse( 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..eyJrZXkiOiJrZXkiLCJ0eXBlIjoiY2xpZW50IiwiaWF0IjoxNTg0NjQ0NjQ3LCJleHAiOjE1ODQ2NDQ3MDd9' );
	}
}
