<?php
use Krokedil\KlarnaExpressCheckout\Session;
use WP_Mock\Tools\TestCase;

class SessionTest extends TestCase {
	public function setUp(): void {
		parent::setUp();
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	public function testConstructor() {
		// Expect actions to be added.
		WP_Mock::expectActionAdded( 'woocommerce_checkout_update_order_review', Session::class . '::on_update_order_review' );
		WP_Mock::expectActionAdded( 'woocommerce_thankyou', Session::class . '::unset_client_token', 1 );
		WP_Mock::expectActionAdded( 'woocommerce_thankyou', Session::class . '::unset_klarna_address', 1 );

		$session = new Session();
		$this->assertInstanceOf( Session::class, $session );
	}

	public function testCanSetClientToken() {
		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'set' )->with( 'kec_client_token', 'test_token' )->once();

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);

		$result = Session::set_client_token( 'test_token' );
		$this->assertTrue( $result );
	}

	public function testCantSetClientTokenIfSessionIsMissing() {
		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => null,
			)
		);

		$result = Session::set_client_token( 'test_token' );
		$this->assertFalse( $result );
	}

	public function testCanSetKlarnaAddress() {
		$address     = array(
			'field_1' => 'test_field_1',
			'field_2' => 'test_field_2',
		);
		$addressJson = json_encode( $address );

		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'set' )->with( 'kec_klarna_address', $addressJson )->once();

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);
		$result = Session::set_klarna_address( $address );
		$this->assertTrue( $result );
	}

	public function testCantSetKlarnaAddressIfSessionIsMissing() {
		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => null,
			)
		);

		$result = Session::set_klarna_address( array() );
		$this->assertFalse( $result );
	}

	public function testCanGetClientToken() {
		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )->with( 'kec_client_token', false )->andReturn( 'test_token' )->once();

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);

		$this->assertEquals( 'test_token', Session::get_client_token() );
	}

	public function testCantGetClientTokenIfSessionIsMissing() {
		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => null,
			)
		);

		$this->assertFalse( Session::get_client_token() );
	}

	public function testCanGetKlarnaAddress() {
		$address     = array(
			'field_1' => 'test_field_1',
			'field_2' => 'test_field_2',
		);
		$addressJson = json_encode( $address );

		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )->with( 'kec_klarna_address', false )->andReturn( $addressJson )->once();

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);

		$this->assertEquals( $address, Session::get_klarna_address() );
	}

	public function testCantGetKlarnaAddressIfSessionIsMissing() {
		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => null,
			)
		);

		$this->assertFalse( Session::get_klarna_address() );
	}

	public function testUnsetClientToken() {
		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( '__unset' )->with( 'kec_client_token' )->once();

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);
		$result = Session::unset_client_token();

		$this->assertTrue( $result );
	}

	public function testCantUnsetClientTokenIfSessionIsMissing() {
		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => null,
			)
		);
		$result = Session::unset_client_token();

		$this->assertFalse( $result );
	}

	public function testUnsetKlarnaAddress() {
		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( '__unset' )->with( 'kec_klarna_address' )->once();

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);

		$result = Session::unset_klarna_address();

		$this->assertTrue( $result );
	}

	public function testCantGetKlarnaAddressIfNotSet() {
		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )->with( 'kec_klarna_address', false )->andReturn( false )->once();

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);

		$this->assertFalse( Session::get_klarna_address() );
	}

	public function testCantUnsetKlarnaAddressIfSessionIsMissing() {
		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => null,
			)
		);

		$result = Session::unset_klarna_address();

		$this->assertFalse( $result );
	}

	public function testOnUpdateOrderReviewSkipsIfSessionsNotSet() {
		$this->expectNotToPerformAssertions();
		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )->with( 'kec_client_token', false )->andReturn( false )->once();
		$wcSession->shouldReceive( 'get' )->with( 'kec_klarna_address', false )->andReturn( false )->once();

		$wcSession->shouldNotReceive( 'set' );
		$wcSession->shouldNotReceive( '__unset' );
		$wcSession->shouldNotReceive( '__unset' );

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);

		Session::on_update_order_review( 'test' );
	}

	public function testOnUpdateOrderReviewSkipsIfKpNotUsed() {
		$this->expectNotToPerformAssertions();
		$address   = array( 'test' => 'test' );
		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )
			->with( 'kec_client_token', false )
			->andReturn( 'test_token' )
			->once();
		$wcSession->shouldReceive( 'get' )
			->with( 'kec_klarna_address', false )
			->andReturn( json_encode( $address ) )
		->once();

		$wcSession->shouldNotReceive( 'set' );
		$wcSession->shouldNotReceive( '__unset' );
		$wcSession->shouldNotReceive( '__unset' );

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);

		Session::on_update_order_review( 'payment_method=test' );
	}

	public function testOnUpdateOrderReviewCanValidateSuccessfully() {
		$this->expectNotToPerformAssertions();
		$klarna_address = array(
			'given_name' => 'test_name',
		);

		$posted_data = 'payment_method=klarna_payments&billing_first_name=test_name&shipping_first_name=test_name';

		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )
			->with( 'kec_client_token', false )
			->andReturn( 'test_token' )
			->once();
		$wcSession->shouldReceive( 'get' )
			->with( 'kec_klarna_address', false )
			->andReturn( json_encode( $klarna_address ) )
			->once();

		$wcSession->shouldNotReceive( 'set' );
		$wcSession->shouldNotReceive( '__unset' );
		$wcSession->shouldNotReceive( '__unset' );

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);

		Session::on_update_order_review( $posted_data );
	}

	public function testOnUpdateOrderReviewCanValidateSuccessfullyShipToDifferentAddress() {
		$this->expectNotToPerformAssertions();
		$klarna_address = array(
			'given_name' => 'test_name',
			'email'      => 'test_email',
		);

		$posted_data = 'payment_method=klarna_payments&shipping_first_name=test_name&ship_to_different_address=1';

		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )
			->with( 'kec_client_token', false )
			->andReturn( 'test_token' )
			->once();
		$wcSession->shouldReceive( 'get' )
			->with( 'kec_klarna_address', false )
			->andReturn( json_encode( $klarna_address ) )
			->once();

		$wcSession->shouldNotReceive( 'set' );
		$wcSession->shouldNotReceive( '__unset' );
		$wcSession->shouldNotReceive( '__unset' );

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session' => $wcSession,
			)
		);

		Session::on_update_order_review( $posted_data );
	}

	public function testOnUpdateOrderReviewCanInvalidateSessionWithMismatchShippingAddress() {
		$this->expectNotToPerformAssertions();
		$klarna_address = array(
			'given_name' => 'test_name',
		);

		$posted_data = 'payment_method=klarna_payments&shipping_first_name=test_different_name&billing_first_name=test_name';

		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )
			->with( 'kec_client_token', false )
			->andReturn( 'test_token' )
			->once();
		$wcSession->shouldReceive( 'get' )
			->with( 'kec_klarna_address', false )
			->andReturn( json_encode( $klarna_address ) )
			->once();

		$wcCustomer = Mockery::mock( 'overload:WC_Customer' );
		$wcCustomer->shouldReceive( 'set_props' )->with( array( 'shipping_first_name' => 'test_different_name' ) )->once();

		$wcSession->shouldReceive( 'set' )->with( 'reload_checkout', true )->once();
		$wcSession->shouldReceive( '__unset' )->with( 'kec_client_token' )->once();
		$wcSession->shouldReceive( '__unset' )->with( 'kec_klarna_address' )->once();

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session'  => $wcSession,
				'customer' => $wcCustomer,
			)
		);

		Session::on_update_order_review( $posted_data );
	}

	public function testOnUpdateOrderReviewCanInvalidateSessionWithMismatchBillingAddress() {
		$this->expectNotToPerformAssertions();
		$klarna_address = array(
			'given_name' => 'test_name',
		);

		$posted_data = 'payment_method=klarna_payments&shipping_first_name=test_name&billing_first_name=test_different_name';

		$wcSession = Mockery::mock( 'overload:WC_Session_Handler' );
		$wcSession->shouldReceive( 'get' )
			->with( 'kec_client_token', false )
			->andReturn( 'test_token' )
			->once();
		$wcSession->shouldReceive( 'get' )
			->with( 'kec_klarna_address', false )
			->andReturn( json_encode( $klarna_address ) )
			->once();

		$wcCustomer = Mockery::mock( 'overload:WC_Customer' );
		$wcCustomer->shouldReceive( 'set_props' )->with( array( 'billing_first_name' => 'test_different_name' ) )->once();

		$wcSession->shouldReceive( 'set' )->with( 'reload_checkout', true )->once();
		$wcSession->shouldReceive( '__unset' )->with( 'kec_client_token' )->once();
		$wcSession->shouldReceive( '__unset' )->with( 'kec_klarna_address' )->once();

		WP_Mock::userFunction( 'WC' )->andReturn(
			(object) array(
				'session'  => $wcSession,
				'customer' => $wcCustomer,
			)
		);

		Session::on_update_order_review( $posted_data );
	}
}
