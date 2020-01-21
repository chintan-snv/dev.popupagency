<?php 
/*
	Plugin Name: Simple Blog Stats
	Plugin URI: https://perishablepress.com/simple-blog-stats/
	Description: Provides shortcodes and template tags to display a variety of statistics about your site.
	Tags: stats, statistics, analytics, posts, pages,  drafts, comments, categories, recent posts, tags, users
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://monzillamedia.com/donate.html
	Contributors: specialk
	Requires at least: 4.1
	Tested up to: 5.3
	Stable tag: 20191105
	Version: 20191105
	Requires PHP: 5.6.20
	Text Domain: simple-blog-stats
	Domain Path: /languages
	License: GPL v2 or later
*/

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 
	2 of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	with this program. If not, visit: https://www.gnu.org/licenses/
	
	Copyright 2019 Monzilla Media. All rights reserved.
*/

if (!defined('ABSPATH')) die();



$sbs_wp_vers = '4.1';
$sbs_version = '20191105';
$sbs_plugin  = esc_html__('Simple Blog Stats', 'simple-blog-stats');
$sbs_options = get_option('sbs_options');
$sbs_path    = plugin_basename(__FILE__); // 'simple-blog-stats/simple-blog-stats.php';
$sbs_homeurl = 'https://perishablepress.com/simple-blog-stats/';

require_once('stats-functions.php');

function sbs_i18n_init() {
	
	load_plugin_textdomain('simple-blog-stats', false, dirname(plugin_basename(__FILE__)) .'/languages/');
	
}
add_action('plugins_loaded', 'sbs_i18n_init');



function sbs_require_wp_version() {
	
	global $sbs_path, $sbs_plugin, $sbs_wp_vers;
	
	if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
		
		$wp_version = get_bloginfo('version');
		
		if (version_compare($wp_version, $sbs_wp_vers, '<')) {
			
			if (is_plugin_active($sbs_path)) {
				
				deactivate_plugins($sbs_path);
				
				$msg =  '<strong>' . $sbs_plugin . '</strong> ' . esc_html__('requires WordPress ', 'simple-blog-stats') . $sbs_wp_vers . esc_html__(' or higher, and has been deactivated!', 'simple-blog-stats') . '<br />';
				
				$msg .= esc_html__('Please return to the', 'simple-blog-stats') . ' <a href="' . admin_url() . '">' . esc_html__('WordPress Admin area', 'simple-blog-stats') . '</a> ' . esc_html__('to upgrade WordPress and try again.', 'simple-blog-stats');
				
				wp_die($msg);
				
			}
			
		}
		
	}
	
}
add_action('admin_init', 'sbs_require_wp_version');



function sbs_on_deactivation() {
	
	if (!current_user_can('activate_plugins')) return;
	
	delete_transient('sbs_word_count');
	delete_transient('sbs_post_count');
	delete_transient('sbs_page_count');
	delete_transient('sbs_draft_count');
	delete_transient('sbs_user_count');
	delete_transient('sbs_comments_approved_count');
	delete_transient('sbs_comments_moderated_count');
	delete_transient('sbs_comments_total_count');
	
}
register_deactivation_hook(__FILE__, 'sbs_on_deactivation');



// number of posts
function sbs_posts($attr, $content = null) {
	
	global $sbs_options;
	
	$cache = isset($sbs_options['sbs_enable_cache']) ? $sbs_options['sbs_enable_cache'] : false;
	
	extract(shortcode_atts(array(
		'type'   => 'post',
		'status' => 'publish',
		'cat'    => null,
		'tag'    => null,
	), $attr));
	
	$limit = apply_filters('sbs_get_posts_limit', -1);
	
	$args = array(
		'posts_per_page' => $limit,
		'post_type'      => $type,
		'post_status'    => $status,
		'category_name'  => $cat,
		'tag'            => $tag,
		'fields'         => 'ids',
	);
	
	$posts = get_posts($args);
	
	if ($cache) {
		
		if (false === ($count = get_transient('sbs_post_count'))) {
			
			$count = count($posts);
			
			set_transient('sbs_post_count', $count, 12 * HOUR_IN_SECONDS);
			
		}
		
	} else {
		
		$count = count($posts);
		
	}
	
	return $sbs_options['count_posts_before'] . number_format($count) . $sbs_options['count_posts_after'];
	
}
add_shortcode('sbs_posts','sbs_posts');



// number of posts (alt)
function sbs_posts_alt($attr, $content = null) {
	
	extract(shortcode_atts(array(
		'type'   => 'post',
		'status' => 'publish',
	), $attr));
	
	$property = "$status";
	
	$total = wp_count_posts($type)->{$property};
	
	return number_format($total);
	
}
add_shortcode('sbs_posts_alt', 'sbs_posts_alt');



// number of pages
function sbs_pages() {
	
	global $sbs_options;
	
	$cache = isset($sbs_options['sbs_enable_cache']) ? $sbs_options['sbs_enable_cache'] : false;
	
	$count_pages = wp_count_posts('page');
	
	if ($cache) {
		
		if (false === ($count = get_transient('sbs_page_count'))) {
			
			$count = intval($count_pages->publish);
			
			set_transient('sbs_page_count', $count, 12 * HOUR_IN_SECONDS);
			
		}
		
	} else {
		
		$count = intval($count_pages->publish);
		
	}
	
	return $sbs_options['count_pages_before'] . number_format($count) . $sbs_options['count_pages_after'];
	
}
add_shortcode('sbs_pages','sbs_pages');



// number of drafts
function sbs_drafts() {
	
	global $sbs_options;
	
	$cache = isset($sbs_options['sbs_enable_cache']) ? $sbs_options['sbs_enable_cache'] : false;
	
	$count_drafts = wp_count_posts();
	
	if ($cache) {
		
		if (false === ($count = get_transient('sbs_draft_count'))) {
			
			$count = intval($count_drafts->draft);
			
			set_transient('sbs_draft_count', $count, 12 * HOUR_IN_SECONDS);
			
		}
		
	} else {
		
		$count = intval($count_drafts->draft);
		
	}
	
	return $sbs_options['count_drafts_before'] . number_format($count) . $sbs_options['count_drafts_after'];
	
}
add_shortcode('sbs_drafts','sbs_drafts');



// number of comments (total)
function sbs_comments() {
	
	global $sbs_options;
	
	$cache = isset($sbs_options['sbs_enable_cache']) ? $sbs_options['sbs_enable_cache'] : false;
	
	$count_comments = wp_count_comments();
	
	if ($cache) {
		
		if (false === ($count = get_transient('sbs_comments_total_count'))) {
			
			$count = intval($count_comments->total_comments);
			
			set_transient('sbs_comments_total_count', $count, 12 * HOUR_IN_SECONDS);
			
		}
		
	} else {
		
		$count = intval($count_comments->total_comments);
		
	}
	
	return $sbs_options['count_comments_before'] . number_format($count) . $sbs_options['count_comments_after'];
	
}
add_shortcode('sbs_comments','sbs_comments');



