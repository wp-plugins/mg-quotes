jQuery(document).ready(function($) {

	var
		input = $('#mg_qt_author_input')
	;
	
	input.suggest(
		ajaxurl + '?action=ajax-tag-search&tax=mg_qt_author',
		{ 
			delay: 500, 
			minchars: 2, 
			multiple: false, 
			//multipleSep: postL10n.comma + ' ' 
		} 
	);

});