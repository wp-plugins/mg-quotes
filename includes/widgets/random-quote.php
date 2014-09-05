<?php

if (!defined('ABSPATH')) exit;

class mg_qt_Random_Quote extends WP_Widget {

	private $factory_settings = array(
		'title' => '',
		'category' => 0,
		'author' => 0
	);

	public function __construct() {
		parent::__construct(
			'mg_qt_widget_random_quote',
			__('mg Quotes: Random', 'mg_qt'),
			array(
				'description' => __('Pick a random quotes. Supports categories and authors', 'mg_qt'),
				'classname' => 'mg_qt_widget_random_quote'
			) 
		);
	}
	
	public function form($instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		$title = esc_attr($instance['title']);
		
		$category = $instance['category'];
		$display_cat_select = mg_qt_Query::there_are_categories();
		if ($display_cat_select) {
			if ($category > 0 && !mg_qt_Query::category_exists($category) )
			$category = 0;
		}
		
		$author = $instance['author'];
		$display_author_select = mg_qt_Query::there_are_authors();
		if ($display_author_select) {
			if ($author > 0 && !mg_qt_Query::author_exists($author) )
			$author = 0;
		}
		
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mg_qt'); ?> 
					<input 
						id="<?php echo $this->get_field_id('title'); ?>" 
						name="<?php echo $this->get_field_name('title'); ?>" 
						type="text" 
						value="<?php echo $title; ?>" 
						class="widefat" 
					/>
				</label>
			</p>
			
			<?php if ($display_cat_select): ?>
				<p>
					<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:', 'mg_qt'); ?></label> 
					<?php
						wp_dropdown_categories(array(
							'taxonomy' => 'mg_qt_category',
							'name' => $this->get_field_name('category'),
							'selected' => $category,
							'show_option_all' => __('From all categories', 'mg_qt'),
							'hierarchical' => 1,
							'show_count' => 1,
							'orderby' => 'name',
							'class' => 'widefat'
						));
					?>
				</p>
			<?php endif; ?>
			
			<?php if ($display_author_select): ?>
				<p>
					<label for="<?php echo $this->get_field_id('author'); ?>"><?php _e('Author:', 'mg_qt'); ?></label> 
					<?php
						wp_dropdown_categories(array(
							'taxonomy' => 'mg_qt_author',
							'name' => $this->get_field_name('author'),
							'selected' => $author,
							'show_option_all' => __('From all authors', 'mg_qt'),
							'hierarchical' => 0,
							'show_count' => 1,
							'orderby' => 'name',
							'class' => 'widefat'
						));
					?>
				</p>
			<?php endif; ?>
		<?php
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['category'] = absint($new_instance['category']);
		$instance['author'] = absint($new_instance['author']);
		
		return $instance;
	}
	
	public function widget($args, $instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		$category = $instance['category'];
		$author = $instance['author'];
		
		switch (2 * !empty($category) + !empty($author)) {
			case 0:
				$quote = mg_qt_Query::rnd_quote();
				break;
			case 1:
				$quote = mg_qt_Query::rnd_quote_from_author_id($author);
				break;
			case 2:
				$quote = mg_qt_Query::rnd_quote_from_category_id($category);
				break;
			case 3:
				$quote = mg_qt_Query::rnd_quote_from_cat_and_author($category, $author);
				break;
		}
			
		$quote_markup = mg_qt_markup($quote);
		
		if ($quote_markup === '')
			return;
		
		$title = apply_filters('widget_title', 
			empty($instance['title']) ? '' : $instance['title'], 
			$instance, $this->id_base 
		);
		
		extract($args);

		echo $before_widget;
		if ($title)
			echo $before_title . $title . $after_title;
		echo $quote_markup;
		echo $after_widget;
	}

}