// number of comments (moderated)
function sbs_moderated() {
	
	global $sbs_options;
	
	$cache = isset($sbs_options['sbs_enable_cache']) ? $sbs_options['sbs_enable_cache'] : false;
	
	$count_moderated = wp_count_comments();
	
	if ($cache) {
		
		if (false === ($count = get_transient('sbs_comments_moderated_count'))) {
			
			$count = intval($count_moderated->moderated);
			
			set_transient('sbs_comments_moderated_count', $count, 12 * HOUR_IN_SECONDS);
			
		}
		
	} else {
		
		$count = intval($count_moderated->moderated);
		
	}
	
	return $sbs_options['count_moderated_before'] . number_format($count) . $sbs_options['count_moderated_after'];
	
}
add_shortcode('sbs_moderated','sbs_moderated');



// number of comments (approved)
function sbs_approved() {
	
	global $sbs_options;
	
	$cache = isset($sbs_options['sbs_enable_cache']) ? $sbs_options['sbs_enable_cache'] : false;
	
	$count_approved = wp_count_comments();
	
	if ($cache) {
		
		if (false === ($count = get_transient('sbs_comments_approved_count'))) {
			
			$count = intval($count_approved->approved);
			
			set_transient('sbs_comments_approved_count', $count, 12 * HOUR_IN_SECONDS);
			
		}
		
	} else {
		
		$count = intval($count_approved->approved);
		
	}
	
	return $sbs_options['count_approved_before'] . number_format($count) . $sbs_options['count_approved_after'];
	
}
add_shortcode('sbs_approved','sbs_approved');



// number of users
function sbs_users() {
	
	global $sbs_options;
	
	$cache = isset($sbs_options['sbs_enable_cache']) ? $sbs_options['sbs_enable_cache'] : false;
	
	$count_users = count_users();
	
	if ($cache) {
		
		if (false === ($count = get_transient('sbs_user_count'))) {
			
			$count = intval($count_users['total_users']);
			
			set_transient('sbs_user_count', $count, 12 * HOUR_IN_SECONDS);
			
		}
		
	} else {
		
		$count = intval($count_users['total_users']);
		
	}
	
	return $sbs_options['count_users_before'] . number_format($count) . $sbs_options['count_users_after'];
	
}
add_shortcode('sbs_users','sbs_users');



// number of users per role
function sbs_roles($attr, $content = null) {
	
	global $sbs_options;
	
	extract(shortcode_atts(array(
		'role' => 'all',
		'txt'  => '',
	), $attr));
	
	$count_users = count_users();
	
	$roles = isset($count_users['avail_roles']) ? $count_users['avail_roles'] : false;
	
	unset($roles['none']);
	
	if (!isset($role) || empty($role) || $role === 'all') {
		
		$return = '<ul>';
		
		foreach ($roles as $key => $value) {
			
			$label = ngettext(ucfirst($key), ucfirst($key) . esc_html__('s', 'simple-blog-stats'), intval($value));
			
			if ($txt) $label = $txt;
			
			if ($txt === 'null') $label = '';
			
    		$return .= '<li>'. $value .' '. $label .'</li>';
    		
		}
		
		$return .= '</ul>';
		
	} else {
		
		$return = '';
		
		foreach ($roles as $key => $value) {
			
			if (strtolower($key) === strtolower($role)) {
				
				$label = ngettext(ucfirst($role), ucfirst($role) . esc_html__('s', 'simple-blog-stats'), intval($value));
				
				if ($txt) $label = $txt;
				
				if ($txt === 'null') $label = '';
				
				$return .= $value .' '. $label;
				
    		}
    		
		}
		
	}
	
	return $sbs_options['count_roles_before'] . $return . $sbs_options['count_roles_after'];
	
}
add_shortcode('sbs_roles','sbs_roles');



// number of categories
function sbs_cats() {
	global $sbs_options;
	$cats = wp_list_categories('title_li=&style=none&echo=0');
	$cats_parts = explode('<br />', $cats);
	$cats_count = count($cats_parts) - 1;
	return $sbs_options['count_cats_before'] . intval($cats_count) . $sbs_options['count_cats_after'];
}
add_shortcode('sbs_cats','sbs_cats');



// number of tags
function sbs_tags() {
	global $sbs_options;
	return $sbs_options['count_tags_before'] . intval(wp_count_terms('post_tag')) . $sbs_options['count_tags_after'];
}
add_shortcode('sbs_tags','sbs_tags');



// number of taxonomy terms
function sbs_tax($attr, $content = null) {
	
	global $sbs_options;
	
	$before = isset($sbs_options['count_tax_before']) ? $sbs_options['count_tax_before'] : '';
	$after  = isset($sbs_options['count_tax_after'])  ? $sbs_options['count_tax_after']  : '';
	
	extract(shortcode_atts(array('tax' => null), $attr));
	
	$tax = intval(wp_count_terms($tax));
	
	return $before . $tax . $after;
	
}
add_shortcode('sbs_tax', 'sbs_tax');



// site last updated
function sbs_updated($d = '') {
	global $sbs_options;
	$count_posts = wp_count_posts();
	$published_posts = $count_posts->publish; 
	$recent = new WP_Query("showposts=1&orderby=date&post_status=publish");
	if ($recent->have_posts()) {
		while ($recent->have_posts()) {
			$recent->the_post();
			$last_update = get_the_modified_date($d) . ' <span class="sbs-site-updated-time">@ ' . get_the_time($d) . '</span>';
			$last_update = apply_filters('sbs_last_update', $last_update, get_the_modified_date($d), get_the_time($d));
		}
		return $sbs_options['site_updated_before'] . sanitize_text_field($last_update) . $sbs_options['site_updated_after'];
	} else {
		return $sbs_options['site_updated_before'] . sanitize_text_field('awhile ago') . $sbs_options['site_updated_after'];
	}
}
add_shortcode('sbs_updated','sbs_updated');



// latest posts
function sbs_latest_posts($d = '') {
	global $sbs_options;
	$posts_number = $sbs_options['number_of_posts'];
	$post_length  = $sbs_options['post_length'];
	$latest = new WP_Query("showposts=$posts_number&orderby=date&post_status=publish");
	if ($latest->have_posts()) {
		$latest_posts = '<ul id="sbs-posts">';
		while ($latest->have_posts()) {
			$latest->the_post();
			$post_content = get_the_content();
			$post_excerpt = preg_replace('/\s+?(\S+)?$/', '', substr($post_content, 0, $post_length));
			$post_display = strip_tags($post_excerpt, '<p>');
			$latest_posts .= '<li class="sbs-post"><a href="' . get_permalink() . '">' . the_title_attribute(array('echo'=>0)) . '</a> ';
			$latest_posts .= '<span>' . $post_display . ' <small>[...]</small></span></li>';
		}
		$latest_posts .= '</ul>';
		return $sbs_options['latest_posts_before'] . $latest_posts . $sbs_options['latest_posts_after'];
	} else {
		return $sbs_options['latest_posts_before'] . 'nothing new' . $sbs_options['latest_posts_after'];
	}
}
add_shortcode('sbs_latest_posts','sbs_latest_posts');



