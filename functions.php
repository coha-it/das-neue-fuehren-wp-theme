<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {
	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/* Deactivate Comments */
add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
function wcs_woo_remove_reviews_tab($tabs) {
	unset($tabs['reviews']);
	return $tabs;
}



/**
 * @snippet       Disable PayPal Based on Cart Total - WooCommerce
 * @author        Alexander Bachschmid
 * @testedwith    WooCommerce 3.5.6
 */

add_filter( 'woocommerce_available_payment_gateways', 'coha_disable_by_cart_value' );

function coha_disable_by_cart_value( $available_gateways ) {
	$cart_total = WC()->cart->total;
        $paypal_maximum = 500;
	$stripe_maximum = 999;

        if ( $cart_total > $paypal_maximum ) {
                unset( $available_gateways['paypal'] );
        }

	if ( $cart_total > $stripe_maximum ) {
		unset( $available_gateways['stripe'] );
	}

	//var_dump($available_gateways);

        return $available_gateways;
}



/**
 * @snippet       Disable PayPal Based on Cart Total - WooCommerce
 * @author        Alexander Bachschmid
 * @testedwith    WooCommerce 3.5.6
 */

add_filter( 'woocommerce_available_payment_gateways', 'coha_disable_invoice');

function coha_disable_invoice( $available_gateways ) {
        $minimum = 100;
        if ( WC()->cart->total < $minimum ) {
                unset( $available_gateways['invoice'] );
        }
        return $available_gateways;
}



/**
 * @snippet       hide coupon field on the cart page - WooCommerce
 * @author        Alexander Bachschmid
 * @testedwith    WooCommerce 3.5.6
 */

function disable_coupon_field_on_cart( $enabled ) {
	if ( is_cart() ) {
		$enabled = false;
	}
	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'disable_coupon_field_on_cart' );



/**
 * @snippet       hide coupon field on the checkout page - WooCommerce
 * @author        Alexander Bachschmid
 * @testedwith    WooCommerce 3.5.6
 */
/*function disable_coupon_field_on_checkout( $enabled ) {
	if ( is_checkout() ) {
		$enabled = false;
	}
	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'disable_coupon_field_on_checkout' );
*/

/**
 * @snippet       phone field not required - WooCommerce
 * @author        Alexander Bachschmid
 * @testedwith    WooCommerce 3.5.6
 */
/*function kb_no_required_phone( $address_fields ) {
	$address_fields['billing_phone']['required'] = false;
	return $address_fields;
}
add_filter( 'woocommerce_billing_fields', 'kb_no_required_phone', 10, 1 ); */

/**
 * @snippet       Custom Bestellbestaetigungs-Text - WooCommerce
 * @author        Alexander Bachschmid
 * @testedwith    WooCommerce 3.5.6
 */
function custom_order_received_text() {
	return 'Vielen Dank! <br>Ihre Bestellung ist bei uns eingegangen. Eine Bestätigung Ihrer Bestellung haben wir Ihnen auch per Mail zugesendet. Bitte sehen Sie ggf. auch im Spam-/Junk-Ordner nach.';
}
add_filter( 'woocommerce_thankyou_order_received_text', 'custom_order_received_text' );


/**
 * @snippet       Custom: Push AGB Buttons down
 * @author        Alexander Bachschmid
 * @testedwith    WooCommerce 3.5.6
 */
function change_wc_gzd_checkout() {
  if ( is_plugin_active( 'woocommerce-germanized/woocommerce-germanized.php' ) ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    ## Legal Text incl. checkbox before the checkout button
    remove_action( 'woocommerce_review_order_after_payment', 'woocommerce_gzd_template_checkout_legal',  wc_gzd_get_hook_priority( 'checkout_legal' ) );
    add_action( 'woocommerce_review_order_before_submit', 'woocommerce_gzd_template_checkout_legal', wc_gzd_get_hook_priority( 'checkout_legal' ) );
  }
}
add_action( 'wp_loaded', 'change_wc_gzd_checkout'  );



/**
 * Change the strength requirement on the woocommerce password
 *
 * Strength Settings
 * 4 = Strong
 * 3 = Medium (default)
 * 2 = Also Weak but a little stronger
 * 1 = Password should be at least Weak
 * 0 = Very Weak / Anything
 */
function coha_change_password_strength ($strength) {
	return 0;
}
add_filter( 'woocommerce_min_password_strength', 'coha_change_password_strength' );



function coha_strength_meter_settings( $params, $handle)
{
	if( $handle === 'wc-password-strength-meter' )
	{
		$params = array_merge( $params, array(
			'min_password_strength' => 0,
			'i18n_password_error' => 'Für ein sicheres Konto bitte ein stärkeres Passwort vergeben',
			'i18n_password_hint' => 'Bitte vergeben Sie ein Passwort mit einer <strong>Mindestlänge von 7 Zeichen</strong> und verwenden Sie eine Mischung aus <strong>GROSSBUCHSTABEN</strong> und <strong>kleinbuchstaben</strong>, <strong>Nummern</strong>, und <strong>Symbolen</strong> (zum Beispiel: <strong> ! " ? $ % ^ & </strong>).'
		) );
	}
	return $params;
}
add_filter( 'woocommerce_get_script_data', 'coha_strength_meter_settings', 20, 2);


function coha_password_messages()
{
	wp_localize_script( 'wc-password-strength-meter', 'pwsL10n', array(
		'short' => 'Zu kurz',
		'bad' => 'Besser, aber noch nicht sehr sicher',
		'good' => 'Sicher',
		'strong' => 'Sehr sicher',
		'mismatch' => 'Ihre Passwörter stimmen nicht überein, bitte versuchen Sie es erneut'
	) );
}
add_action( 'wp_enqueue_scripts',  'coha_password_messages', 9999 );

// Include Tracker
$wp_coha_trk_fjcxo = __DIR__.'/coha-tracking-client/wp_includer.php';
if (file_exists($wp_coha_trk_fjcxo)) {
	include $wp_coha_trk_fjcxo;
}

// Custom JavaScript
/* Custom script with no dependencies, enqueued in the header */
add_action('wp_enqueue_scripts', 'dnf_enqueue_custom_js');
function dnf_enqueue_custom_js() {
    wp_enqueue_script('custom', get_stylesheet_directory_uri().'/scripts/custom.js');
}

// Remove Fonts
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );
add_filter( 'astra_google_fonts', '__return_empty_array' );
add_filter( 'astra_google_fonts_selected', '__return_empty_array' );

