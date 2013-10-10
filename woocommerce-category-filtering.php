<?php
/*
Plugin Name: WooCommerce Category Filtering
Plugin URI: https://github.com/eschmar/woocommerce-category-filtering
Description: Isotope style filtering of your products by category
Version: 1.0
Author: Marcel Eschmann
Author URI: https://github.com/eschmar
License: MIT
*/

// Enqueue necessary .css and .js files
function wooccf_scripts() {
	wp_enqueue_style( 'woocommerce-category-filtering', plugins_url().'/woocommerce-isotope-categories/css/style.css');
	wp_enqueue_script( 'masonry', plugins_url().'/woocommerce-isotope-categories/js/masonry.pkgd.min.js', array('jquery'), '3.1.2', true );
	wp_enqueue_script( 'isotope', plugins_url().'/woocommerce-isotope-categories/js/jquery.isotope.min.js', array('jquery'), '1.5.25', true );
	wp_enqueue_script( 'woocommerce-isotope-categories', plugins_url().'/woocommerce-isotope-categories/js/main.js', array('isotope'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'wooccf_scripts' );


// Output a list of your categories to filter the product list
function wooccf_render_list() {
	$terms = get_terms('product_cat');
	$total = 0;
	$output = '<ul class="isotope_container">';
	$items = '';

	$item_template = function($slug, $name, $count) {
		return "<li><a href='#' data-filter='category_$slug'>$name<span class='badge'>$count</span></a></li>";
	};

	foreach ($terms as $term) {
		$total += $term->count;
		$items .= $item_template($term->slug, $term->name, $term->count);
	}

	$output .= "<li><a href='#' data-filter='*'>Alle<span class='badge'>$total</span></a></li>";
	$output .= $items.'</ul>';

	echo $output;
	return;
}
add_action( 'woocommerce_before_shop_loop', 'wooccf_render_list');


// Insert category slug to every product list item: category_$slug
function wooccf_cssclass() {
	// TODO

	// available hooks: http://docs.woothemes.com/document/hooks/#templatehooks

	/*$categories = wp_get_object_terms( $product->id, 'product_cat', 'slug' );
	foreach ($categories as $category) {
		array_push($classes, 'category-'.$category->slug);
	}*/
}



function wooccf_template_override( $template, $template_name, $template_path ) {
	global $woocommerce;

	$_template = $template;
	if ( ! $template_path ){
		$template_path = $woocommerce->template_url;
	}

	$plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/woocommerce/';

	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			$template_path . $template_name,
			$template_name
		)
	);

	// Modification: Get the template from this plugin, if it exists
	if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
		$template = $plugin_path . $template_name;
	}
	
	// Use default template
	if ( ! $template ) {
		$template = $_template;
	}
	
	// Return what we found
	return $template;
}
add_filter( 'woocommerce_locate_template', 'wooccf_template_override', 10, 3 );