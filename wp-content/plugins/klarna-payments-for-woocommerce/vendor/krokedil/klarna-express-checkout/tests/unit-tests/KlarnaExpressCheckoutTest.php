<?php
use Krokedil\KlarnaExpressCheckout\AJAX;
use Krokedil\KlarnaExpressCheckout\Assets;
use Krokedil\KlarnaExpressCheckout\ClientTokenParser;
use Krokedil\KlarnaExpressCheckout\KlarnaExpressCheckout;
use Krokedil\KlarnaExpressCheckout\Session;
use Krokedil\KlarnaExpressCheckout\Settings;

use WP_Mock\Tools\TestCase;

class KlarnaExpressCheckoutTest extends TestCase {
	public function setUp(): void {
		WP_Mock::userFunction( 'get_option' )
			->with( 'test_key', array() )
			->andReturn(
				array(
					'kec_enabled'            => 'yes',
					'kec_credentials_secret' => 'test_credentials_secret',
					'kec_theme'              => 'default',
					'kec_shape'              => 'default',
				)
			);
		parent::setUp();
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	public function testConstructor() {
		// Create an instance of KlarnaExpressCheckout
		$kec = new KlarnaExpressCheckout( 'test_key' );

		$this->assertInstanceOf( KlarnaExpressCheckout::class, $kec );
		$this->assertInstanceOf( Session::class, $kec->session() );
		$this->assertInstanceOf( Settings::class, $kec->settings() );
		$this->assertInstanceOf( ClientTokenParser::class, $kec->client_token_parser() );
		$this->assertInstanceOf( Assets::class, $kec->assets() );
		$this->assertInstanceOf( AJAX::class, $kec->ajax() );
	}

	public function testGetPaymentButtonId() {
		$this->assertEquals( 'kec-pay-button', KlarnaExpressCheckout::get_payment_button_id() );
	}

	public function testMaybeUnhookKpActionsCanHandleNoToken() {
		$this->expectNotToPerformAssertions();

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => null,
			)
		);

		$kec = new KlarnaExpressCheckout( 'test_key' );

		$kec->maybe_unhook_kp_actions();
	}

	public function testMaybeUnhookKpActionsCanHandleKpNotExisting() {
		$this->expectNotToPerformAssertions();

		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )->with( 'kec_client_token', false )->andReturn( 'test_token' );

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);

		define( 'KP_WC', false );

		$kec = new KlarnaExpressCheckout( 'test_key' );

		$kec->maybe_unhook_kp_actions();
	}

	public function testMaybeUnhookKpActionsCanUnhookKp() {
		$this->expectNotToPerformAssertions();

		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )->with( 'kec_client_token', false )->andReturn( 'test_token' );

		$kpGatewayClass = Mockery::mock( 'overload:WC_Gateway_Klarna_Payments' );

		$wcGatewaysClass = Mockery::mock( 'overload:WC_Payment_Gateways' );
		$wcGatewaysClass->shouldReceive( 'get_available_payment_gateways' )->andReturn( array( 'klarna_payments' => $kpGatewayClass ) );

		$wcClass = Mockery::mock( 'overload:WC' );
		$wcClass->shouldReceive( 'payment_gateways' )->andReturn( $wcGatewaysClass );
		$wcClass->session = $wcSession;
		WP_Mock::userFunction( 'WC' )->andReturn( $wcClass );

		$kpSession  = Mockery::mock( 'overload:KP_Session' );
		$kpCheckout = Mockery::mock( 'overload:KP_Checkout' );

		WP_Mock::userFunction( 'KP_WC' )->andReturn(
			(object) array(
				'session'  => $kpSession,
				'checkout' => $kpCheckout,
			)
		);

		WP_Mock::userFunction( 'remove_action' )->with( 'woocommerce_after_calculate_totals', array( $kpSession, 'get_session' ), 999999 )->once();
		WP_Mock::userFunction( 'remove_filter' )->with( 'woocommerce_update_order_review_fragments', array( $kpCheckout, 'add_token_fragment' ) )->once();
		WP_Mock::userFunction( 'remove_action' )->with( 'woocommerce_review_order_before_submit', array( $kpCheckout, 'html_client_token' ) )->once();
		WP_Mock::userFunction( 'remove_action' )->with( 'woocommerce_pay_order_before_submit', array( $kpCheckout, 'html_client_token' ) )->once();
		WP_Mock::userFunction( 'remove_action' )->with( 'wc_get_template', array( $kpGatewayClass, 'override_kp_payment_option' ) )->once();

		$kec = new KlarnaExpressCheckout( 'test_key' );

		WP_Mock::expectActionAdded( 'wp_enqueue_scripts', array( $kec, 'dequeue_kp_scripts' ), 15 );

		$kec->maybe_unhook_kp_actions();
	}

	public function testCanAddKecButtonOnce() {
		WP_Mock::userFunction( 'get_option' )
			->with( 'test_key_enabled', array() )
			->andReturn(
				array(
					'kec_enabled' => 'yes',
				)
			);
		$kec = new KlarnaExpressCheckout( 'test_key_enabled' );

		WP_Mock::userFunction( 'did_action' )->with( 'woocommerce_single_product_summary' )->andReturnValues( array( 1, 2 ) );

		// Expect HTML output.
		ob_start();
		$kec->add_kec_button();
		$actualFirst = ob_get_clean();

		ob_start();
		$kec->add_kec_button();
		$actualSecond = ob_get_clean();

		$this->assertStringContainsString( '<div id="kec-pay-button"></div>', $actualFirst );
		$this->assertStringNotContainsString( '<div id="kec-pay-button"></div>', $actualSecond );
	}

	public function testCanNotAddKecButtonIfKecIsNotEnabled() {
		WP_Mock::userFunction( 'get_option' )
			->with( 'test_key_disabled', array() )
			->andReturn(
				array(
					'kec_enabled' => 'no',
				)
			);

		$kec = new KlarnaExpressCheckout( 'test_key_disabled' );

		WP_Mock::userFunction( 'did_action' )->with( 'woocommerce_single_product_summary' )->andReturn( 1 );

		// Expect HTML output.
		ob_start();
		$kec->add_kec_button();
		$actual = ob_get_clean();

		$this->assertStringNotContainsString( '<div id="kec-pay-button"></div>', $actual );
	}

	public function testCanDequeueKpScript() {
		$this->expectNotToPerformAssertions();
		WP_Mock::userFunction( 'wp_dequeue_script' )->with( 'klarna_payments' )->once();

		$kec = new KlarnaExpressCheckout( 'test_key' );

		$kec->dequeue_kp_scripts();
	}
}
