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
	wp_enqueue_style( 'woocommerce-category-filtering', plugins_url().'/woocommerce-category-filtering/css/style.css');
	/*wp_enqueue_script( 'masonry', plugins_url().'/woocommerce-category-filtering/js/masonry.pkgd.min.js', array('jquery'), '3.1.2', true );*/
	wp_enqueue_script( 'isotope', plugins_url().'/woocommerce-category-filtering/js/jquery.isotope.min.js', array('jquery'), '1.5.25', true );
	wp_enqueue_script( 'woocommerce-category-filtering', plugins_url().'/woocommerce-category-filtering/js/main.js', array('isotope'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'wooccf_scripts' );


// Output a list of your categories to filter the product list
function wooccf_render_list() {
	$terms = get_terms('product_cat');
	$total = 0;
	$output = '<ul class="isotope_filter">';
	$items = '';

	$item_template = function($slug, $name, $count) {
		return "<li><a href='#' data-filter='.category-$slug'>$name<span class='badge'>$count</span></a></li>";
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
function wooccf_cssclass($classes, $class, $ID) {
	if (is_tax('product_cat') || is_tax('product_tag') || is_post_type_archive('product')) {
		$categories = wp_get_object_terms( $ID, 'product_cat', 'slug' );
		foreach ($categories as $category) {
			array_push($classes, 'category-'.$category->slug);
		}
	}
	return $classes;
}
add_filter('post_class', 'wooccf_cssclass', 10, 3);


// Enable template overriding from within the plugin
/*function wooccf_template_override( $template, $template_name, $template_path ) {
	global $woocommerce;
	$path = untrailingslashit(plugin_dir_path( __FILE__ )).'/woocommerce/';

	// Override template if existing
	if ( file_exists( $path . $template_name ) ) {
		$template = $path . $template_name;
	}

	return $template;
}
add_filter( 'woocommerce_locate_template', 'wooccf_template_override', 10, 3 );*/