<?php
/**
 * OntariosBest Child Theme Functions
 * Parent Theme: Astra
 */

// Enqueue parent and child theme styles
add_action( 'wp_enqueue_scripts', 'ontariosbest_enqueue_styles' );
function ontariosbest_enqueue_styles() {
	wp_enqueue_style(
		'astra-child-theme-css',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'astra-theme-css' ),
		wp_get_theme()->get( 'Version' )
	);
}

// -------------------------------------------------------
// Custom Post Types
// -------------------------------------------------------

add_action( 'init', 'ontariosbest_register_post_types' );
function ontariosbest_register_post_types() {

	// Casino Reviews
	register_post_type( 'casino', array(
		'labels'      => array(
			'name'          => 'Casinos',
			'singular_name' => 'Casino',
			'add_new_item'  => 'Add New Casino',
			'edit_item'     => 'Edit Casino',
		),
		'public'      => true,
		'has_archive' => true,
		'rewrite'     => array( 'slug' => 'casinos' ),
		'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'menu_icon'   => 'dashicons-tickets-alt',
		'show_in_rest' => true,
	) );

	// Travel Listings
	register_post_type( 'travel', array(
		'labels'      => array(
			'name'          => 'Travel',
			'singular_name' => 'Travel Listing',
			'add_new_item'  => 'Add New Travel Listing',
		),
		'public'      => true,
		'has_archive' => true,
		'rewrite'     => array( 'slug' => 'travel' ),
		'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'menu_icon'   => 'dashicons-location-alt',
		'show_in_rest' => true,
	) );

	// Entertainment Listings
	register_post_type( 'entertainment', array(
		'labels'      => array(
			'name'          => 'Entertainment',
			'singular_name' => 'Entertainment Listing',
			'add_new_item'  => 'Add New Entertainment Listing',
		),
		'public'      => true,
		'has_archive' => true,
		'rewrite'     => array( 'slug' => 'entertainment' ),
		'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'menu_icon'   => 'dashicons-star-filled',
		'show_in_rest' => true,
	) );

	// Services Listings
	register_post_type( 'service', array(
		'labels'      => array(
			'name'          => 'Services',
			'singular_name' => 'Service',
			'add_new_item'  => 'Add New Service',
		),
		'public'      => true,
		'has_archive' => true,
		'rewrite'     => array( 'slug' => 'services' ),
		'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'menu_icon'   => 'dashicons-hammer',
		'show_in_rest' => true,
	) );

	// Restaurant Listings
	register_post_type( 'restaurant', array(
		'labels'      => array(
			'name'          => 'Restaurants',
			'singular_name' => 'Restaurant',
			'add_new_item'  => 'Add New Restaurant',
			'edit_item'     => 'Edit Restaurant',
		),
		'public'      => true,
		'has_archive' => true,
		'rewrite'     => array( 'slug' => 'restaurants' ),
		'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'menu_icon'   => 'dashicons-food',
		'show_in_rest' => true,
	) );

	// Shopping Listings (Phase 2)
	register_post_type( 'shopping', array(
		'labels'      => array(
			'name'          => 'Shopping',
			'singular_name' => 'Shopping Listing',
			'add_new_item'  => 'Add New Shopping Listing',
		),
		'public'      => true,
		'has_archive' => true,
		'rewrite'     => array( 'slug' => 'shopping' ),
		'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'menu_icon'   => 'dashicons-cart',
		'show_in_rest' => true,
	) );
}

// -------------------------------------------------------
// Casino Taxonomies
// -------------------------------------------------------

