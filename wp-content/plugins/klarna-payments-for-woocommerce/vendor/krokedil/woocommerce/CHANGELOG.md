# Changelog

All notable changes of krokedil/woocommerce are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

------------------
## [1.6.1] - 2024-05-13
### Fixed
* Reference to undefined method causes a fatal error.

## [1.6.0] - 2024-05-13
### Added
* Added support for "Gift Cards" by Woo.
* Added support for "PW WooCommerce Gift Cards" by Pimwick.
* Added support for "YITH WooCommerce Gift Cards" by YITH.
* Added support for "WooCommerce Smart Coupons" by StoreApps.

## [1.5.0] - 2024-03-12
### Added
* Added support for multiple taxes per order line item.

### Fixed
* Fixed an issue that caused a critical error if the cart contained any coupon. This issue was introduced in WC v.8.7.0 where the metadata "coupon_data" has been removed.

## [1.4.0] - 2024-01-08
### Added
* Added support for AvaTax.

## [1.3.1] - 2023-12-04
### Fixed
* Removed the extra shipping package that WC Subscriptions add when the cart contain a subscription with a free trial. We can remove the package because if it is a free trial, there should not be any cost for the customer. If we kept it, the customer would have been charged for the shipping cost.

## [1.3.0] - 2023-10-03

### Added
* Added support for WooCommerce Subscriptions. We will no longer add shipping cost if the order is a free trial subscription without any shipping costs

### Fixed
* We will now default to empty strings in customer details if no value can be found from the checkout object.

## [1.2.1] - 2023-06-20

### Changes
* We now use the first package returned when getting packages from the cart instead of the one with the key 0. This should improve compatibility with other plugins that might use something other then a incrementing integer for the package key. For example WooCommerce Advanced Shipping Packages, which uses the post id of the package as the key.

## [1.2.0] - 2023-06-19

### Added
* Added support standard Coupons.
* Added support for handling Store Api carts.

### Fixed
* Fixed and issue with how we handled YITH Giftcards.

## [1.1.0] - 2023-05-15

### Fixed
* Fixed some issues with bad references for coupon keys.

### Changed
* Fixed not returning the full shipping reference with instnace id for order shipping lines.

### Added
* Added filters for the full order line and cart line data when we are setting them in our Data classes. For example cart line items can now be filtered using the `cart_set_line_items` filter. Thank you [@fitimvata](https://github.com/fitimvata)!

## [1.0.3] - 2023-03-02

### Fixed

* Fixed getting the applied coupon amount from smart coupons instead of the coupon total amount.

## [1.0.2] - 2023-02-23

### Fixed

* Fixed an issue trying to calculate shipping lines without shipping being set.

## [1.0.1] - 2023-02-22

### Fixed

* Fixed support for Cart fees.

## [1.0.0] - 2023-01-13

### Added

* Initial release of the package.