// latest comments
function sbs_latest_comments() {
	global $sbs_options;
	$comments_number = $sbs_options['number_of_comments'];
	$comment_length  = $sbs_options['comment_length'];

	$recent_comments = get_comments(array('number'=>$comments_number, 'status'=>'approve'));
	$comments = '<ul id="sbs-comments">';
	foreach ($recent_comments as $recent_comment) {
		$comment_id        = $recent_comment->comment_ID;
		$comment_date      = $recent_comment->comment_date;
		$comment_author    = $recent_comment->comment_author;

		$comment_content   = $recent_comment->comment_content;
		$comment_excerpt   = preg_replace('/\s+?(\S+)?$/', '', substr($comment_content, 0, $comment_length));

		$line_breaks       = array("\r\n", "\n", "\r");
		$comment_display   = str_replace($line_breaks, " ", $comment_excerpt);
		$comment_display   = esc_attr($comment_display);

		$comment_post_id   = $recent_comment->comment_post_ID;
		$comment_permalink = get_permalink($comment_post_id);

		$comments .= '<li class="sbs-comment">';
		$comments .= '<a href="' . $comment_permalink . '#comment-' . $comment_id . '" title="Posted: ' . $comment_date . '">' . $comment_author . '</a>: ';
		$comments .= '<span>' . $comment_display . ' <small>[...]</small></span></li>';
	}
	$comments .= '</ul>';
	return $sbs_options['latest_comments_before'] . $comments . $sbs_options['latest_comments_after'];	
}
add_shortcode('sbs_latest_comments','sbs_latest_comments');



// number of words per post
function sbs_word_count($atts) {
	
	global $sbs_options;
	
	$args = shortcode_atts(array('id' => false), $atts);
    
	$id = (isset($args['id']) && !empty($args['id']) && is_numeric($args['id'])) ? $args['id'] : get_the_ID();
	
	$post = get_post($id); 
	
	$content = isset($post->post_content) ? strip_tags($post->post_content) : null;
	
	$count = 0;
	
	if ($content) {
		
		$content = preg_replace('/(\[sbs_word_count(.*)\])/', '', $content);
		
		$count = str_word_count($content);
		
	}
	
	return $sbs_options['count_words_before'] . number_format($count) .  $sbs_options['count_words_after'];
	
}
add_shortcode('sbs_word_count','sbs_word_count');



// number of words all posts
function sbs_word_count_all($wrap) {
	
	global $sbs_options;
	
	$cache = isset($sbs_options['sbs_enable_cache']) ? $sbs_options['sbs_enable_cache'] : false;
	
	$limit = apply_filters('sbs_get_posts_limit', -1);
	
	$args = array(
		'post_type'      => 'any', 
		'post_status'    => 'publish', 
		'posts_per_page' => $limit,
		'fields'         => 'ids',
	);
	
	$posts = get_posts($args);
	
	if (!$posts) return;
	
	if ($cache) {
		
		if (false === ($count = get_transient('sbs_word_count'))) {
			
			$count = 0;
			
			foreach ($posts as $id) {
				
				$post = get_post($id);
				
				$content = isset($post->post_content) ? $post->post_content : null;
				
				$content = preg_replace('/(\[sbs_word_count(.*)\])/', '', $content);
				
				$wordcount = str_word_count($content, 0);
				
				$totalcount = $wordcount + $count;
				
				$count = $totalcount;
				
			}
			
			set_transient('sbs_word_count', $count, 12 * HOUR_IN_SECONDS);
			
		}
		
	} else {
		
		$count = 0;
		
		foreach ($posts as $id) {
			
			$post = get_post($id);
			
			$content = isset($post->post_content) ? $post->post_content : null;
			
			$content = preg_replace('/(\[sbs_word_count(.*)\])/', '', $content);
			
			$wordcount = str_word_count($content, 0);
			
			$totalcount = $wordcount + $count;
			
			$count = $totalcount;
			
		}
		
	}
	
	wp_reset_postdata();
	
	$output = number_format($count);
	
	$output = ($wrap) ? $sbs_options['count_words_all_before'] . $output . $sbs_options['count_words_all_after'] : $output;
	
	return $output;
	
}
add_shortcode('sbs_word_count_all','sbs_word_count_all');



// number of specific post type
function sbs_cpt_count($atts) {
	
	global $sbs_options;
	
	$before = isset($sbs_options['sbs_cpt_before']) ? $sbs_options['sbs_cpt_before'] : '';
	$after  = isset($sbs_options['sbs_cpt_after'])  ? $sbs_options['sbs_cpt_after']  : '';
	
	extract(shortcode_atts(array(
		'cpt' => 'post',
		'txt' => '',
	), $atts));
	
	$post = get_post_type_object($cpt);
	
	$name = isset($post->labels->name) ? $post->labels->name : null;
	
	if ($txt) $name = $txt;
	
	if ($txt === 'null') $name = '';
	
	$count = wp_count_posts($cpt);
	
	$publish = $count->publish .' '. $name;
	
	return $before . $publish . $after;
	
}
add_shortcode('sbs_cpt_count', 'sbs_cpt_count');



// number of cpt posts
function sbs_cpts_count($atts) {
	
	global $sbs_options;
	
	$before = isset($sbs_options['sbs_cpts_before']) ? $sbs_options['sbs_cpts_before'] : '';
	$after  = isset($sbs_options['sbs_cpts_after'])  ? $sbs_options['sbs_cpts_after']  : '';
	
	$post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects', 'and');
	
	$html = '<ul>';
	
	foreach ($post_types as $post_type) {
		
		$num_posts = wp_count_posts($post_type->name);
		
		$num = number_format_i18n($num_posts->publish);
		
		$text = _n($post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));
		
		$html .= '<li>'. esc_html($num) .' '. esc_html($text) .'</li>';
		
	}
	
	$html .= '</ul>';
	
	return $before . $html . $after;
	
}
add_shortcode('sbs_cpts_count', 'sbs_cpts_count');



