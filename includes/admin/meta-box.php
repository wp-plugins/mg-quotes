<?php

if (!defined('ABSPATH')) exit;

class mg_qt_Metaboxes {

	public function __construct() {
		add_action('add_meta_boxes_mg_qt_quote', array($this, 'register_meta_boxes'));
		add_action('admin_print_styles-post-new.php', array($this, 'injection'));
		add_action('admin_print_styles-post.php', array($this, 'injection'));
	}

	function register_meta_boxes() {
		add_meta_box(
			'mg_qt_quote_content',
			'Quote Content',
			array($this, 'render_content_meta_box'),
			null,
			'normal',
			'high'
		);
		
		add_meta_box(
			'mg_qt_quote_author',
			'Quote Author',
			array($this, 'render_author_meta_box'),
			null,
			'normal',
			'high'
		);
		
		add_meta_box(
			'mg_qt_quote_title',
			'Quote Title',
			array($this, 'render_title_meta_box'),
			null,
			'advanced',
			'default'
		);
	}

	function render_content_meta_box($post) {
		?>
			<textarea name="post_content" style="width: 100%; height: 10em;"><?php echo esc_html($post->post_content);?></textarea>
		<?php
	}

	function render_title_meta_box($post) {
		global $post_type_object;
		?>
		<div id="titlediv">
			<div id="titlewrap">
				<label 
					class="screen-reader-text" 
					id="title-prompt-text" 
					for="title"
				>
					<?php echo __('Enter an optional title here', 'mg_qt'); ?>
				</label>
				<input 
					id="title"
					type="text" 
					name="post_title" 
					size="30" 
					value="<?php echo esc_attr( htmlspecialchars( $post->post_title ) ); ?>"  
					autocomplete="off" 
				/>
			</div>

			<div class="inside">
			<?php
				$sample_permalink_html = $post_type_object->public ? get_sample_permalink_html($post->ID) : '';
				$shortlink = wp_get_shortlink($post->ID, 'post');
				$permalink = get_permalink( $post->ID );
				if ( !empty( $shortlink ) && $shortlink !== $permalink && $permalink !== home_url('?page_id=' . $post->ID) )
					$sample_permalink_html .= '<input id="shortlink" type="hidden" value="' . esc_attr($shortlink) . '" /><a href="#" class="button button-small" onclick="prompt(&#39;URL:&#39;, jQuery(\'#shortlink\').val()); return false;">' . __('Get Shortlink') . '</a>';

				if ( $post_type_object->public && ! ( 'pending' == get_post_status( $post ) && !current_user_can( $post_type_object->cap->publish_posts ) ) ) {
				$has_sample_permalink = $sample_permalink_html && 'auto-draft' != $post->post_status;
					?>
					<div id="edit-slug-box" class="hide-if-no-js">
					<?php
						if ( $has_sample_permalink )
							echo $sample_permalink_html;
					?>
					</div>
				<?php
				}
			?>
			</div>
			<?php wp_nonce_field( 'samplepermalink', 'samplepermalinknonce', false ); ?>
		</div><!-- /titlediv -->
		<?php
	}

	function render_author_meta_box($post, $box) {
		$author_name = mg_qt_Query::quote_author_name($post->ID);
		?>
			<input 
				id="mg_qt_author_input" 
				type="text" 
				name="tax_input[mg_qt_author]" 
				value="<?php echo esc_attr($author_name); ?>" 
				size="16" 
				autocomplete="off" 
				class=""
				style="position: relative; width: 50%"
			>
		<?php
	}

	function injection() {
		global $post_type;
		
		if ($post_type !== 'mg_qt_quote')
			return;
		
		wp_enqueue_script('mg_qt_mb', MG_QT_ASSETS . 'js/mb.js', array('jquery', 'suggest'), '', true);
	}

}

new mg_qt_MetaBoxes();
