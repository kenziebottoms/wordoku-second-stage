<?php

add_filter('wp_head', 'fifu_add_js');
add_filter('wp_head', 'fifu_add_social_tags');
add_filter('wp_head', 'fifu_add_sirv_js');
add_filter('wp_head', 'fifu_apply_css');

function fifu_add_js() {
    include 'html/script.html';
}

function fifu_add_social_tags() {
    $post_id = get_the_ID();
    $url = get_post_meta($post_id, 'fifu_image_url', true);
    $title = get_the_title($post_id);
    $description = wp_strip_all_tags(get_post_field('post_content', $post_id));

    if ($url && get_option('fifu_social') == 'toggleon')
        include 'html/social.html';
}

function fifu_add_sirv_js() {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (is_plugin_active('sirv/sirv.php')) {
        include 'html/sirv.html';
    }
}

function fifu_apply_css() {
    if (get_option('fifu_wc_lbox') == 'toggleoff')
        echo '<style>[class$="woocommerce-product-gallery__trigger"] {display:none !important;}</style>';
}

add_action('the_post', 'fifu_choose');

function fifu_choose($post) {
    $post_id = $post->ID;

    $image_url = get_post_meta($post_id, 'fifu_image_url', true);

    $featured_image = get_post_meta($post_id, '_thumbnail_id', true);

    if ($image_url) {
        if (!$featured_image)
            update_post_meta($post_id, '_thumbnail_id', -1);
    }
    else {
        if ($featured_image == -1)
            delete_post_meta($post_id, '_thumbnail_id');
    }
}

add_filter('post_thumbnail_html', 'fifu_replace', 10, 2);

function fifu_replace($html, $post_id) {
    $url = get_post_meta($post_id, 'fifu_image_url', true);
    $alt = get_post_meta($post_id, 'fifu_image_alt', true);

    return empty($url) ? $html : fifu_get_html($url, $alt);
}

function is_ajax_call() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || wp_doing_ajax();
}

function fifu_get_html($url, $alt) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (is_plugin_active('sirv/sirv.php') && strpos($url, "sirv.com") !== false)
        return sprintf('<!-- Featured Image From URL plugin --> <img class="Sirv" data-src="%s">', $url);

    return sprintf('<!-- Featured Image From URL plugin --> <img %s alt="%s" style="%s">', fifu_lazy_url($url), $alt, fifu_should_hide() ? 'display:none' : '');
}

add_filter('the_content', 'fifu_add_to_content');

function fifu_add_to_content($content) {
    if (is_singular() && has_post_thumbnail() && get_option('fifu_content') == 'toggleon')
        return get_the_post_thumbnail() . $content;
    else
        return $content;
}

add_filter('wp_get_attachment_url', 'fifu_replace_attachment_url', 10, 2);

function fifu_replace_attachment_url($att_url, $att_id) {
    if ($att_id == get_post_thumbnail_id(get_the_ID())) {
        $url = get_post_meta(get_the_ID(), 'fifu_image_url', true);
        if ($url)
            $att_url = $url;
    }
    return $att_url;
}

add_filter('wp_get_attachment_image_src', 'fifu_replace_attachment_image_src', 10, 2);

function fifu_replace_attachment_image_src($image, $att_id) {
    if ($att_id == get_post_thumbnail_id(get_the_ID())) {
        $url = get_post_meta(get_the_ID(), 'fifu_image_url', true);
        if ($url) {
            return array(
                $url,
                0,
                0,
                false
            );
        }
    }
    return $image;
}

function fifu_should_hide() {
    return ((is_singular('post') && get_option('fifu_hide_post') == 'toggleon') || (is_singular('page') && get_option('fifu_hide_page') == 'toggleon'));
}

add_filter('genesis_get_image', 'fifu_genesis_image', 10, 4);

function fifu_genesis_image($args, $var1, $var2, $src) {
    return $src ? fifu_replace($args, get_the_ID()) : $args;
}

function fifu_lazy_url($url) {
    if (get_option('fifu_lazy') != 'toggleon' || is_ajax_call())
        return 'src="' . $url . '"';
    return (is_home() || (class_exists('WooCommerce') && is_shop()) ? 'data-src="' : 'src="') . $url . '"';
}
