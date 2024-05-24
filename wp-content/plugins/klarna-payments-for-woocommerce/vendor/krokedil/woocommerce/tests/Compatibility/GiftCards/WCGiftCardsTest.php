<?php
namespace Krokedil\WooCommerce\Tests\Compatibility\GiftCards;

use Krokedil\WooCommerce\Compatibility\Giftcards\WCGiftCards;
use WP_Mock\Tools\TestCase;

/**
 * Class SmartCouponsTest
 */
class WCGiftCardsTest extends TestCase {
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
	private function get_mock_wc_gc() {
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
			->setMethods( array( 'get_applied_gift_cards' ) )
			->getMock();

		$mock_cart->expects( $this->once() )
			->method( 'get_applied_gift_cards' )
			->willReturn(
				array(
					'giftcards' => array(
						array(
							'giftcard' => $this->get_mock_cart_giftcard( 'coupon1' ),
							'amount'   => 100,
						),
						array(
							'giftcard' => $this->get_mock_cart_giftcard( 'coupon2' ),
							'amount'   => 200,
						),
					),
				)
			);

		return $mock_cart;
	}

	/**
	 * Get a mock cart coupon with the given discount, code and discount type.
	 *
	 * @param int $discount The discount amount.
	 * @param string $code The coupon code.
	 * @param string $discount_type The discount type.
	 *
	 * @return \stdClass
	 */
	private function get_mock_cart_giftcard( $code ) {
		$mock_coupon = $this->getMockBuilder( 'stdClass' )
			->setMethods( array( 'get_data' ) )
			->getMock();

		$mock_coupon->expects( $this->once() )
			->method( 'get_data' )
			->willReturn( array( 'code' => $code ) );

		return $mock_coupon;
	}

	/**
	 * Get the mock WooCommerce order with coupons.
	 *
	 * @return \WC_Order
	 */
	private function get_mock_order() {
		// Arrange.
		$mock_order = $this->getMockBuilder( 'WC_Order' )
			->setMethods( array( 'get_items' ) )
			->getMock();

		$mock_coupon_1 = $this->get_mock_order_item_gift_card( 100, 'coupon1', 'smart_coupon' );
		$mock_coupon_2 = $this->get_mock_order_item_gift_card( 200, 'coupon2', 'store_credit' );

		$mock_order->expects( $this->once() )
			->method( 'get_items' )
			->with( 'gift_card' )
			->willReturn(
				array(
					$mock_coupon_1,
					$mock_coupon_2,
				)
			);

		return $mock_order;
	}

	/**
	 * Get a mock order item coupon with the given discount, code and discount type.
	 *
	 * @param int $discount The discount amount.
	 * @param string $code The coupon code.
	 * @param string $discount_type The discount type.
	 *
	 * @return \WC_Order_Item_Coupon
	 */
	private function get_mock_order_item_gift_card( $discount, $code, $discount_type ) {
		$mock_coupon = $this->getMockBuilder( 'WC_Order_Item_Coupon' )
			->setMethods( array( 'get_amount', 'get_code' ) )
			->getMock();

		$mock_coupon->expects( $this->once() )
			->method( 'get_amount' )
			->willReturn( $discount );

		$mock_coupon->expects( $this->once() )
			->method( 'get_code' )
			->willReturn( $code );

		return $mock_coupon;
	}

	/**
	 * Test get_cart_giftcards.
	 *
	 * @return void
	 */
	public function test_get_cart_giftcards_can_get_giftcards() {
		// Arrange.
		$mock_wc_gc = $this->get_mock_wc_gc();
		\WP_Mock::userFunction( 'WC_GC', array(
			'return' => $mock_wc_gc,
		) );

		// Act
		$smart_coupons = new WCGiftCards( $this->mock_package );
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
		$smart_coupons = new WCGiftCards( $this->mock_package );
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
