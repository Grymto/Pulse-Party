<?php
namespace Krokedil\WooCommerce\Tests\Compatibility\GiftCards;

use Krokedil\WooCommerce\Compatibility\Giftcards\YITHGiftCards;
use WP_Mock\Tools\TestCase;

/**
 * Class SmartCouponsTest
 */
class YITHGiftCardsTest extends TestCase {
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
			->setMethods( array( 'cart' ) )
			->getMock();

		$mock_wc->cart = $this->get_mock_cart();

		return $mock_wc;
	}

	/**
	 * Get the mock WooCommerce cart with coupons.
	 *
	 * @return \stdClass
	 */
	private function get_mock_cart() {
		$mock_cart = $this->getMockBuilder( 'stdClass' )
			->setMethods( array( 'applied_gift_cards', 'applied_gift_cards_amounts' ) )
			->getMock();

		$mock_cart->applied_gift_cards = array( 'coupon1', 'coupon2' );
		$mock_cart->applied_gift_cards_amounts = array(
			'coupon1' => -100,
			'coupon2' => -200,
		);

		return $mock_cart;
	}

	/**
	 * Get the mock WooCommerce order with coupons.
	 *
	 * @return \WC_Order
	 */
	private function get_mock_order() {
		// Arrange.
		$mock_order = $this->getMockBuilder( 'WC_Order' )
			->setMethods( array( 'get_meta' ) )
			->getMock();

		$mock_order->expects( $this->once() )
			->method( 'get_meta' )
			->with( '_ywgc_applied_gift_cards' )
			->willReturn(
				array(
					'coupon1' => 100,
					'coupon2' => 200,
				)
			);

		return $mock_order;
	}

	/**
	 * Test get_cart_giftcards.
	 *
	 * @return void
	 */
	public function test_get_cart_giftcards_can_get_giftcards() {
		// Arrange.
		$mock_wc_gc = $this->get_mock_wc();
		\WP_Mock::userFunction( 'WC', array(
			'return' => $mock_wc_gc,
		) );

		// Act
		$smart_coupons = new YITHGiftCards( $this->mock_package );
		$actual = $smart_coupons->get_cart_giftcards();

		// Assert
		$this->assertCount( 2, $actual );
		$this->assertEquals( 'Gift card coupon1', $actual[0]->get_name() );
		$this->assertEquals( 'gift_card', $actual[0]->get_sku() );
		$this->assertEquals( 1, $actual[0]->get_quantity() );
		$this->assertEquals( -10000, $actual[0]->get_unit_price() );
		$this->assertEquals( -10000, $actual[0]->get_subtotal_unit_price() );
		$this->assertEquals( -10000, $actual[0]->get_total_amount() );
		$this->assertEquals( 0, $actual[0]->get_total_tax_amount() );
		$this->assertEquals( 0, $actual[0]->get_tax_rate() );
		$this->assertEquals( 'gift_card', $actual[0]->get_type() );

		$this->assertEquals( 'Gift card coupon2', $actual[1]->get_name() );
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
		$mock_order = $this->get_mock_order();

		// Act
		$smart_coupons = new YITHGiftCards( $this->mock_package );
		$actual = $smart_coupons->get_order_giftcards( $mock_order );

		// Assert
		$this->assertCount( 2, $actual );
		$this->assertEquals( 'Gift card coupon1', $actual[0]->get_name() );
		$this->assertEquals( 'gift_card', $actual[0]->get_sku() );
		$this->assertEquals( 1, $actual[0]->get_quantity() );
		$this->assertEquals( -10000, $actual[0]->get_unit_price() );
		$this->assertEquals( -10000, $actual[0]->get_subtotal_unit_price() );
		$this->assertEquals( -10000, $actual[0]->get_total_amount() );
		$this->assertEquals( 0, $actual[0]->get_total_tax_amount() );
		$this->assertEquals( 0, $actual[0]->get_tax_rate() );
		$this->assertEquals( 'gift_card', $actual[0]->get_type() );

		$this->assertEquals( 'Gift card coupon2', $actual[1]->get_name() );
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
