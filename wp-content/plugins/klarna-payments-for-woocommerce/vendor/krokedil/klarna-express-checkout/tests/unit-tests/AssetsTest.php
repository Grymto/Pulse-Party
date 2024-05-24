<?php
use Krokedil\KlarnaExpressCheckout\Assets;
use WP_Mock\Tools\TestCase;

class AssetsTest extends TestCase {
	public function setUp(): void {
		parent::setUp();
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	private function getAssetsInstance() {
		$mockSettings = $this->getMockBuilder( 'Krokedil\KlarnaExpressCheckout\Settings' )
			->disableOriginalConstructor()
			->getMock();

		$mockSettings->method( 'is_enabled' )->willReturn( true );

		$assets = new Assets( $mockSettings );

		return $assets;
	}

	public function testConstructor() {
		$assets = $this->getAssetsInstance();

		$this->assertInstanceOf( Assets::class, $assets );
	}

	public function testCanRegisterAssets() {
		$this->expectNotToPerformAssertions();
		$assets = $this->getAssetsInstance();

		WP_Mock::userFunction( 'wp_register_style' )->with( 'kec-cart', Mockery::any(), Mockery::any(), Mockery::any() )->once();
		WP_Mock::userFunction( 'wp_register_script' )->with( 'klarnapayments', Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any() )->once();
		WP_Mock::userFunction( 'wp_register_script' )->with( 'kec-cart', Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any() )->once();
		WP_Mock::userFunction( 'wp_register_script' )->with( 'kec-checkout', Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any() )->once();

		$assets->register_assets();
	}

	public function testDoesNotEnqueueAssetsIfNotEnabled() {
		$this->expectNotToPerformAssertions();
		$mockSettings = $this->getMockBuilder( 'Krokedil\KlarnaExpressCheckout\Settings' )
			->disableOriginalConstructor()
			->getMock();

		$mockSettings->method( 'is_enabled' )->willReturn( false );

		WP_Mock::userFunction( 'is_cart' )->never();
		WP_Mock::userFunction( 'is_product' )->never();
		WP_Mock::userFunction( 'is_checkout' )->never();

		$assets = new Assets( $mockSettings );

		$assets->enqueue_assets();
	}

	public function testCanEnqueueCartAssets() {
		$this->expectNotToPerformAssertions();
		$assets = $this->getAssetsInstance();

		$wcAjax = Mockery::mock( 'overload:WC_AJAX' );
		$wcAjax->shouldReceive( 'get_endpoint' )->with( 'kec_get_payload' )->andReturn( 'test_endpoint' );
		$wcAjax->shouldReceive( 'get_endpoint' )->with( 'kec_auth_callback' )->andReturn( 'test_endpoint' );
		$wcAjax->shouldReceive( 'get_endpoint' )->with( 'kec_set_cart' )->andReturn( 'test_endpoint' );

		$wcProduct = Mockery::mock( 'overload:WC_Product' );
		$wcProduct->shouldReceive( 'get_id' )->andReturn( 1 );
		$wcProduct->shouldReceive( 'get_type' )->andReturn( 'simple' );

		WP_Mock::userFunction( 'get_the_ID' )->andReturn( 1 );
		WP_Mock::userFunction( 'wc_get_product' )->with( 1 )->andReturn( $wcProduct );

		WP_Mock::userFunction( 'is_cart' )->andReturn( true );
		WP_Mock::userFunction( 'is_product' )->andReturn( true );
		WP_Mock::userFunction( 'is_checkout' )->andReturn( false );

		WP_Mock::userFunction( 'wp_localize_script' )->with( 'kec-cart', 'kec_cart_params', Mockery::any() )->once();
		WP_Mock::userFunction( 'wp_enqueue_style' )->with( 'kec-cart' )->once();
		WP_Mock::userFunction( 'wp_enqueue_script' )->with( 'klarnapayments' )->once();
		WP_Mock::userFunction( 'wp_enqueue_script' )->with( 'kec-cart' )->once();

		$assets->enqueue_assets();
	}

	public function testCanEnqueueCheckoutAssets() {
		$this->expectNotToPerformAssertions();
		$wcSession = Mockery::mock( 'overload:WC_Session' );
		$wcSession->shouldReceive( 'get' )->with( 'kec_client_token', false )->andReturn( 'test_token' );

		WP_Mock::userFunction( 'WC' )->andReturn( (object) array( 'session' => $wcSession ) );

		// Create alias for the WC_AJAX class.
		$wcAjax = Mockery::mock( 'overload:WC_AJAX' );
		$wcAjax->shouldReceive( 'get_endpoint' )->with( 'kec_get_payload' )->andReturn( 'test_endpoint' );
		$wcAjax->shouldReceive( 'get_endpoint' )->with( 'checkout' )->andReturn( 'test_endpoint' );
		$wcAjax->shouldReceive( 'get_endpoint' )->with( 'kec_finalize_callback' )->andReturn( 'test_endpoint' );

		$assets = $this->getAssetsInstance();

		WP_Mock::userFunction( 'is_cart' )->andReturn( false );
		WP_Mock::userFunction( 'is_product' )->andReturn( false );
		WP_Mock::userFunction( 'is_checkout' )->andReturn( true );

		WP_Mock::userFunction( 'wp_localize_script' )->with( 'kec-checkout', 'kec_checkout_params', Mockery::any() )->once();
		WP_Mock::userFunction( 'wp_enqueue_script' )->with( 'klarnapayments' )->once();
		WP_Mock::userFunction( 'wp_enqueue_script' )->with( 'kec-checkout' )->once();

		$assets->enqueue_assets();
	}

	public function testDoesNotEnqueueCheckoutAssetsIfTokenMissing() {
		$this->expectNotToPerformAssertions();
		$wcSession = Mockery::mock( 'overload:WC_Session' );
		$wcSession->shouldReceive( 'get' )->with( 'kec_client_token', false )->andReturn( false );

		WP_Mock::userFunction( 'WC' )->andReturn( (object) array( 'session' => $wcSession ) );

		// Create alias for the WC_AJAX class.
		$wcAjax = Mockery::mock( 'overload:WC_AJAX' );
		$wcAjax->shouldReceive( 'get_endpoint' )->with( 'kec_get_payload' )->andReturn( 'test_endpoint' );
		$wcAjax->shouldReceive( 'get_endpoint' )->with( 'checkout' )->andReturn( 'test_endpoint' );
		$wcAjax->shouldReceive( 'get_endpoint' )->with( 'kec_finalize_callback' )->andReturn( 'test_endpoint' );

		$assets = $this->getAssetsInstance();

		WP_Mock::userFunction( 'is_cart' )->andReturn( false );
		WP_Mock::userFunction( 'is_product' )->andReturn( false );
		WP_Mock::userFunction( 'is_checkout' )->andReturn( true );

		WP_Mock::userFunction( 'wp_localize_script' )->with( 'kec-checkout', 'kec_checkout_params', Mockery::any() )->never();
		WP_Mock::userFunction( 'wp_enqueue_script' )->with( 'klarnapayments' )->never();
		WP_Mock::userFunction( 'wp_enqueue_script' )->with( 'kec-checkout' )->never();

		$assets->enqueue_assets();
	}
}