add_action( 'init', 'ontariosbest_register_taxonomies' );
function ontariosbest_register_taxonomies() {

	// Casino Features (e.g. Live Dealer, Mobile App, Crypto)
	register_taxonomy( 'casino_feature', 'casino', array(
		'label'        => 'Casino Features',
		'rewrite'      => array( 'slug' => 'casino-features' ),
		'hierarchical' => false,
		'show_in_rest' => true,
	) );

	// Casino Payment Methods
	register_taxonomy( 'payment_method', 'casino', array(
		'label'        => 'Payment Methods',
		'rewrite'      => array( 'slug' => 'payment-methods' ),
		'hierarchical' => false,
		'show_in_rest' => true,
	) );

	// Travel Region
	register_taxonomy( 'travel_region', 'travel', array(
		'label'        => 'Region',
		'rewrite'      => array( 'slug' => 'travel-region' ),
		'hierarchical' => true,
		'show_in_rest' => true,
	) );

	// Entertainment Type
	register_taxonomy( 'entertainment_type', 'entertainment', array(
		'label'        => 'Entertainment Type',
		'rewrite'      => array( 'slug' => 'entertainment-type' ),
		'hierarchical' => true,
		'show_in_rest' => true,
	) );

	// Restaurant Cuisine
	register_taxonomy( 'restaurant_cuisine', 'restaurant', array(
		'label'        => 'Cuisine',
		'rewrite'      => array( 'slug' => 'cuisine' ),
		'hierarchical' => false,
		'show_in_rest' => true,
	) );

	// Service Category
	register_taxonomy( 'service_category', 'service', array(
		'label'        => 'Service Category',
		'rewrite'      => array( 'slug' => 'service-category' ),
		'hierarchical' => true,
		'show_in_rest' => true,
	) );

	// Shared region taxonomy across all non-casino post types
	register_taxonomy( 'listing_region', array( 'restaurant', 'travel', 'entertainment', 'service', 'shopping' ), array(
		'label'        => 'Region',
		'rewrite'      => array( 'slug' => 'region' ),
		'hierarchical' => true,
		'show_in_rest' => true,
	) );
}

// -------------------------------------------------------
// Responsible Gambling Footer Notice
// Required for Ontario casino affiliate compliance
// -------------------------------------------------------

add_action( 'wp_footer', 'ontariosbest_rg_notice' );
function ontariosbest_rg_notice() {
	// Only show on casino-related pages
	if ( is_singular( 'casino' ) || is_post_type_archive( 'casino' ) ) {
		echo '<div class="rg-notice" style="background:#1a1a1a;color:#aaa;text-align:center;padding:12px;font-size:13px;">';
		echo '19+ | Gambling can be addictive. Please play responsibly. ';
		echo '<a href="https://connexontario.ca" target="_blank" rel="nofollow" style="color:#e8a020;">ConnexOntario: 1-866-531-2600</a> | ';
		echo '<a href="/responsible-gambling/" style="color:#e8a020;">Responsible Gambling</a>';
		echo '</div>';
	}
}

// -------------------------------------------------------
// Affiliate Disclosure
// -------------------------------------------------------

add_filter( 'the_content', 'ontariosbest_affiliate_disclosure' );
function ontariosbest_affiliate_disclosure( $content ) {
	if ( is_singular( array( 'casino', 'travel', 'entertainment', 'service', 'restaurant', 'shopping' ) ) || is_singular( 'post' ) ) {
		$disclosure = '<p class="affiliate-disclosure" style="background:#f5f5f5;border-left:4px solid #e8a020;padding:10px 14px;font-size:13px;margin-bottom:20px;">';
		$disclosure .= '<strong>Disclosure:</strong> OntariosBest.com may earn a commission when you click links on this page. This helps us keep the site free. We only recommend services we have reviewed.';
		$disclosure .= '</p>';
		$content = $disclosure . $content;
	}
	return $content;
}

// -------------------------------------------------------
// Casino Meta Fields (helper to get/display)
// -------------------------------------------------------

/**
 * Get casino meta with fallback
 */
function ob_casino_meta( $key, $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	return get_post_meta( $post_id, $key, true );
}

/**
 * Render star rating HTML (1–5 scale, supports half stars)
 */
function ob_render_stars( $rating, $max = 5 ) {
	$output = '<div class="ob-stars" aria-label="Rating: ' . esc_attr( $rating ) . ' out of ' . $max . '">';
	for ( $i = 1; $i <= $max; $i++ ) {
		if ( $rating >= $i ) {
			$output .= '<span class="star full">★</span>';
		} elseif ( $rating >= $i - 0.5 ) {
			$output .= '<span class="star half">★</span>';
		} else {
			$output .= '<span class="star empty">☆</span>';
		}
	}
	$output .= '<span class="rating-number">' . number_format( (float) $rating, 1 ) . '</span>';
	$output .= '</div>';
	return $output;
}

// -------------------------------------------------------
// Schema Markup for Casino Reviews
// -------------------------------------------------------

