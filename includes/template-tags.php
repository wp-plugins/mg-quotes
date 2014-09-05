<?php

if (!defined('ABSPATH')) exit;

/*
 * Get a single quote, specified by its ID
 */
function mg_qt_get_quote($id) {
	return mg_qt_markup(mg_qt_Query::quote_by_id($id));
}

/*
 * Pick one random quote, optionally from the category and/or the author specified(by they names)
 */
function mg_qt_get_rnd_quote($category = null, $author = null) {
	switch (2 * !empty($category) + !empty($author)) {
		case 0:
			$quote = mg_qt_Query::rnd_quote();
			break;
		case 1:
			$quote = mg_qt_Query::rnd_quote_from_author_name($author);
			break;
		case 2:
			$quote = mg_qt_Query::rnd_quote_from_category_name($category);
			break;
		case 3:
			$category = get_term_by('name', $category, 'mg_qt_category');
			$author = get_term_by('name', $author, 'mg_qt_author');
			$quote = mg_qt_Query::rnd_quote_from_cat_and_author($category, $author);
			break;
	}
		
	return mg_qt_markup($quote);
}

/*
 * echoing template tags
 */
 
function mg_qt_quote($id) {
	echo mg_qt_get_quote($id);
}
 
function mg_qt_rnd_quote($category = null, $author = null){
	echo mg_qt_get_rnd_quote($category, $author);
}
