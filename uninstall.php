<?php

class mg_qt_Uninstall {

	public function __construct() {
		$this->delete_quotes();
		$this->delete_taxonomies();
		$this->remove_caps();
	}
	
	private function delete_quotes() {
		$quotes = get_posts(array(
			'post_type' => 'mg_qt_quote', 
			'post_status' => 'any', 
			'numberposts' => -1, 
			'fields' => 'ids'
		));

		if (!empty($quotes))
			foreach ($quotes as $quote)
				wp_delete_post($quote, true);
	}
	
	private function delete_taxonomies() {
		$tax_names = array('mg_qt_category', 'mg_qt_author');
		foreach ($tax_names as $tax_name) {
			$terms = $this->get_terms($tax_name);
			foreach ($terms as $term)
				wp_delete_term($term->term_id, $tax_name);
		}
	}
	
	private function remove_caps() {
		$admin_role = get_role('administrator');
		
		foreach ($this->get_caps() as $cap)
			$admin_role->remove_cap($cap);
	}
	
	private function get_terms($tax_name) {
		global $wpdb;

		$query = 'SELECT t.name, t.term_id
			FROM ' . $wpdb->terms . ' AS t
            INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt
            ON t.term_id = tt.term_id
            WHERE tt.taxonomy = "' . $tax_name . '"';

		return $wpdb->get_results($query);
	}
	
	private function get_caps() {
		return array(
			// Category custom taxonomy
			'manage_quote_categories',
			'edit_quote_categories',
			'delete_quote_categories',
			'assign_quote_categories',
			// Author custom taxonomy
			'manage_quote_authors',
			'edit_quote_authors',
			'delete_quote_authors',
			'assign_quote_authors',
			// CPT
			'edit_quotes',
			'edit_others_quotes',
			'publish_quotes',
			'read_private_quotes',
			'read',
			'delete_quotes',
			'delete_private_quotes',
			'delete_published_quotes',
			'delete_others_quotes',
			'edit_private_quotes',
			'edit_published_quotes'
		);
	}

}

new mg_qt_Uninstall();
