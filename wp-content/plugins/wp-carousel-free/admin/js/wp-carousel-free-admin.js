jQuery(document).ready(function ($) {

	/**
	 * Admin Preloader.
	 */
	$(".sp_wpcp_shortcode_generator .wpcf-wrapper").css("visibility", "hidden");
	$(".sp_wpcp_shortcode_generator .wpcf-wrapper").css("visibility", "visible");
	$(".sp_wpcp_shortcode_generator .wpcf-wrapper").css("background", "#fff");
	$(".sp_wpcp_shortcode_generator .wpcf-wrapper li").css("opacity", 1);

	// Smart Brand plugin installation process functionalities.
	$(document).on('click', '.brand-plugin-install:not(.activated,.activate_brand)', function (e) {
		e.preventDefault(); // Prevents the default click behavior
		var data = {
			action: 'wp_ajax_install_plugin', // Ajax action
			_ajax_nonce: $(this).data('nonce'), // nonce
			slug: 'smart-brands-for-woocommerce', // e.g. woocommerce
		};
		$('.brand-plugin-install').html('Installing...');
		jQuery.post(ajaxurl, data, function (response) {
			if (response.success) {
				const activateUrl = response.data.activateUrl; // Plugin activate URL
				$('.brand-plugin-install').addClass('activate_brand')
					.html('Activate Now')
					.attr('data-url', activateUrl)
					.removeClass('brand-plugin-install');
			}
		});
	});

	// Smart Brand plugin activation process functionalities.
	$(document).on('click', '.activate_brand', function (e) {
		e.preventDefault(); // Prevents the default click behavior
		$('.activate_brand').html('Activating ...'); // Changes the HTML content of elements with the class 'qv-active'
		var activateUrl = $(this).data('url'); // Plugin activate URL
		if (activateUrl) {
			$.ajax({
				url: activateUrl,
				type: 'GET', // Sets the HTTP request method to GET
				success: function () {
					$('.activate_brand').html('Activated').removeAttr('data-url').css({ 'text-decoration': 'none', 'pointer-events': 'none' });
				}
			});
		}
	});

	$(document).on('click', '.quick-view-install:not(.activated,.activate_plugin)', function (e) {
		e.preventDefault(); // Prevents the default click behavior
		var data = {
			action: 'wp_ajax_install_plugin', // Ajax action
			_ajax_nonce: $(this).data('nonce'), // nonce
			slug: 'woo-quickview', // e.g. woocommerce
		};

		$('.quick-view-install').html('Installing ...');
		jQuery.post(ajaxurl, data, function (response) {
			if (response.success) {
				const activateUrl = response.data.activateUrl; // Plugin activate URL
				$('.quick-view-install').addClass('activate_plugin')
					.html('Activate Now')
					.attr('data-url', activateUrl)
					.removeClass('quick-view-install');
			}
		});
	});

	// Smart Brand plugin activation process functionalities.	
	$(document).on('click', '.activate_plugin', function (e) {
		e.preventDefault(); // Prevents the default click behavior
		$('.activate_plugin').html('Activating ...'); // Changes the HTML content of elements with the class 'qv-active'
		var activateUrl = $(this).data('url'); // Plugin activate URL
		if (activateUrl) {
			$.ajax({
				url: activateUrl,
				type: 'GET', // Sets the HTTP request method to GET
				success: function () {
					$('.activate_plugin').html('Activated').removeAttr('data-url').css({ 'text-decoration': 'none', 'pointer-events': 'none' });
				}
			});
		}
	});

	if ($('.brand-plugin-install').hasClass('activated') || $('.quick-view-install').hasClass('activated')) { // after activating successfully remove the sub-messages from below the "product brand" button.
		$('.brand-plugin-install.activated, .quick-view-install.activated').html('Activated').parent('.wpcf-submessage-info').hide();
	}
});