<?php
namespace Krokedil\WooCommerce\Tests\Compatibility\GiftCards;

use Krokedil\WooCommerce\Compatibility\Giftcards\PWGiftCards;
use WP_Mock\Tools\TestCase;

/**
 * Class PWGiftCardsTest
 */
class PWGiftCardsTest extends TestCase {
	/**
	 * Mock for the package class.
	 *
	 * @var \Krokedil\WooCommerce\KrokedilWooCommerce
	 */
	private $mock_package;

	/**
	 * Init the test.
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		// Mock the package and the config method.
		$this->mock_package = $this->getMockBuilder( 'Krokedil\WooCommerce\KrokedilWooCommerce' )
			->disableOriginalConstructor()
			->getMock();

		$this->mock_package->method( 'config' )
			->willReturn(
				array(
					'slug'         => 'test-slug',
					'price_format' => 'minor',
				)
			);

		\WP_Mock::userFunction( 'wp_parse_args', array(
			'return' => array(
				'slug'         => 'test-slug',
				'price_format' => 'minor',
			),
		) );

		\WP_Mock::userFunction( 'wc_format_decimal', array(
			'return' => function( $price ) {
				return $price;
			},
		) );
	}

	/**
	 * Get the mock WooCommerce object.
	 *
	 * @return \stdClass
	 */
	private function get_mock_wc() {
		$mock_wc = $this->getMockBuilder( 'stdClass' )
			->setMethods( array( 'session' ) )
			->getMock();

		return $mock_wc;
	}

	/**
	 * Get the mock session object.
	 *
	 * @return \stdClass
	 */
	private function get_mock_session() {
		$mock_session = $this->getMockBuilder( 'stdClass' )
			->setMethods( array( 'get' ) )
			->getMock();

		$mock_session->expects( $this->once() )
			->method( 'get' )
			->with( 'pw-gift-card-data' )
			->willReturn(
				array(
					'gift_cards' => array(
						'giftcard1' => 100,
						'giftcard2' => 200,
					),
				)
			);

		return $mock_session;
	}

	/**
	 * Test get_cart_giftcards.
	 *
	 * @return void
	 */
	public function test_get_cart_giftcards_can_get_giftcards() {
		// Arrange.
		$mock_wc = $this->get_mock_wc();
		$mock_wc->session = $this->get_mock_session();

		\WP_Mock::userFunction( 'WC', array(
			'return' => $mock_wc,
		) );

		// Act
		$pw_giftcards = new PWGiftCards( $this->mock_package );
		$actual = $pw_giftcards->get_cart_giftcards();

		// Assert
		$this->assertCount( 2, $actual );
		$this->assertEquals( 'Gift card giftcard1', $actual[0]->get_name() );
		$this->assertEquals( 'gift_card', $actual[0]->get_sku() );
		$this->assertEquals( 1, $actual[0]->get_quantity() );
		$this->assertEquals( -10000, $actual[0]->get_unit_price() );
		$this->assertEquals( -10000, $actual[0]->get_subtotal_unit_price() );
		$this->assertEquals( -10000, $actual[0]->get_total_amount() );
		$this->assertEquals( 0, $actual[0]->get_total_tax_amount() );
		$this->assertEquals( 0, $actual[0]->get_tax_rate() );
		$this->assertEquals( 'gift_card', $actual[0]->get_type() );

		$this->assertEquals( 'Gift card giftcard2', $actual[1]->get_name() );
		$this->assertEquals( 'gift_card', $actual[1]->get_sku() );
		$this->assertEquals( 1, $actual[1]->get_quantity() );
		$this->assertEquals( -20000, $actual[1]->get_unit_price() );
		$this->assertEquals( -20000, $actual[1]->get_subtotal_unit_price() );
		$this->assertEquals( -20000, $actual[1]->get_total_amount() );
		$this->assertEquals( 0, $actual[1]->get_total_tax_amount() );
		$this->assertEquals( 0, $actual[1]->get_tax_rate() );
		$this->assertEquals( 'gift_card', $actual[1]->get_type() );
	}

	/**
	 * Test get_order_giftcards.
	 *
	 * @return void
	 */
	public function test_get_order_giftcards_can_get_giftcards() {
		// Arrange.
		$mock_wc = $this->get_mock_wc();
		$mock_wc->session = $this->get_mock_session();

		\WP_Mock::userFunction( 'WC', array(
			'return' => $mock_wc,
		) );

		// Act
		$pw_giftcards = new PWGiftCards( $this->mock_package );
		$actual = $pw_giftcards->get_order_giftcards( $this->getMockBuilder( 'WC_Order' )->getMock() );

		// Assert
		$this->assertCount( 2, $actual );
		$this->assertEquals( 'Gift card giftcard1', $actual[0]->get_name() );
		$this->assertEquals( 'gift_card', $actual[0]->get_sku() );
		$this->assertEquals( 1, $actual[0]->get_quantity() );
		$this->assertEquals( -10000, $actual[0]->get_unit_price() );
		$this->assertEquals( -10000, $actual[0]->get_subtotal_unit_price() );
		$this->assertEquals( -10000, $actual[0]->get_total_amount() );
		$this->assertEquals( 0, $actual[0]->get_total_tax_amount() );
		$this->assertEquals( 0, $actual[0]->get_tax_rate() );
		$this->assertEquals( 'gift_card', $actual[0]->get_type() );

		$this->assertEquals( 'Gift card giftcard2', $actual[1]->get_name() );
		$this->assertEquals( 'gift_card', $actual[1]->get_sku() );
		$this->assertEquals( 1, $actual[1]->get_quantity() );
		$this->assertEquals( -20000, $actual[1]->get_unit_price() );
		$this->assertEquals( -20000, $actual[1]->get_subtotal_unit_price() );
		$this->assertEquals( -20000, $actual[1]->get_total_amount() );
		$this->assertEquals( 0, $actual[1]->get_total_tax_amount() );
		$this->assertEquals( 0, $actual[1]->get_tax_rate() );
		$this->assertEquals( 'gift_card', $actual[1]->get_type() );
	}
}
