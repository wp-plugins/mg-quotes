<?php

class mg_qt_Installer {

	public function __construct() {
		register_activation_hook(MG_QT_PLUGIN_FILE, array($this, 'install'));
	}
	
	public function install() {
		$this->assign_caps();
		$this->permalinks();
	}
	
	private function assign_caps() {
		$admin_role = get_role('administrator');
		
		foreach ($this->get_caps() as $cap)
			$admin_role->add_cap($cap);
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
	
	private function permalinks() {
		mg_qt_setup_post_type();
		mg_qt_register_taxonomies();
		
		flush_rewrite_rules();
	}

}

new mg_qt_Installer();
