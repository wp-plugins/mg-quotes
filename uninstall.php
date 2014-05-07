<?php

class mg_qt_Uninstall {

	public function __construct() {
		$this->delete_quotes();
		$this->delete_taxonomies();
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
	
	private function get_terms($tax_name) {
		global $wpdb;

		$query = 'SELECT t.name, t.term_id
			FROM ' . $wpdb->terms . ' AS t
            INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt
            ON t.term_id = tt.term_id
            WHERE tt.taxonomy = "' . $tax_name . '"';

		return $wpdb->get_results($query);
	}

}

new mg_qt_Uninstall();
