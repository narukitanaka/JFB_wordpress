<?php

//アイキャッチ画像を有効化
add_theme_support('post-thumbnails');

//お知らせ
function post_has_archive( $args, $post_type ) {
  if ( 'post' == $post_type ) {
    $args['rewrite'] = true;
    $args['has_archive'] = 'news'; //URLとして使いたい文字列
  }
  return $args;
}
add_filter( 'register_post_type_args', 'post_has_archive', 10, 2 );


// カスタム投稿タイプの登録
function create_custom_post_types() {
    // 「商品」用のカスタム投稿タイプを作成
    register_post_type('product', array(
        'labels' => array(
            'name' => '商品',
            'singular_name' => '商品',
            'add_new' => '新規追加',
            'edit_item' => '編集'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-media-text',
        'show_in_rest' => true,
    ));

    // 「メーカー」用のカスタム投稿タイプを作成
    register_post_type('maker', array(
        'labels' => array(
            'name' => 'メーカー',
            'singular_name' => 'メーカー',
            'add_new' => '新規追加',
            'edit_item' => '編集'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 6,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-media-text',
        'show_in_rest' => true,
    ));

    // 「バイヤー」用のカスタム投稿タイプを作成
    register_post_type('buyer', array(
        'labels' => array(
            'name' => 'バイヤー',
            'singular_name' => 'バイヤー',
            'add_new' => '新規追加',
            'edit_item' => '編集'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 7,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-media-text',
        'show_in_rest' => true,
    ));
    
    // 共通のタクソノミーを登録
    register_taxonomy(
        'product-cat',
        array('product', 'maker', 'buyer'), // 両方の投稿タイプに適用
        array(
            'labels' => array(
                'name' => '商品カテゴリ',
                'add_new_item' => '新規カテゴリーを追加',
            ),
            'hierarchical' => true,
            'show_in_rest' => true,
        )
    );
    
    register_taxonomy(
        'region',
        array('product', 'maker'), // 両方の投稿タイプに適用
        array(
            'labels' => array(
                'name' => '地域',
                'add_new_item' => '新規カテゴリーを追加',
            ),
            'hierarchical' => true,
            'show_in_rest' => true,
        )
    );

    // バイヤーのタクソノミーを登録
    register_taxonomy(
        'country',
        array('buyer'),
        array(
            'labels' => array(
                'name' => '国',
                'add_new_item' => '新規カテゴリーを追加',
            ),
            'hierarchical' => true,
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'create_custom_post_types');



//カスタム投稿タイプの管理画面にカテゴリフィルターを追加
function add_taxonomy_filters() {
    global $typenow;
    // 投稿タイプごとにフィルターするタクソノミーを定義
    $taxonomy_filters = array(
        'product' => array('product-cat', 'region'),
        'maker' => array('product-cat', 'region'),
        'buyer' => array('product-cat', 'country')
    );
    // 現在の投稿タイプに対応するフィルターが定義されているか確認
    if (isset($taxonomy_filters[$typenow])) {
        // 対応するタクソノミーを取得
        $taxonomies = $taxonomy_filters[$typenow];
        
        foreach ($taxonomies as $taxonomy) {
            $taxonomy_obj = get_taxonomy($taxonomy);
            if ($taxonomy_obj) {
                $taxonomy_name = $taxonomy_obj->labels->name;
                // 現在選択されているタームを取得
                $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
                // ドロップダウンを表示
                wp_dropdown_categories(array(
                    'show_option_all' => $taxonomy_name . 'をすべて表示',
                    'taxonomy' => $taxonomy,
                    'name' => $taxonomy,
                    'orderby' => 'name',
                    'selected' => $selected,
                    'hierarchical' => true,
                    'show_count' => true,
                    'hide_empty' => true,
                    'value_field' => 'slug', // slugでフィルタリング
                ));
            }
        }
    }
}
add_action('restrict_manage_posts', 'add_taxonomy_filters');


/************************************************************************************
 * 記事の閲覧数をカウントする関数
 ***********************************************************************************/
function count_post_views() {
    static $counted = false;
    
    // すでにカウント済みの場合は処理しない（二重カウント防止）
    if ($counted) {
        return;
    }
    
    if (is_singular('product')) {
        global $post;
        $post_id = $post->ID;
        
        // 現在の閲覧数を取得
        $count = get_post_meta($post_id, 'post_views_count', true);
        
        // カウントが空の場合は0を設定
        if ($count == '') {
            $count = 0;
            delete_post_meta($post_id, 'post_views_count');
            add_post_meta($post_id, 'post_views_count', '0');
        } else {
            $count++;
            update_post_meta($post_id, 'post_views_count', $count);
        }
        
        // カウント済みとマーク
        $counted = true;
    }
}
add_action('wp_head', 'count_post_views');

/**
 * 管理画面の商品一覧に閲覧数カラムを追加
 */
function add_post_views_column($columns) {
    // 閲覧数カラムを追加
    $columns['post_views'] = '閲覧数';
    return $columns;
}
add_filter('manage_product_posts_columns', 'add_post_views_column');

/**
 * 閲覧数カラムの内容を表示
 */
function display_post_views_column($column, $post_id) {
    if ($column === 'post_views') {
        $views = get_post_meta($post_id, 'post_views_count', true);
        echo $views ? number_format($views) : '0';
    }
}
add_action('manage_product_posts_custom_column', 'display_post_views_column', 10, 2);

/**
 * 閲覧数カラムでソート可能にする
 */
function make_post_views_column_sortable($columns) {
    $columns['post_views'] = 'post_views_count';
    return $columns;
}
add_filter('manage_edit-product_sortable_columns', 'make_post_views_column_sortable');

/**
 * 閲覧数でのソートを有効にする
 */
function post_views_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // productの投稿タイプの場合のみ適用
    if ($query->get('post_type') !== 'product') {
        return;
    }
    
    if ($query->get('orderby') === 'post_views_count') {
        $query->set('meta_key', 'post_views_count');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'post_views_orderby');


/************************************************************************************
 * 子カテゴリーを選択したら親カテゴリーを自動で選択
 ***********************************************************************************/
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


/************************************************************************************
 * メーカー/バイヤー Contact Form 7関連のカスタマイズ
 ***********************************************************************************/
// Contact Form 7のフォーム要素に投稿タイトルなどの動的コンテンツを挿入
add_filter( 'wpcf7_form_elements', 'custom_wpcf7_form_elements' );
function custom_wpcf7_form_elements( $form ) {
  $form = str_replace( '{post_title}', get_the_title(), $form );
  return $form;
}

// Contact Form 7のユーザー情報自動入力
add_filter('wpcf7_form_tag_data_option', 'custom_form_tag_data_option', 10, 3);
function custom_form_tag_data_option($output, $args, $tag) {
  if (!is_user_logged_in()) {
    return $output;
  }
  $user = wp_get_current_user();
  if ($tag->name === 'your-name' && $args === 'default:user_first_name') {
    return get_user_meta($user->ID, 'first_name', true);
  }
  if ($tag->name === 'your-email' && $args === 'default:user_email') {
    return $user->user_email;
  }
  return $output;
}

// プロジェクト投稿タイプの送信先メールアドレス設定
add_filter('wpcf7_mail_components', 'custom_contact_form_recipient', 10, 3);
function custom_contact_form_recipient($components, $contact_form, $mail_key) {
  // メインのメールの場合のみ処理を実行
  if ($mail_key === 'mail') {
    $post_id = get_the_ID();
    
    if (!$post_id) {
      $referer = wp_get_referer();
      $post_id = url_to_postid($referer);
    }
    
    // maker または buyer 投稿タイプの場合に処理
    $post_type = get_post_type($post_id);
    if ($post_id && ($post_type === 'maker' || $post_type === 'buyer')) {
      $recipient = get_field('mail-address', $post_id);
      if ($recipient && is_email($recipient)) {
        $components['recipient'] = $recipient;
      } else {
        // エラーフラグをセット
        global $wpcf7_invalid_mail;
        $wpcf7_invalid_mail = true;
      }
    }
  }
  return $components;
}

// フォーム送信時のエラーメッセージを追加
add_filter('wpcf7_validate', 'custom_mail_validation', 11, 2);
function custom_mail_validation($result, $tags) {
  global $wpcf7_invalid_mail;
  if (!empty($wpcf7_invalid_mail)) {
    $result->invalidate('', 'We are currently not accepting inquiries. Please try again later.');
  }
  return $result;
}
// Contact Form 7のnonce検証をスキップ
// 注：セキュリティ上のリスクがあるため、開発環境でのみ使用することを推奨
add_filter('wpcf7_verify_nonce', '__return_true');


/************************************************************************************
 * 謎のpタグ対処
 ***********************************************************************************/
// 投稿の自動整形を無効化
remove_filter('the_content', 'wpautop');
// 抜粋の自動整形を無効化
remove_filter('the_excerpt', 'wpautop');
// Contact Form 7の自動整形を無効化
add_filter('wpcf7_autop_or_not', '__return_false');

