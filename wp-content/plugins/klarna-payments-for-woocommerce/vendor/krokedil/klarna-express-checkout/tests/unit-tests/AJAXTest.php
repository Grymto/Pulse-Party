<?php
use Krokedil\KlarnaExpressCheckout\AJAX;
use Krokedil\KlarnaExpressCheckout\ClientTokenParser;
use WP_Mock\Tools\TestCase;

class AJAXTest extends TestCase {
	/**
	 * @var AJAX
	 */
	private $ajax;

	/**
	 * @var ClientTokenParser
	 */
	private $client_token_parser;

	public function setUp(): void {
		$this->client_token_parser = Mockery::mock( 'Krokedil\KlarnaExpressCheckout\ClientTokenParser' );
		$this->ajax                = new AJAX( $this->client_token_parser );
	}

	public function tearDown(): void {
		Mockery::close();
	}

	public function testSetGetPayload() {
		$callable = function () {
			return 'test';
		};

		$this->ajax->set_get_payload( $callable );
		$this->assertEquals( $callable, $this->ajax->get_payload );
	}

	public function testAddAjaxEvents() {
		WP_Mock::expectActionAdded( 'wc_ajax_kec_get_payload', array( $this->ajax, 'kec_get_payload' ) );
		WP_Mock::expectActionAdded( 'wc_ajax_kec_auth_callback', array( $this->ajax, 'kec_auth_callback' ) );

		$this->ajax->add_ajax_events();

		WP_Mock::assertHooksAdded();
	}

	public function testCanSetFinalizeCallback() {
		$callable = function () {
			return 'test';
		};

		$this->ajax->set_finalize_callback( $callable );
		$this->assertEquals( $callable, $this->ajax->finalize_callback );
	}

	public function testKecGetPayload() {
		// No assertions will run, but WP_Mock will fail if the function is not called properly.
		$this->expectNotToPerformAssertions();

		$callable = function () {
			return array();
		};

		$this->ajax->set_get_payload( $callable );

		WP_Mock::userFunction(
			'wp_send_json_success',
			array(
				'args' => array(
					array(),
				),
			)
		);

		WP_Mock::userFunction(
			'check_ajax_referer',
			array(
				'args'   => array(
					'kec_get_payload',
					'nonce',
				),
				'return' => true,
			)
		);

		$this->ajax->kec_get_payload();
	}

	public function testKecGetPayloadException() {
		// No assertions will run, but WP_Mock will fail if the function is not called properly.
		$this->expectNotToPerformAssertions();

		$callable = function () {
			return null;
		};

		$this->ajax->set_get_payload( $callable );

		WP_Mock::userFunction(
			'wp_send_json_error',
			array(
				'args' => array(
					'Could not get a Payload for the cart',
				),
			)
		);

		WP_Mock::userFunction(
			'check_ajax_referer',
			array(
				'args'   => array(
					'kec_get_payload',
					'nonce',
				),
				'return' => true,
			)
		);

		$this->ajax->kec_get_payload();
	}
}
