<?php

if (!defined('ABSPATH')) exit;

class mg_qt_ListTable {

	public function __construct() {
		add_filter('manage_mg_qt_quote_posts_columns', array($this, 'custom_columns'));
		//add_filter('manage_posts_custom_column', 'mg_qt_custom_columns_data', 10, 2);
		add_filter('months_dropdown_results', array($this, 'remove_months_dropdown'), 10, 2);
		add_action('restrict_manage_posts', array($this, 'taxonomy_dropdowns'));
		add_filter('post_row_actions', array($this, 'row_actions'), 10, 2);
	}
	
	function row_actions($actions, $post) {
		if ($post->post_type === 'mg_qt_quote')
			$actions = array_merge(array('id' => "ID: $post->ID"), $actions);
		
		return $actions;
	}

	function custom_columns($columns) {
		$columns = array(
			'cb' => '<input type="checkbox">',
			'title' => __('Title', 'mg_qt'),
			'taxonomy-mg_qt_author' => __('Author', 'mg_qt'),
			'taxonomy-mg_qt_category' => __('Category', 'mg_qt')
		);
		
		return $columns;
	}

	/* 
	function custom_columns_data($column_id, $post_id) {
		switch ($column_id) {
			case 'quote_author':
				
				break;
			case 'quote_category':
				 break;
		}
	}
	*/

	function remove_months_dropdown($months, $post_type) {
		return $post_type === 'mg_qt_quote' ? array() : $months;
	}

	function taxonomy_dropdowns() {
		global $typenow;
		
		if ($typenow !== 'mg_qt_quote')
			return;
			
		require_once MG_QT_INCLUDES . 'admin/tax-dropdown.php';
		
		$selected = get_query_var('mg_qt_author');
		if ($selected === '')
			$selected = 0;
			
		wp_dropdown_categories(array(
			'taxonomy' => 'mg_qt_author',
			'name' => 'mg_qt_author',
			'selected' => $selected,
			'walker' => new mg_qt_Walker_TaxonomyDropdown(),
			'show_option_all' => __('View all authors', 'mg_qt'),
			'hierarchical' => 0,
			'orderby' => 'name'
		));
		
		$selected = get_query_var('mg_qt_category');
		if ($selected === '')
			$selected = 0;
			
		wp_dropdown_categories(array(
			'taxonomy' => 'mg_qt_category',
			'name' => 'mg_qt_category',
			'selected' => $selected,
			'walker' => new mg_qt_Walker_TaxonomyDropdown(),
			'show_option_all' => __('View all categories', 'mg_qt'),
			'hierarchical' => 1,
			'orderby' => 'name'
		));
	}

}

new mg_qt_ListTable();