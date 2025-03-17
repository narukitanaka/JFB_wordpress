<?php

// 子カテゴリーを選択したら親カテゴリーを自動で選択
function enqueue_custom_script() {
    wp_enqueue_script(
        'custom-script',
        get_template_directory_uri() . '/js/custom-script.js',
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data' ),
        filemtime( get_template_directory() . '/js/custom-script.js' ),
        true
    );
}
add_action( 'enqueue_block_editor_assets', 'enqueue_custom_script' );
