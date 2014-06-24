<?php

if (!defined('ABSPATH')) exit;

add_action('init', 'mg_qt_setup_post_type');

function mg_qt_setup_post_type() {
	$labels =  array(
		'name' 				=> __('Quotes', 'mg_qt'),
		'singular_name' 	=> __('Quote', 'mg_qt'),
		'add_new' 			=> __( 'Add New', 'mg_qt' ),
		'add_new_item' 		=> __( 'Add New Quote', 'mg_qt' ),
		'edit_item' 		=> __( 'Edit Quote', 'mg_qt' ),
		'new_item' 			=> __( 'New Quote', 'mg_qt' ),
		'all_items' 		=> __( 'All Quotes', 'mg_qt' ),
		'view_item' 		=> __( 'View Quote', 'mg_qt' ),
		'search_items' 		=> __( 'Search Quotes', 'mg_qt' ),
		'not_found' 		=> __( 'No Quotes found', 'mg_qt' ),
		'not_found_in_trash'=> __( 'No Quotes found in Trash', 'mg_qt' ),
		'menu_name' 		=> __( 'Quotes', 'mg_qt' )
	);
	
	$args = array(
		'labels'               => $labels,
		'description'          => '',
		'hierarchical'         => false,
		'public'               => true,
		'menu_icon'            => 'dashicons-format-quote',
		'capability_type'      => 'quote',
		'map_meta_cap'         => true,
		'supports'             => array('custom-fields', 'author'),
		'register_meta_box_cb' => null,
		'taxonomies'           => array(),
		'has_archive'          => false,
		'rewrite'              => array('slug' => 'quotes'),
		'query_var'            => true,
		'can_export'           => true,
		'delete_with_user'     => null,
		'_builtin'             => false,
		'_edit_link'           => 'post.php?post=%d'
	);
	
	register_post_type('mg_qt_quote', $args);
}

add_action('wp_insert_post_data', 'mg_qt_quote_title', 10, 2);

function mg_qt_quote_title($data, $postarr) {
	if ($data['post_type'] === 'mg_qt_quote' && empty($data['post_title'])) {
		$title = $data['post_content'];
		$title = strip_shortcodes($title);
		$title = apply_filters('the_content', $title);
		$title = str_replace(']]>', ']]&gt;', $title);
		$title = wp_trim_words($title, 10, '...');
		
		$data['post_title'] = $title;
	}
	
	return $data;
}