add_action( 'wp_head', 'ontariosbest_casino_schema' );
function ontariosbest_casino_schema() {
	if ( ! is_singular( 'casino' ) ) {
		return;
	}

	$post_id     = get_the_ID();
	$name        = get_the_title();
	$description = get_the_excerpt();
	$rating      = ob_casino_meta( '_casino_overall_rating' );
	$url         = get_permalink();

	if ( ! $rating ) {
		return;
	}

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Review',
		'name'        => $name . ' Review',
		'description' => $description,
		'url'         => $url,
		'reviewRating' => array(
			'@type'       => 'Rating',
			'ratingValue' => $rating,
			'bestRating'  => '5',
			'worstRating' => '1',
		),
		'author' => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		),
		'publisher' => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url(),
		),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}

// -------------------------------------------------------
// Generic listing meta helper
// -------------------------------------------------------

/**
 * Get post meta for any listing post type
 */
function ob_listing_meta( $key, $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	return get_post_meta( $post_id, $key, true );
}

// -------------------------------------------------------
// LocalBusiness Schema for non-casino listings
// -------------------------------------------------------

add_action( 'wp_head', 'ontariosbest_listing_schema' );
function ontariosbest_listing_schema() {
	$listing_types = array( 'restaurant', 'travel', 'entertainment', 'service', 'shopping' );
	if ( ! is_singular( $listing_types ) ) {
		return;
	}

	$post_id     = get_the_ID();
	$name        = get_the_title();
	$description = get_the_excerpt();
	$url         = get_permalink();
	$rating      = ob_listing_meta( '_listing_overall_rating' );
	$address     = ob_listing_meta( '_listing_address' );
	$phone       = ob_listing_meta( '_listing_phone' );
	$website     = ob_listing_meta( '_listing_website' );

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'LocalBusiness',
		'name'        => $name,
		'description' => $description,
		'url'         => $url,
	);

	if ( $address ) {
		$schema['address'] = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $address,
			'addressRegion'   => 'ON',
			'addressCountry'  => 'CA',
		);
	}
	if ( $phone ) {
		$schema['telephone'] = $phone;
	}
	if ( $website ) {
		$schema['sameAs'] = $website;
	}
	if ( $rating ) {
		$schema['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => $rating,
			'bestRating'  => '5',
			'worstRating' => '1',
			'ratingCount' => '1',
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}

// -------------------------------------------------------
// Sponsored/Featured listing helpers
// -------------------------------------------------------

/**
 * Returns true if a listing is marked as featured (pinned to top)
 */
function ob_is_featured( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	return (bool) get_post_meta( $post_id, '_listing_featured', true );
}

/**
 * Returns the sponsorship badge label or false
 */
function ob_sponsored_badge( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	return get_post_meta( $post_id, '_listing_sponsored_label', true );
}

// -------------------------------------------------------
// -------------------------------------------------------
// Theme Setup
// -------------------------------------------------------

add_action( 'after_setup_theme', 'ontariosbest_setup' );
function ontariosbest_setup() {

	// Post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Title tag handled by WordPress
	add_theme_support( 'title-tag' );

	// Custom logo
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// HTML5 markup
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption', 'style', 'script' ) );

	// Navigation menus
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'ontariosbest' ),
		'footer'  => __( 'Footer Menu', 'ontariosbest' ),
	) );

	// Custom image sizes
	add_image_size( 'casino-logo',   200, 100, false );
	add_image_size( 'listing-thumb', 400, 300, true  );
}

// -------------------------------------------------------
// Enqueue Google Fonts (Inter)
// -------------------------------------------------------

add_action( 'wp_enqueue_scripts', 'ontariosbest_fonts' );
function ontariosbest_fonts() {
	wp_enqueue_style(
		'ontariosbest-inter',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap',
		array(),
		null
	);
}

// -------------------------------------------------------
// Excerpt length
// -------------------------------------------------------

add_filter( 'excerpt_length', function() { return 28; }, 999 );
add_filter( 'excerpt_more',   function() { return '…'; } );

// -------------------------------------------------------
// Remove WordPress version from head (security)
// -------------------------------------------------------

remove_action( 'wp_head', 'wp_generator' );

// -------------------------------------------------------
// Flush rewrite rules on theme activation
// -------------------------------------------------------

add_action( 'after_switch_theme', function() {
	ontariosbest_register_post_types();
	ontariosbest_register_taxonomies();
	flush_rewrite_rules();
} );
