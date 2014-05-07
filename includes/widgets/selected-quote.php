<?php

if (!defined('ABSPATH')) exit;

class mg_qt_Selected_Quote extends WP_Widget {

	private $factory_settings = array(
		'title' => '', 
		'quote' => 0
	);

	public function __construct() {
		parent::__construct(
			'mg_qt_widget_selected_quote',
			__('Selected Quote', 'mg_qt'),
			array(
				'description' => __('Pick a selected quote', 'mg_qt'),
				'classname' => 'mg_qt_widget_selected_quote'
			) 
		);
	}
	
	public function form($instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		$title = esc_attr($instance['title']);
		$quotes = mg_qt_Query::quote_titles();
		
		$current_quote = $instance['quote'];
		if ($current_quote !== 0 && !isset($quotes[$current_quote]))
			$current_quote = 0;
		
		$title_field_id = $this->get_field_id('title');
		$title_field_name = $this->get_field_name('title');
		$quote_field_id = $this->get_field_id('quote');
		$quote_field_name = $this->get_field_name('quote');
		
		?>
			<p><label for="<?php echo $title_field_id; ?>"><?php _e('Title:', 'mg_qt'); ?></label> 
			<input class="widefat" id="<?php echo $title_field_id; ?>" name="<?php echo $title_field_name; ?>" type="text" value="<?php echo $title; ?>" /></p>
			<p>
				<label for="<?php echo $quote_field_id; ?>"><?php _e('Quote:', 'mg_qt'); ?></label> 
				<select id="<?php echo $quote_field_id?>" name="<?php echo $quote_field_name; ?>" class="widefat">
					<option value="0"<?php selected($current_quote, 0); ?>>Select a quote</option>
					<?php foreach ($quotes as $id => $title): ?>
						<?php
						if (strlen($title) > 30)
							$title = substr($title, 0, 30) . '...';
						?>
						<option 
							value="<?php echo esc_attr($id); ?>"
							<?php selected($current_quote, $id); ?>
						>
							<?php echo esc_html($title); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
		<?php
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['quote'] = absint($new_instance['quote']);
		
		return $instance;
	}
	
	public function widget($args, $instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		if ($instance['quote'] === 0)
			return;
			
		$quote = mg_qt_Query::quote_by_id($instance['quote']);
		if (empty($quote))
			return;
			
		$quote_markup = mg_qt_markup($quote);
		if ($quote_markup === '')
			return;
		
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		
		echo $before_widget;
		
		if ($title)
			echo $before_title . $title . $after_title;
			
		echo $quote_markup;
		
		echo $after_widget;
	}

}