// number of media files
function sbs_media_count($atts) {
	
	global $sbs_options;
	
	$before = isset($sbs_options['sbs_media_before']) ? $sbs_options['sbs_media_before'] : '';
	$after  = isset($sbs_options['sbs_media_after'])  ? $sbs_options['sbs_media_after']  : '';
	
	extract(shortcode_atts(array(
		'type' => 'image',
		'txt' => '',
	), $atts));
	
	$name = $txt ? ' '. $txt : '';
	
	if ($type === 'video') {
		
		$mime = array(
			'asf|asx'      => 'video/x-ms-asf',
			'wmv'          => 'video/x-ms-wmv',
			'wmx'          => 'video/x-ms-wmx',
			'wm'           => 'video/x-ms-wm',
			'avi'          => 'video/avi',
			'divx'         => 'video/divx',
			'flv'          => 'video/x-flv',
			'mov|qt'       => 'video/quicktime',
			'mpeg|mpg|mpe' => 'video/mpeg',
			'mp4|m4v'      => 'video/mp4',
			'ogv'          => 'video/ogg',
			'webm'         => 'video/webm',
			'mkv'          => 'video/x-matroska',
		);
		
	} else {
		
		$mime = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tif|tiff'     => 'image/tiff',
			'ico'          => 'image/x-icon',
		);
		
	}
	
	$mime = apply_filters('sbs_media_count_mime', $mime, $type);
	
	$args = array(
		
		'post_type'      => 'attachment',
		'post_mime_type' => $mime,
		'post_status'    => 'inherit',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		
	);
	
	$query = new WP_Query($args);
	
	$count = isset($query->post_count) ? $query->post_count : 0;
	
	return $before . $count . $name . $after;
	
}
add_shortcode('sbs_media_count', 'sbs_media_count');



// display blog stats
function sbs_blog_stats() {
	global $sbs_options;

	$count_posts = wp_count_posts();
	$number_posts = $count_posts->publish;

	$count_pages = wp_count_posts('page');
	$number_pages = $count_pages->publish;

	$count_drafts = wp_count_posts();
	$number_drafts = $count_drafts->draft;

	$count_comments = wp_count_comments();
	$number_comments = $count_comments->total_comments;

	$count_moderated = wp_count_comments();
	$number_moderated = $count_moderated->moderated;

	$count_approved = wp_count_comments();
	$number_approved = $count_approved->approved;

	$count_users = count_users();
	$number_users = $count_users['total_users'];

	$cats = wp_list_categories('title_li=&style=none&echo=0');
	$cats_parts = explode('<br />', $cats);
	$cats_count = count($cats_parts) - 1;
	$number_cats = $cats_count;

	$number_tags = wp_count_terms('post_tag');
	$number_words = sbs_word_count_all(false);
	
	$sbs_stats  = '<ul id="sbs-stats">';
	$sbs_stats .= '<li><span>' . intval($number_posts) . '</span> ' . esc_html__('posts', 'simple-blog-stats') . '</li>';
	$sbs_stats .= '<li><span>' . intval($number_pages) . '</span> ' . esc_html__('pages', 'simple-blog-stats') . '</li>';
	$sbs_stats .= '<li><span>' . intval($number_drafts) . '</span> ' . esc_html__('drafts', 'simple-blog-stats') . '</li>';
	$sbs_stats .= '<li><span>' . intval($number_words) . '</span> ' . esc_html__('words', 'simple-blog-stats') . '</li>';
	$sbs_stats .= '<li><span>' . intval($number_comments) . '</span> ' . esc_html__('total comments', 'simple-blog-stats') . '</li>';
	$sbs_stats .= '<li><span>' . intval($number_moderated) . '</span> ' . esc_html__('comments in queue', 'simple-blog-stats') . '</li>';
	$sbs_stats .= '<li><span>' . intval($number_approved) . '</span> ' . esc_html__('comments approved', 'simple-blog-stats') . '</li>';
	$sbs_stats .= '<li><span>' . intval($number_users) . '</span> ' . esc_html__('registered users', 'simple-blog-stats') . '</li>';
	$sbs_stats .= '<li><span>' . intval($number_cats) . '</span> ' . esc_html__('categories', 'simple-blog-stats') . '</li>';
	$sbs_stats .= '<li><span>' . intval($number_tags) . '</span> ' . esc_html__('tags', 'simple-blog-stats') . '</li>';
	$sbs_stats .= '</ul>';

	return $sbs_options['blog_stats_before'] . $sbs_stats . $sbs_options['blog_stats_after'];
}
add_shortcode('sbs_blog_stats','sbs_blog_stats');



// dashboard widget
function sbs_dashboard_widget_stats($post, $args) {
	
	echo '<style>.sbs-blog-stats ul { margin: 0 0 0 40px; list-style: disc outside; }</style>';
	echo '<p>'. esc_html__('Statistics for ', 'simple-blog-stats') . get_bloginfo('name') .':</p>';
	echo do_shortcode('[sbs_blog_stats]');
	
}

function sbs_dashboard_widget() {
	global $sbs_options;
	
	// wp_add_dashboard_widget($widget_id, $widget_name, $callback, $control_callback, $callback_args );
	wp_add_dashboard_widget('sbs_dashboard_widget', esc_html__('Site Statistics', 'simple-blog-stats'), 'sbs_dashboard_widget_stats');
	
}
add_action('wp_dashboard_setup', 'sbs_dashboard_widget');



function sbs_plugin_action_links($links, $file) {
	global $sbs_path, $sbs_path;
	if ($file == $sbs_path && current_user_can('manage_options')) {
		$sbs_links = '<a href="' . get_admin_url() . 'options-general.php?page=' . $sbs_path . '">' . esc_html__('Settings', 'simple-blog-stats') .'</a>';
		array_unshift($links, $sbs_links);
	}
	return $links;
}
add_filter ('plugin_action_links', 'sbs_plugin_action_links', 10, 2);



function add_sbs_links($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		
		$home_href  = 'https://perishablepress.com/simple-blog-stats/';
		$home_title = esc_attr__('Plugin Homepage', 'simple-blog-stats');
		$home_text  = esc_html__('Homepage', 'simple-blog-stats');
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
		
		$rate_url   = 'https://wordpress.org/support/plugin/simple-blog-stats/reviews/?rate=5#new-post';
		$rate_title = esc_html__('THANK YOU for your support!', 'simple-blog-stats');
		$rate_text  = esc_html__('Rate this plugin', 'simple-blog-stats') .'&nbsp;&raquo;';
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_url .'" title="'. $rate_title .'">'. $rate_text .'</a>';
	}
	return $links;
}
add_filter('plugin_row_meta', 'add_sbs_links', 10, 2);



function sbs_delete_plugin_options() {
	delete_option('sbs_options');
}
if ($sbs_options['default_options'] == 1) {
	register_uninstall_hook (__FILE__, 'sbs_delete_plugin_options');
}



