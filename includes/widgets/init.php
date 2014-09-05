<?php

if (!defined('ABSPATH')) exit;

add_action('widgets_init', 'mg_qt_register_widgets');

function mg_qt_register_widgets() {
	register_widget('mg_qt_Random_Quote');
	register_widget('mg_qt_Selected_Quote');
}

require_once 'random-quote.php';
require_once 'selected-quote.php';