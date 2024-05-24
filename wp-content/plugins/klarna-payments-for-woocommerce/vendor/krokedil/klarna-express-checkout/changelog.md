# Changelog

All notable changes of krokedil/klarna-express-checkout are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]


------------------
## [1.3.1] - 2024-04-22
### Fix

* Fixed an issue where a new KEC button would be created on a variation product every time the customer selected a variation option. 
* Moved from client_key to client_id as the former has now been deprecated by Klarna.

## [1.3.0] - 2024-01-31
### Added

* Added support for passing locale to the KEC initialization.

### Fix

* We will no longer test against shipping address when the delivery address is forced to be the billing address.

## [1.2.1] - 2024-01-16
### Fix

* Removed the required flag from the Credentials Secret setting as it is not required.

## [1.2.0] - 2024-01-12
### Added

* Added handling to switch to Klarna Payments normal flow away from Klarna Express Checkout on a change to the shipping address in the checkout.

### Changed

* Changed the name of the Credentials Secret setting to Klarna Client Identifier and added links to the merchant portal where they can be found.

## [1.1.0] - 2023-12-15

### Added

* Added support for Product pages as well as the cart page.
* Added setting to disable the Klarna Express Checkout button.

### Changed

* Changed how we integrate the Klarna Express Checkout with Klarna Payments. We no longer set the Klarna session_id to hook into the Klarna Payments flow. But rather take over from the package if a KEC token is set.

## [1.0.0] - 2023-12-12

### Added

* Initial release of the package.
