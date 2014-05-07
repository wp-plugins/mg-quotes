<?php

if (!defined('ABSPATH')) exit;

add_action('init', 'mg_qt_register_taxonomies');

function mg_qt_register_taxonomies() {
	register_taxonomy('mg_qt_category', 'mg_qt_quote', array(
		'description'           => 'Quote Categories',
		'public'                => true,
		'hierarchical'          => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'show_tagcloud'         => true,
		'meta_box_cb'           => null,
		'capabilities'          => array(),
		'rewrite' => array('slug' => __('Category', 'mg_qt')),
		'query_var'             => 'mg_qt_category',
		'update_count_callback' => ''
	));
	
	$labels = array(
		'name' => __('Authors', 'mg_qt'),
		'singular_name' => __('Author', 'mg_qt'),
		'search_items' => __('Search Authors', 'mg_qt'),
		'popular_items' => __('Popular Authors', 'mg_qt'),
		'all_items' => __('All Authors', 'mg_qt'),
		'edit_item' => __('Edit Author', 'mg_qt'),
		'view_item' => __('View Author', 'mg_qt'),
		'update_item' => __('Update Author', 'mg_qt'),
		'add_new_item' => __('Add New Author', 'mg_qt'),
		'new_item_name' => __('New Author Name', 'mg_qt'),
		//'separate_items_with_commas' => null, 
		//'add_or_remove_items' => null,
		//'choose_from_most_used' => null,
		'not_found' => __('No Authors found', 'mg_qt'),
	);
	
	register_taxonomy('mg_qt_author', 'mg_qt_quote', array(
		'labels'                => $labels,
		'description'           => 'Quote Authors',
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'show_tagcloud'         => true,
		'meta_box_cb'           => false,
		'capabilities'          => array(),
		'rewrite' => array('slug' => __('Author', 'mg_qt')),
		'query_var'             => 'mg_qt_author',
		'update_count_callback' => ''
	));
}
