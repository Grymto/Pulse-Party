jQuery( function ( $ ) {
    var klarna_onsite_messaging = {
        refresh: function () {
            if ( window.Klarna && window.Klarna.OnsiteMessaging ) {
                window.Klarna.OnsiteMessaging.refresh()
            }
            $( "klarna-placement" ).show()
        },

        check_variable: function () {
            if ( $( ".product-type-variable" )[ 0 ] && $( ".variation_id" ).val() === "0" ) {
                // Product is a variable product and variable is not set
                return false
            }
            return true
        },

        update_total_cart: function ( price ) {
            if ( ! price ) {
                price = Math.round( $( "#kosm_cart_total" ).val() )
            }

            if ( ! isNaN( price ) ) {
                klarna_onsite_messaging.update_total_price( price * 100 )
            }
        },

        update_total_variation: function ( variation ) {
            let price = Math.round( variation.display_price * 100 )
            klarna_onsite_messaging.update_total_price( price )
        },

        update_total_price: function ( price ) {
            /* If the price is undefined, OSM won't appear. Let this be known. */
            if ( ! price ) {
                console.warn( "OSM price error: ", price )
            }

            $( "klarna-placement" ).each( function () {
                $( this ).attr( "data-total_amount", price )
                $( this ).attr( "data-purchase-amount", price )
            } )

            klarna_onsite_messaging.refresh()
        },

        debug_info: function () {
            if ( /[?&]osmDebug/.test( location.search ) ) {
                const d = klarna_onsite_messaging_params.debug_info

                if ( typeof d !== "undefined" ) {
                    console.log( "%cDebug info: ", "color: #ff0000" )
                    for ( [ key, value ] of Object.entries( d ) ) {
                        console.log( `${ key }: ${ value }` )
                    }

                    if (
                        typeof window.KlarnaOnsiteService !== "undefined" &&
                        typeof window.KlarnaOnsiteService.loaded !== "undefined"
                    ) {
                        console.log( "Klarna loaded status: " + window.KlarnaOnsiteService.loaded )
                    }
                }
            }
        },

        init: function () {
            $( document ).ready( function () {
                if ( false === klarna_onsite_messaging.check_variable ) {
                    $( "klarna-placement" ).hide()
                } else {
                    $( "klarna-placement" ).show()
                }

                klarna_onsite_messaging.debug_info()
                klarna_onsite_messaging.refresh()
            } )

            $( document.body ).on( "updated_cart_totals", function () {
                $.ajax( {
                    url: klarna_onsite_messaging_params.get_cart_total_url,
                    type: "GET",
                    dataType: "json",
                    success: function ( response ) {
                        console.log( "success", response )
                        klarna_onsite_messaging.update_total_cart( response.data )
                    },
                    error: function ( response ) {
                        console.log( response )
                    },
                } )
            } )

            $( document ).on( "found_variation", function ( e, variation ) {
                klarna_onsite_messaging.update_total_variation( variation )
            } )

            /* "WooCommerce Measurement Price Calculator". */

            // Measurement with a "total price". These have more than one field/unit.
            $( ".total_price" ).on(
                "wc-measurement-price-calculator-total-price-change",
                function ( e, quantity, price ) {
                    if ( price ) {
                        klarna_onsite_messaging.update_total_price( Math.round( quantity * price * 100 ) )
                    }
                },
            )

            // Triggered when the customer manually adjusts the quantity (rather than the measurements).
            $( "form.cart" ).on( "wc-measurement-price-calculator-quantity-changed", function ( e, quantity ) {
                if ( quantity ) {
                    price = 100 * quantity * parseFloat( wc_price_calculator_params.product_price )
                    klarna_onsite_messaging.update_total_price( price )
                }
            } )

            // Measurements with a "product price". These have a single field/unit.
            // The product price does not account for quantity, instead we have to account for this when updating OSM.
            $( ".product_price" ).on(
                "wc-measurement-price-calculator-product-price-change",
                function ( e, measurement, price ) {
                    quantity = parseInt( $( "form.cart .qty" ).val() )
                    if ( price && quantity > 0 ) {
                        klarna_onsite_messaging.update_total_price( Math.round( price * 100 * quantity ) )
                    }
                },
            )

            $( "form.cart" ).on( "change", "input.qty", function () {
                if ( typeof wc_price_calculator_params === "undefined" ) {
                    return
                }

                quantity = this.value
                unit_price = parseFloat( $( ".product_price span" ).text() ) // NaN - if the customer has not yet entered any units in the MPC fields.

                if ( quantity > 0 ) {
                    price = 100 * quantity * parseFloat( wc_price_calculator_params.product_price )
                    if ( ! isNaN( unit_price ) ) {
                        price = 100 * unit_price * quantity
                    }

                    klarna_onsite_messaging.update_total_price( price )
                }
            } )
        },
    }
    klarna_onsite_messaging.init()
} )
