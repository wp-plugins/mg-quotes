<?php

if (!defined('ABSPATH')) exit;

function mg_qt_markup($quote) {
	if (empty($quote))
		return '';
		
	$quote['content'] = wptexturize($quote['content']);
	$quote['content'] = wpautop($quote['content']);
		
	ob_start();
	?>
		<blockquote class="mg_qt_quote">
			<?php echo $quote['content']; ?>
			<?php if (!empty($quote['author'])): ?>
				<footer class="meta">
					<cite class="author"><?php echo $quote['author']; ?></cite>
				</footer>
			<?php endif; ?>
		</blockquote>
	<?php
	$html = ob_get_clean();
	
	return apply_filters('mg_qt_quote_markup', $html, $quote);
}