function sbs_add_defaults() {
	$tmp = get_option('sbs_options');
	if(($tmp['default_options'] == '1') || (!is_array($tmp))) {
		$arr = array(
			'default_options'    => 0,
			'count_posts_before' => '<span class="sbs-count-posts">',
			'count_posts_after'  => '</span>',
			'count_pages_before' => '<span class="sbs-count-pages">',
			'count_pages_after'  => '</span>',
			'count_drafts_before' => '<span class="sbs-count-drafts">',
			'count_drafts_after'  => '</span>',
			'count_comments_before' => '<span class="sbs-count-comments">',
			'count_comments_after'  => '</span>',
			'count_moderated_before' => '<span class="sbs-count-moderated">',
			'count_moderated_after'  => '</span>',
			'count_approved_before' => '<span class="sbs-count-approved">',
			'count_approved_after'  => '</span>',
			'count_users_before' => '<span class="sbs-count-users">',
			'count_users_after'  => '</span>',
			'count_cats_before' => '<span class="sbs-count-cats">',
			'count_cats_after'  => '</span>',
			'count_tags_before' => '<span class="sbs-count-tags">',
			'count_tags_after'  => '</span>',
			'count_tax_before' => '<span class="sbs-count-tax">',
			'count_tax_after'  => '</span>',
			'site_updated_before' => '<span class="sbs-site-updated">',
			'site_updated_after'  => '</span>',
			
			'latest_posts_before' => '<div class="sbs-latest-posts">',
			'latest_posts_after'  => '</div>',
			'latest_comments_before' => '<div class="sbs-latest-comments">',
			'latest_comments_after'  => '</div>',
			'blog_stats_before' => '<div class="sbs-blog-stats">',
			'blog_stats_after'  => '</div>',
			
			'number_of_comments' => '3',
			'number_of_posts' => '3',
			'comment_length' => '30',
			'post_length' => '30',
			'count_words_before' => '<span class="sbs-number-words">',
			'count_words_after' => '</span>',
			'count_words_all_before' => '<span class="sbs-number-words-all">',
			'count_words_all_after' => '</span>',
			
			'count_roles_before' => '<div class="sbs-count-roles">',
			'count_roles_after' => '</div>',
			'sbs_cpts_before' => '<div class="sbs-count-cpts">',
			'sbs_cpts_after' => '</div>',
			
			'sbs_cpt_before' => '<span class="sbs-count-cpt">',
			'sbs_cpt_after' => '</span>',
			
			'logged_users_before' => '<span class="sbs-logged-users">',
			'logged_users_after' => '</span>',
			
			'sbs_media_before' => '<span class="sbs-count-media">',
			'sbs_media_after' => '</span>',
			
			'sbs_enable_cache' => false,
		);
		update_option('sbs_options', $arr);
	}
}
register_activation_hook (__FILE__, 'sbs_add_defaults');



function sbs_init() {
	register_setting('sbs_plugin_options', 'sbs_options', 'sbs_validate_options');
}
add_action ('admin_init', 'sbs_init');



function sbs_validate_options($input) {
	
	if (!isset($input['default_options'])) $input['default_options'] = null;
	$input['default_options'] = ($input['default_options'] == 1 ? 1 : 0);

	$input['count_posts_before'] = wp_kses_post($input['count_posts_before']);
	$input['count_posts_after'] = wp_kses_post($input['count_posts_after']);

	$input['count_pages_before'] = wp_kses_post($input['count_pages_before']);
	$input['count_pages_after'] = wp_kses_post($input['count_pages_after']);

	$input['count_drafts_before'] = wp_kses_post($input['count_drafts_before']);
	$input['count_drafts_after'] = wp_kses_post($input['count_drafts_after']);

	$input['count_comments_before'] = wp_kses_post($input['count_comments_before']);
	$input['count_comments_after'] = wp_kses_post($input['count_comments_after']);

	$input['count_moderated_before'] = wp_kses_post($input['count_moderated_before']);
	$input['count_moderated_after'] = wp_kses_post($input['count_moderated_after']);

	$input['count_approved_before'] = wp_kses_post($input['count_approved_before']);
	$input['count_approved_after'] = wp_kses_post($input['count_approved_after']);

	$input['count_users_before'] = wp_kses_post($input['count_users_before']);
	$input['count_users_after'] = wp_kses_post($input['count_users_after']);

	$input['count_cats_before'] = wp_kses_post($input['count_cats_before']);
	$input['count_cats_after'] = wp_kses_post($input['count_cats_after']);

	$input['count_tags_before'] = wp_kses_post($input['count_tags_before']);
	$input['count_tags_after'] = wp_kses_post($input['count_tags_after']);
	
	$input['count_tax_before'] = wp_kses_post($input['count_tax_before']);
	$input['count_tax_after'] = wp_kses_post($input['count_tax_after']);
	
	$input['site_updated_before'] = wp_kses_post($input['site_updated_before']);
	$input['site_updated_after'] = wp_kses_post($input['site_updated_after']);

	$input['latest_posts_before'] = wp_kses_post($input['latest_posts_before']);
	$input['latest_posts_after'] = wp_kses_post($input['latest_posts_after']);

	$input['latest_comments_before'] = wp_kses_post($input['latest_comments_before']);
	$input['latest_comments_after'] = wp_kses_post($input['latest_comments_after']);

	$input['blog_stats_before'] = wp_kses_post($input['blog_stats_before']);
	$input['blog_stats_after'] = wp_kses_post($input['blog_stats_after']);

	$input['count_words_before'] = wp_kses_post($input['count_words_before']);
	$input['count_words_after'] = wp_kses_post($input['count_words_after']);

	$input['count_words_all_before'] = wp_kses_post($input['count_words_all_before']);
	$input['count_words_all_after'] = wp_kses_post($input['count_words_all_after']);
	
	$input['count_roles_before'] = wp_kses_post($input['count_roles_before']);
	$input['count_roles_after'] = wp_kses_post($input['count_roles_after']);
	
	$input['sbs_cpts_before'] = wp_kses_post($input['sbs_cpts_before']);
	$input['sbs_cpts_after'] = wp_kses_post($input['sbs_cpts_after']);
	
	$input['sbs_cpt_before'] = wp_kses_post($input['sbs_cpt_before']);
	$input['sbs_cpt_after'] = wp_kses_post($input['sbs_cpt_after']);
	
	$input['logged_users_before'] = wp_kses_post($input['logged_users_before']);
	$input['logged_users_after'] = wp_kses_post($input['logged_users_after']);
	
	$input['sbs_media_before'] = wp_kses_post($input['sbs_media_before']);
	$input['sbs_media_after'] = wp_kses_post($input['sbs_media_after']);
	
	$input['number_of_comments'] = intval($input['number_of_comments']);
	$input['number_of_posts'] = intval($input['number_of_posts']);
	$input['comment_length'] = intval($input['comment_length']);
	$input['post_length'] = intval($input['post_length']);
	
	if (!isset($input['sbs_enable_cache'])) $input['sbs_enable_cache'] = null;
	$input['sbs_enable_cache'] = ($input['sbs_enable_cache'] == 1 ? 1 : 0);
	
	if (!$input['sbs_enable_cache']) sbs_on_deactivation();
	
	return $input;
}



