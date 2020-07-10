<?php
/**
 * To create the clone/duplicate copy of the current post of the WordPress.
 * @param  int $post_id post id for which you have to create the clone
 * @return int return the post if of the newly created/cloned post
 */
function duplicate_post($post_id) {

    if (empty($post_id)) {
        return 0;
    }

    $oldpost = get_post($post_id);
    $post = array(
        'post_title' => get_the_title($post_id),
        'post_status' => $oldpost->post_status,
        'post_type' => $oldpost->post_type,
        'post_author' => $oldpost->post_author,
    );
    $new_post_id = wp_insert_post($post);

    // to clone the post meta
    $metas = get_post_meta($post_id);
    foreach ($metas as $key => $values) {
        foreach ($values as $value) {
            add_post_meta($new_post_id, $key, $value);
        }
    }

    // to clone the post taxonomies
    $taxonomies = get_object_taxonomies($oldpost->post_type);
    foreach ($taxonomies as $taxonomy) {
        $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
        wp_set_object_terms($new_post_id, $post_terms, $taxonomy, FALSE);
    }

    return $new_post_id;
}
