<?php
/*
Plugin Name: WooCommerce Isotope
Plugin URI: https://github.com/eschmar/wp_woocommerce-isotope
Description: Isotope filtering of your products by category
Version: 1.0
Author: Marcel Eschmann
Author URI: https://github.com/eschmar
License: MIT
*/

// Enqueue necessary .css and .js files
function woocommerce_isotope_scripts() {
	wp_enqueue_style( 'wp_woocommerce-isotope', plugins_url().'/wp_woocommerce-isotope/css/style.css');
	/*wp_enqueue_script( 'masonry', plugins_url().'/wp_woocommerce-isotope/js/masonry.pkgd.min.js', array('jquery'), '3.1.2', true );*/
	wp_enqueue_script( 'isotope', plugins_url().'/wp_woocommerce-isotope/js/jquery.isotope.min.js', array('jquery'), '1.5.25', true );
	wp_enqueue_script( 'wp_woocommerce-isotope', plugins_url().'/wp_woocommerce-isotope/js/main.js', array('isotope'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'woocommerce_isotope_scripts' );


// Output a list of your categories to filter the product list
function woocommerce_isotope_render_list() {
	$terms = get_terms('product_cat');
	$total = 0;
	$output = '<ul class="isotope_filter">';
	$items = '';

	$select = '<select class="isotope_select_filter">';
	$select_items = '';

	$item_template = function($slug, $name, $count) {
		return "<li><a href='#' data-filter='.category-$slug'>$name<span class='badge'>$count</span></a></li>";
	};

	$item_select_template = function($slug, $name, $count) {
		return "<option value='.category-$slug'>$name ($count)</option>";
	};

	foreach ($terms as $term) {
		$total += $term->count;
		$items .= $item_template($term->slug, $term->name, $term->count);
		$select_items .= $item_select_template($term->slug, $term->name, $term->count);
	}

	$output .= "<li class='active'><a href='#' data-filter='*'>Alle<span class='badge'>$total</span></a></li>";
	$output .= $items.'</ul>';

	$select .= "<option value='*'>Alle ($total)</option>";
	$select .= $select_items.'</select>';

	echo $output.$select;
	return;
}
add_action( 'woocommerce_before_shop_loop', 'woocommerce_isotope_render_list');


// Insert category slug to every product list item: category_$slug
function woocommerce_isotope_cssclass($classes, $class, $ID) {
	if (is_tax('product_cat') || is_tax('product_tag') || is_post_type_archive('product')) {
		$categories = wp_get_object_terms( $ID, 'product_cat', 'slug' );
		foreach ($categories as $category) {
			array_push($classes, 'category-'.$category->slug);
		}
	}
	return $classes;
}
add_filter('post_class', 'woocommerce_isotope_cssclass', 10, 3);


// Enable template overriding from within the plugin
/*function woocommerce_isotope_template_override( $template, $template_name, $template_path ) {
	global $woocommerce;
	$path = untrailingslashit(plugin_dir_path( __FILE__ )).'/woocommerce/';

	// Override template if existing
	if ( file_exists( $path . $template_name ) ) {
		$template = $path . $template_name;
	}

	return $template;
}
add_filter( 'woocommerce_locate_template', 'woocommerce_isotope_template_override', 10, 3 );*/