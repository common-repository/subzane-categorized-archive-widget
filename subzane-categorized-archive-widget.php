<?php
/*
Plugin Name: SZ Categorized Archive Widget
Plugin URI: http://www.subzane.com/projects/categorized-archive-widget/
Description: A widget that displays an archive for the current selected category. On non-category pages a standard archive is displayed. 
Author: Andreas Norman
Version: 1.0
Author URI: http://www.subzane.com
*/


function subzane_categorized_archive_init() {
	if ( !function_exists('register_sidebar_widget') )
		return;
	
	function subzane_categorized_archive_widget($args) {
		global $wpdb;
		extract($args);

		$options = get_option('subzane_categorized_archive');
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$showempty = htmlspecialchars($options['showempty'], ENT_QUOTES);
		$showcount = htmlspecialchars($options['showcount'], ENT_QUOTES);

		$cat_id = get_query_var('cat');
		if ($cat_id) {
			$category_name = get_cat_name($cat_id);
			$url = get_bloginfo('wpurl').'/category/';
		} else {
			$category_name = '';
			$url = get_bloginfo('wpurl').'/';
		}
		$month_num = date('n');
		$year = date('Y');
		$months_counter = 0;
		

		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo '<div><ul>';
		for ($i=$month_num; $i > 0; $i--) { 
			$month_name = date("F", mktime(0, 0, 0, $i, 1, 2000));
			$posts = query_posts('monthnum='.$i.'&cat='.$cat_id);
			$post_count = count($posts);
			if ($showempty < 0) {
				if ($post_count > 0) {
					echo '<li><a href="'.$url.$year.'/'.$i.'/'.$category_name.'">'.$month_name.' '.$year;
					if ($showcount > 0) {
						echo ' ('.$post_count.')';
					}
					echo '</a></li>';
				}
			} else {
				if ($post_count > 0) {
					echo '<li><a href="'.$url.$year.'/'.$i.'/'.$category_name.'">'.$month_name.' '.$year;
					if ($showcount > 0) {
						echo ' ('.$post_count.')';
					}
					echo '</a></li>';
				}
			}
			$months_counter ++;
		}
		
		if ($months_counter < 12) {
			$year--;
			for ($i=12; $i > $months_counter; $i--) { 
				$posts = query_posts('monthnum='.$i.'&cat='.$cat_id);
				$month_name = date("F", mktime(0, 0, 0, $i, 1, 2000));
				$post_count = count($posts);
				if ($showempty < 0) {
					if ($post_count > 0) {
						echo '<li><a href="'.$url.$year.'/'.$i.'/'.$category_name.'">'.$month_name.' '.$year;
						if ($showcount > 0) {
							echo ' ('.$post_count.')';
						}
						echo '</a></li>';
					}
				} else {
					if ($post_count > 0) {
						echo '<li><a href="'.$url.$year.'/'.$i.'/'.$category_name.'">'.$month_name.' '.$year;
						if ($showcount > 0) {
							echo ' ('.$post_count.')';
						}
						echo '</a></li>';
					}
				}
			}
		}
		echo '</ul></div>';
		echo $after_widget;
	}

	function subzane_categorized_archive_control() {
		$options = get_option('subzane_categorized_archive');
		if ( isset($_POST['subzane_categorized_archive_submit']) ) {
			$options['title'] = strip_tags(stripslashes($_POST['subzane_categorized_archive_title']));
			$options['showempty'] = strip_tags(stripslashes($_POST['subzane_categorized_archive_showempty']));
			$options['showcount'] = strip_tags(stripslashes($_POST['subzane_categorized_archive_showcount']));
			
			update_option('subzane_categorized_archive', $options);
		}
		
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$showempty = htmlspecialchars($options['showempty'], ENT_QUOTES);
		$showcount = htmlspecialchars($options['showcount'], ENT_QUOTES);
		
		if ($showempty > 0) {
			$showempty_check = 'checked="checked"';
		} else {
			$showempty_check = '';
		}
		
		if ($showcount > 0) {
			$showcount_check = 'checked="checked"';
		} else {
			$showcount_check = '';
		}
		
		echo '
		<p>
			<label for="subzane_categorized_archive_title">
			' . __('Title:') . '
				<input id="subzane_categorized_archive_title" class="widefat" type="text" value="'.$title.'" name="subzane_categorized_archive_title"/>
			</label>
		</p>
		<p>
			<label for="subzane_categorized_archive_showempty">
				<input '.$showempty_check.' type="checkbox" name="subzane_categorized_archive_showempty" value="1" /> ' . __('Show empty') . '
			</label>
		</p>
		<p>
			<label for="subzane_categorized_archive_showcount">
				<input '.$showcount_check.' type="checkbox" name="subzane_categorized_archive_showcount" value="1" /> ' . __('Show post counts') . '
			</label>
		</p>
		
		<input type="hidden" name="subzane_categorized_archive_submit" value="1" />
		';
	}
	
	register_sidebar_widget(array('SZ Categorized Archive', 'widgets'), 'subzane_categorized_archive_widget');
	register_widget_control(array('SZ Categorized Archive', 'widgets'), 'subzane_categorized_archive_control', 350, 150);
	
}
add_action('plugins_loaded', 'subzane_categorized_archive_init');
?>