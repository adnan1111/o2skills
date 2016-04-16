<?php

include plugin_dir_path(__FILE__).'siteorigin-widget.class.php';

function siteorigin_widget_register_self($name, $path){
	global $siteorigin_widgets_registered;
	$siteorigin_widgets_registered[$name] = $path;
}

function siteorigin_widget_render_preview(){
	$class = $_GET['class'];


	if(isset($_POST['widgets'])) {
		$instance = array_pop($_POST['widgets']);
	}
	else {
		foreach($_POST as $n => $v) {
			if(strpos($n, 'widget-') === 0) {
				$instance = array_pop($_POST[$n]);
				break;
			}
		}
	}

	if(!class_exists($class)) exit();
	$widget_obj = new $class();
	if( ! $widget_obj instanceof SiteOrigin_Widget ) exit();

	$instance = $widget_obj->update($instance, $instance);
	$instance['style_hash'] = 'preview';
	include plugin_dir_path(__FILE__).'/inc/preview.tpl.php';

	exit();
}
add_action('wp_ajax_siteorigin_widget_preview', 'siteorigin_widget_render_preview');

/**
 * @param $css
 */
function siteorigin_widget_add_inline_css($css){
	global $siteorigin_widgets_inline_styles;
	if(empty($siteorigin_widgets_inline_styles)) $siteorigin_widgets_inline_styles = '';

	$siteorigin_widgets_inline_styles .= $css;
}

/**
 * Print any inline styles that have been added with siteorigin_widget_add_inline_css
 */
function siteorigin_widget_print_styles(){
	global $siteorigin_widgets_inline_styles;
	if(!empty($siteorigin_widgets_inline_styles)) {
		?><style type="text/css"><?php echo($siteorigin_widgets_inline_styles) ?></style><?php
	}

	$siteorigin_widgets_inline_styles = '';
}
add_action('wp_head', 'siteorigin_widget_print_styles');
add_action('wp_footer', 'siteorigin_widget_print_styles');