function sbs_add_options_page() {
	add_options_page('Simple Blog Stats', 'Simple Blog Stats', 'manage_options', __FILE__, 'sbs_render_form');
}
add_action ('admin_menu', 'sbs_add_options_page');



function sbs_render_form() {
	global $sbs_plugin, $sbs_options, $sbs_path, $sbs_homeurl, $sbs_version; ?>

	<style type="text/css">
		#mm-plugin-options .mm-panel-overview {
			padding: 0 15px 15px 150px;
			background-image: url(<?php echo plugins_url('/simple-blog-stats/sbs-icon.png'); ?>);
			background-repeat: no-repeat; background-position: 15px 0; background-size: 120px 120px;
			}
		#mm-plugin-options .mm-panel-toggle { margin: 5px 0; }
		#mm-plugin-options .mm-credit-info { margin: -10px 0 10px 5px; font-size: 12px; }
		#mm-plugin-options .button-primary { margin: 0 0 15px 15px; }
		
		#mm-plugin-options #setting-error-settings_updated { margin: 5px 0 15px 0; }
		#mm-plugin-options #setting-error-settings_updated p { margin: 7px 0 6px 0; }
		
		#mm-plugin-options .mm-table-wrap { margin: 15px; }
		#mm-plugin-options .mm-table-wrap td { padding: 5px 10px; vertical-align: middle; }
		#mm-plugin-options .mm-table-wrap .mm-table { padding: 10px 0; }
		#mm-plugin-options .mm-table-wrap .widefat th { padding: 10px 15px; vertical-align: middle; }
		#mm-plugin-options .mm-table-wrap .widefat td { padding: 10px; vertical-align: middle; border-bottom: 1px solid #e5e5e5; }
		
		#mm-plugin-options h1 small { line-height: 12px; font-size: 12px; color: #bbb; }
		#mm-plugin-options h2 { margin: 0; padding: 12px 0 12px 15px; font-size: 16px; cursor: pointer; }
		#mm-plugin-options h3 { margin: 20px 15px; font-size: 14px; }
		#mm-plugin-options p { margin-left: 15px; }
		#mm-plugin-options ul { margin: 15px 15px 25px 40px; line-height: 16px; }
		#mm-plugin-options li { margin: 8px 0; list-style-type: disc; }
		
		#mm-plugin-options input[type=checkbox] { margin-top: -3px; }
		#mm-plugin-options .mm-radio-inputs { margin: 5px 0; }
		#mm-plugin-options code { margin: 0 1px; padding: 3px; background-color: #fafae0; color: #333; font-size: 14px; }
		
		#mm-plugin-options .mm-item-caption { margin: 3px 0 0 3px; line-height: 17px; font-size: 12px; color: #777; }
		#mm-plugin-options .mm-item-caption code { margin: 0; padding: 3px; font-size: 12px; background: #f2f2f2; background-color: rgba(0,0,0,0.05); }
		#mm-plugin-options .mm-item-caption-nomargin { margin: 0; }
		#mm-plugin-options textarea + .mm-item-caption { margin: 0 0 0 3px; }
		#mm-plugin-options input[type=checkbox] + .mm-item-caption { margin: 3px 0 0 0; }
		
		#mm-plugin-options .mm-number-option { margin-top: 3px; }
		#mm-plugin-options .mm-number-option input[type=text] { min-height: 0; padding: 3px 5px; line-height: 1; font-size: 12px; }
		#mm-plugin-options #mm-panel-primary td ul { margin-left: 20px; }
		#mm-plugin-options .mm-table-wrap .widefat .sbs-checkbox th, 
		#mm-plugin-options .mm-table-wrap .widefat .sbs-checkbox td { padding-top: 20px; padding-bottom: 20px; }
	</style>

	<div id="mm-plugin-options" class="wrap">
		<h1><?php esc_html_e('Simple Blog Stats', 'simple-blog-stats'); ?> <small><?php echo 'v' . $sbs_version; ?></small></h1>
		<div id="mm-panel-toggle"><a href="<?php get_admin_url() . 'options-general.php?page=' . $sbs_path; ?>"><?php esc_html_e('Toggle all panels', 'simple-blog-stats'); ?></a></div>

		<form method="post" action="options.php">
			<?php $sbs_options = get_option('sbs_options'); settings_fields('sbs_plugin_options'); ?>

			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					
					<div id="mm-panel-overview" class="postbox">
						<h2><?php esc_html_e('Overview', 'simple-blog-stats'); ?></h2>
						<div class="toggle<?php if (isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<div class="mm-panel-overview">
								<p>
									<strong><?php echo $sbs_plugin; ?></strong> <?php esc_html_e('provides a bunch of shortcodes and template tags to display a variety of statistics about your site.', 'simple-blog-stats'); ?> 
									<?php esc_html_e('Use the shortcodes to display various stats on any WP Post or Page. Use the template tags to display stats anywhere in your theme template.', 'simple-blog-stats'); ?>
								</p>
								<ul>
									<li><a id="mm-panel-primary-link" href="#mm-panel-primary"><?php esc_html_e('Shortcodes', 'simple-blog-stats'); ?></a></li>
									<li><a id="mm-panel-secondary-link" href="#mm-panel-secondary"><?php esc_html_e('Template Tags', 'simple-blog-stats'); ?></a></li>
									<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/simple-blog-stats/"><?php esc_html_e('Plugin Homepage', 'simple-blog-stats'); ?>&nbsp;&raquo;</a></li>
								</ul>
								<p>
									<?php esc_html_e('If you like this plugin, please', 'simple-blog-stats'); ?> 
									<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/simple-blog-stats/reviews/?rate=5#new-post" title="<?php esc_attr_e('THANK YOU for your support!', 'simple-blog-stats'); ?>"><?php esc_html_e('give it a 5-star rating', 'simple-blog-stats'); ?>&nbsp;&raquo;</a>
								</p>
							</div>
						</div>
					</div>
					
					<div id="mm-panel-primary" class="postbox">
						<h2><?php esc_html_e('Shortcodes', 'simple-blog-stats'); ?></h2>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<p><?php esc_html_e('Here you&rsquo;ll find your shortcodes along with options to customize the corresponding text and/or markup. Leave the before/after fields blank to disable.', 'simple-blog-stats'); ?></p>
							<div class="mm-table-wrap">
								<table class="widefat">
									<thead>
										<tr>
											<th><?php esc_html_e('Display before shortcode', 'simple-blog-stats'); ?></th>
											<th><?php esc_html_e('Shortcode / Options', 'simple-blog-stats'); ?></th>
											<th><?php esc_html_e('Output', 'simple-blog-stats'); ?></th>
											<th><?php esc_html_e('Display after shortcode', 'simple-blog-stats'); ?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_posts_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_posts_before]"><?php echo esc_textarea($sbs_options['count_posts_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_posts]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of posts', 'simple-blog-stats'); ?> 
												<br><?php esc_html_e('can be customized,', 'simple-blog-stats'); ?> 
												<br><?php esc_html_e('check out the', 'simple-blog-stats'); ?> 
												<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/simple-blog-stats/#installation"><?php esc_html_e('docs', 'simple-blog-stats'); ?>&nbsp;&raquo;</a></div>
											</td>
											<td><?php echo do_shortcode('[sbs_posts]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_posts_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_posts_after]"><?php echo esc_textarea($sbs_options['count_posts_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_pages_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_pages_before]"><?php echo esc_textarea($sbs_options['count_pages_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_pages]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of pages', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_pages]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_pages_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_pages_after]"><?php echo esc_textarea($sbs_options['count_pages_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_drafts_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_drafts_before]"><?php echo esc_textarea($sbs_options['count_drafts_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_drafts]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of drafts', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_drafts]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_drafts_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_drafts_after]"><?php echo esc_textarea($sbs_options['count_drafts_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_comments_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_comments_before]"><?php echo esc_textarea($sbs_options['count_comments_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_comments]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of comments', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_comments]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_comments_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_comments_after]"><?php echo esc_textarea($sbs_options['count_comments_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_moderated_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_moderated_before]"><?php echo esc_textarea($sbs_options['count_moderated_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_moderated]</code>
												<div class="mm-item-caption"><?php esc_html_e('moderated comments', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_moderated]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_moderated_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_moderated_after]"><?php echo esc_textarea($sbs_options['count_moderated_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_approved_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_approved_before]"><?php echo esc_textarea($sbs_options['count_approved_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_approved]</code>
												<div class="mm-item-caption"><?php esc_html_e('approved comments', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_approved]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_approved_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_approved_after]"><?php echo esc_textarea($sbs_options['count_approved_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_users_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_users_before]"><?php echo esc_textarea($sbs_options['count_users_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_users]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of users', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_users]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_users_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_users_after]"><?php echo esc_textarea($sbs_options['count_users_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_cats_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_cats_before]"><?php echo esc_textarea($sbs_options['count_cats_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_cats]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of categories', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_cats]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_cats_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_cats_after]"><?php echo esc_textarea($sbs_options['count_cats_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_tags_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_tags_before]"><?php echo esc_textarea($sbs_options['count_tags_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_tags]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of tags', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_tags]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_tags_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_tags_after]"><?php echo esc_textarea($sbs_options['count_tags_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_tax_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_tax_before]"><?php echo esc_textarea($sbs_options['count_tax_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_tax tax="post_tag"]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of taxonomy terms', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_tax tax="post_tag"]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_tax_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_tax_after]"><?php echo esc_textarea($sbs_options['count_tax_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_words_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_words_before]"><?php echo esc_textarea($sbs_options['count_words_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_word_count id="1"]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of words in post 1', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_word_count id="1"]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_words_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_words_after]"><?php echo esc_textarea($sbs_options['count_words_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_words_all_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_words_all_before]"><?php echo esc_textarea($sbs_options['count_words_all_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_word_count_all]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of words in all posts', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_word_count_all]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_words_all_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_words_all_after]"><?php echo esc_textarea($sbs_options['count_words_all_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[site_updated_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[site_updated_before]"><?php echo esc_textarea($sbs_options['site_updated_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_updated]</code>
												<div class="mm-item-caption"><?php esc_html_e('site last updated', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_updated]'); ?></td>
											<td>
												<label class="description" for="sbs_options[site_updated_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[site_updated_after]"><?php echo esc_textarea($sbs_options['site_updated_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[latest_posts_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[latest_posts_before]"><?php echo esc_textarea($sbs_options['latest_posts_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_latest_posts]</code>
												<div class="mm-item-caption"><?php esc_html_e('displays recent posts', 'simple-blog-stats'); ?></div>
												<div class="mm-number-option">
													<label class="description" for="sbs_options[number_of_posts]"><?php esc_html_e('Number of posts:', 'simple-blog-stats'); ?></label> 
													<input type="text" size="2" maxlength="10" name="sbs_options[number_of_posts]" value="<?php echo esc_attr($sbs_options['number_of_posts']); ?>" />
												</div>
												<div class="mm-number-option">
													<label class="description" for="sbs_options[post_length]"><?php esc_html_e('Length of posts:', 'simple-blog-stats'); ?></label> 
													<input type="text" size="2" maxlength="10" name="sbs_options[post_length]" value="<?php echo esc_attr($sbs_options['post_length']); ?>" />
												</div>
											</td>
											<td><?php echo do_shortcode('[sbs_latest_posts]'); ?></td>
											<td>
												<label class="description" for="sbs_options[latest_posts_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[latest_posts_after]"><?php echo esc_textarea($sbs_options['latest_posts_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[latest_comments_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[latest_comments_before]"><?php echo esc_textarea($sbs_options['latest_comments_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_latest_comments]</code>
												<div class="mm-item-caption"><?php esc_html_e('displays recent comments', 'simple-blog-stats'); ?></div>
												<div class="mm-number-option">
													<label class="description" for="sbs_options[number_of_comments]"><?php esc_html_e('Number of comments:', 'simple-blog-stats'); ?></label> 
													<input type="text" size="2" maxlength="10" name="sbs_options[number_of_comments]" value="<?php echo esc_attr($sbs_options['number_of_comments']); ?>" />
												</div>
												<div class="mm-number-option">
													<label class="description" for="sbs_options[comment_length]"><?php esc_html_e('Length of comments:', 'simple-blog-stats'); ?></label> 
													<input type="text" size="2" maxlength="10" name="sbs_options[comment_length]" value="<?php echo esc_attr($sbs_options['comment_length']); ?>" />
												</div>
											</td>
											<td><?php echo do_shortcode('[sbs_latest_comments]'); ?></td>
											<td>
												<label class="description" for="sbs_options[latest_comments_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[latest_comments_after]"><?php echo esc_textarea($sbs_options['latest_comments_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[count_roles_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_roles_before]"><?php echo esc_textarea($sbs_options['count_roles_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_roles role="all"]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of users per role', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_roles role="all"]'); ?></td>
											<td>
												<label class="description" for="sbs_options[count_roles_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[count_roles_after]"><?php echo esc_textarea($sbs_options['count_roles_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[sbs_cpts_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[sbs_cpts_before]"><?php echo esc_textarea($sbs_options['sbs_cpts_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_cpts_count]</code>
												<div class="mm-item-caption"><?php esc_html_e('list of CPT counts', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_cpts_count]'); ?></td>
											<td>
												<label class="description" for="sbs_options[sbs_cpts_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[sbs_cpts_after]"><?php echo esc_textarea($sbs_options['sbs_cpts_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[sbs_cpt_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[sbs_cpt_before]"><?php echo esc_textarea($sbs_options['sbs_cpt_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_cpt_count cpt="post"]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of any post type', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_cpt_count cpt="post"]'); ?></td>
											<td>
												<label class="description" for="sbs_options[sbs_cpt_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[sbs_cpt_after]"><?php echo esc_textarea($sbs_options['sbs_cpt_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[logged_users_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[logged_users_before]"><?php if (isset($sbs_options['logged_users_before'])) echo esc_textarea($sbs_options['logged_users_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_logged_users]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of logged-in users', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_logged_users]'); ?></td>
											<td>
												<label class="description" for="sbs_options[logged_users_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[logged_users_after]"><?php if (isset($sbs_options['logged_users_after'])) echo esc_textarea($sbs_options['logged_users_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[sbs_media_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[sbs_media_before]"><?php if (isset($sbs_options['sbs_media_before'])) echo esc_textarea($sbs_options['sbs_media_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_media_count type="image"]</code>
												<div class="mm-item-caption"><?php esc_html_e('number of images in library', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_media_count type="image"]'); ?></td>
											<td>
												<label class="description" for="sbs_options[sbs_media_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[sbs_media_after]"><?php if (isset($sbs_options['sbs_media_after'])) echo esc_textarea($sbs_options['sbs_media_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr>
											<td>
												<label class="description" for="sbs_options[blog_stats_before]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[blog_stats_before]"><?php echo esc_textarea($sbs_options['blog_stats_before']); ?></textarea>
												</label>
											</td>
											<td>
												<code>[sbs_blog_stats]</code>
												<div class="mm-item-caption"><?php esc_html_e('displays all blog stats', 'simple-blog-stats'); ?></div>
											</td>
											<td><?php echo do_shortcode('[sbs_blog_stats]'); ?></td>
											<td>
												<label class="description" for="sbs_options[blog_stats_after]">
													<textarea class="textarea" cols="20" rows="2" name="sbs_options[blog_stats_after]"><?php echo esc_textarea($sbs_options['blog_stats_after']); ?></textarea>
												</label>
											</td>
										</tr>
										<tr class="sbs-checkbox">
											<th scope="row"><label class="description" for="sbs_options[sbs_enable_cache]"><?php esc_html_e('Enable Cache', 'simple-blog-stats'); ?></label></th>
											<td colspan="3">
												<input name="sbs_options[sbs_enable_cache]" type="checkbox" value="1" <?php if (isset($sbs_options['sbs_enable_cache'])) checked($sbs_options['sbs_enable_cache']); ?> /> 
												<span class="mm-item-caption">
													<?php esc_html_e('Enable 12-hour caching of stats (via WP transients). NOTE: if you enable this setting, stats will update every 12 hours.', 'simple-blog-stats'); ?>
												</span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							
							<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'simple-blog-stats'); ?>" />
							
						</div>
					</div>
					
					<div id="mm-panel-secondary" class="postbox">
						<h2><?php esc_html_e('Template Tags', 'simple-blog-stats'); ?></h2>
						<div class="toggle default-hidden">
							<p><?php esc_html_e('These template tags are based on the SBS shortcodes, so check out the Shortcodes panel to customize as desired.', 'simple-blog-stats'); ?></p>
							<ul>
								<li><code>&lt;?php echo do_shortcode('[sbs_posts]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_pages]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_drafts]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_comments]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_moderated]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_approved]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_users]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_cats]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_tags]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_tax]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_updated]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_latest_posts]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_latest_comments]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_blog_stats]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_word_count]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_roles role="all"]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_word_count_all]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_cpts_count]'); ?&gt;</code></li>
								<li><code>&lt;?php echo do_shortcode('[sbs_cpt_count cpt="post"]'); ?&gt;</code></li>
							</ul><br>
						</div>
					</div>
					
					<div id="mm-restore-settings" class="postbox">
						<h2><?php esc_html_e('Restore Defaults', 'simple-blog-stats'); ?></h2>
						<div class="toggle default-hidden">
							<p>
								<input name="sbs_options[default_options]" type="checkbox" value="1" id="sbs_restore_defaults" <?php if (isset($sbs_options['default_options'])) { checked('1', $sbs_options['default_options']); } ?> /> 
								<label class="description" for="sbs_options[default_options]"><?php esc_html_e('Restore default options upon plugin deactivation/reactivation.', 'simple-blog-stats'); ?></label>
							</p>
							<p>
								<span class="mm-item-caption mm-item-caption-nomargin">
									<strong><?php esc_html_e('Tip:', 'simple-blog-stats'); ?></strong> 
									<?php esc_html_e('leave this option unchecked to remember your settings. Or, to go ahead and restore all default options, check the box, save your settings, and then deactivate/reactivate the plugin.', 'simple-blog-stats'); ?>
								</span>
							</p>
							<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'simple-blog-stats'); ?>" />
						</div>
					</div>
					
					<div id="mm-panel-current" class="postbox">
						<h2><?php esc_html_e('Show Support', 'simple-blog-stats'); ?></h2>
						<div class="toggle<?php if (isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<?php require_once('support-panel.php'); ?>
						</div>
					</div>
					
				</div>
			</div>
			
			<div id="mm-credit-info">
				<a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($sbs_homeurl); ?>" title="<?php esc_attr_e('Plugin Homepage', 'simple-blog-stats'); ?>"><?php echo esc_html($sbs_plugin); ?></a> <?php esc_html_e('by', 'simple-blog-stats'); ?> 
				<a target="_blank" rel="noopener noreferrer" href="https://twitter.com/perishable" title="<?php esc_attr_e('Jeff Starr on Twitter', 'simple-blog-stats'); ?>">Jeff Starr</a> @ 
				<a target="_blank" rel="noopener noreferrer" href="https://monzillamedia.com/" title="<?php esc_attr_e('Obsessive Web Design &amp; Development', 'simple-blog-stats'); ?>">Monzilla Media</a>
			</div>
			
		</form>
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			// toggle panels
			jQuery('.default-hidden').hide();
			jQuery('#mm-panel-toggle a').click(function(){
				jQuery('.toggle').slideToggle(300);
				return false;
			});
			jQuery('h2').click(function(){
				jQuery(this).next().slideToggle(300);
			});
			jQuery('#mm-panel-primary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-primary .toggle').slideToggle(300);
				return true;
			});
			jQuery('#mm-panel-secondary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-secondary .toggle').slideToggle(300);
				return true;
			});
			// prevent accidents
			if(!jQuery("#sbs_restore_defaults").is(":checked")){
				jQuery('#sbs_restore_defaults').click(function(event){
					var r = confirm("<?php esc_html_e('Are you sure you want to restore all default options? (this action cannot be undone)', 'simple-blog-stats'); ?>");
					if (r == true){  
						jQuery("#sbs_restore_defaults").attr('checked', true);
					} else {
						jQuery("#sbs_restore_defaults").attr('checked', false);
					}
				});
			}
		});
	</script>

<?php }
