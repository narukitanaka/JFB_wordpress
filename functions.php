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
add_filter('wpcf7_form_elements', 'custom_wpcf7_form_elements');
function custom_wpcf7_form_elements($form) {
  // 現在の投稿タイトルを取得
  $post_title = get_the_title();
  
  // {post_title}をタイトルで置換
  $form = str_replace('{post_title}', $post_title, $form);
  
  return $form;
}

function get_maker_email_for_cf7($form_tag) {
  if ('your-recipient' === $form_tag['name']) {
    $post_id = get_the_ID();
    $email = get_field('mail-address', $post_id);
    if ($email) {
      $form_tag['values'][] = $email;
      $form_tag['labels'][] = $email;
    }
  }
  return $form_tag;
}
add_filter('wpcf7_form_tag', 'get_maker_email_for_cf7', 10, 1);

// フォーム上部のバリデーションエラーを非表示にする
add_filter('wpcf7_display_message', 'custom_wpcf7_display_message', 10, 2);
function custom_wpcf7_display_message($message, $status) {
    if ($status === 'validation_error') {
        return ''; // 空の文字列を返してエラーメッセージを非表示にする
    }
    return $message;
}


/************************************************************************************
 * 謎のpタグ対処
 ***********************************************************************************/
// 投稿の自動整形を無効化
remove_filter('the_content', 'wpautop');
// 抜粋の自動整形を無効化
remove_filter('the_excerpt', 'wpautop');
// Contact Form 7の自動整形を無効化
add_filter('wpcf7_autop_or_not', '__return_false');


/************************************************************************************
 * ユーザーがログインしていなければログインページにリダイレクト
 ***********************************************************************************/
// function redirect_non_logged_users_to_login_page() {
//   // ユーザーがログインしていない場合
//   if (!is_user_logged_in()) {
//     // 除外すべきページ（リダイレクトループを防ぐため）
//     $excluded_pages = array(
//       'login',          // loginページ自体
//       'register',       // もし登録ページがある場合
//       'password', // もしパスワードリセットページがある場合
//     );
//     // 現在のページが除外ページリストにないこと、admin画面でないこと、AJAX処理でないことを確認
//     if (!is_page($excluded_pages) && 
//       !is_admin() && 
//       !wp_doing_ajax() && 
//       !strpos($_SERVER['REQUEST_URI'], 'wp-login.php') && 
//       !strpos($_SERVER['REQUEST_URI'], 'wpmem')) {
//       // loginページへリダイレクト
//       $login_url = get_permalink(get_page_by_path('login'));
//       // リダイレクト先が取得できなかった場合はバックアップとしてhome_urlを使用
//       if (empty($login_url)) {
//           $login_url = home_url('/login/');
//       }
//       // 現在のURLをリダイレクト後のリダイレクト先として保存
//       if (!empty($_SERVER['REQUEST_URI'])) {
//           $redirect_to = home_url($_SERVER['REQUEST_URI']);
//           $login_url = add_query_arg('redirect_to', urlencode($redirect_to), $login_url);
//       }
//       wp_redirect($login_url);
//       exit;
//     }
//   }
// }
// add_action('template_redirect', 'redirect_non_logged_users_to_login_page');


/************************************************************************************
 * wp-login.phpへの直接アクセスをブロックし、カスタムログインページにリダイレクト
 ***********************************************************************************/
// function custom_logout_redirect() {
//     // カスタムログインページのURLを取得
//     $login_url = get_permalink(get_page_by_path('login'));
    
//     // ページが見つからない場合のバックアップ
//     if (empty($login_url)) {
//         $login_url = home_url('/login/');
//     }
    
//     // ログアウトURLをカスタマイズ
//     return $login_url;
// }
// add_filter('logout_redirect', 'custom_logout_redirect', 10, 3);

// function block_wp_login() {
//     // 現在のURLパスを取得
//     $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
//     // wp-login.phpへのアクセスをチェック（ログアウト処理は除外）
//     if (strpos($request_uri, 'wp-login.php') !== false && !isset($_GET['action']) || 
//         (isset($_GET['action']) && $_GET['action'] != 'logout')) {
        
//         // カスタムログインページのURLを取得
//         $login_url = get_permalink(get_page_by_path('login'));
        
//         // ページが見つからない場合のバックアップ
//         if (empty($login_url)) {
//             $login_url = home_url('/login/');
//         }
        
//         // リダイレクト
//         wp_redirect($login_url);
//         exit;
//     }
// }
// add_action('init', 'block_wp_login');


/************************************************************************************
 * wp-membersのテキスト変更
 ***********************************************************************************/
add_filter( 'wpmem_default_text', 'sv_wpmem_default_text' );
function sv_wpmem_default_text( $text ) {
    //ログイン画面
    $text['login_heading'] = 'J-FOOD HUB';
    $text['login_username'] = 'Email Address';
    $text['login_password'] = 'Password';
    $text['login_button']   = 'Sign in';
    $text['remember_me']    = 'Save your login information';
    //ログインしてる時
    $text['login_welcome']    = 'Hello, Mr. %s';
    $text['login_logout']    = 'Click to Sign out';

    //登録画面
    $text['register_status'] = 'Hello, Mr. %s';
    $text['register_logout'] = 'Click to Sign out';
    $text['register_continue'] = 'View Site';

    //パスワードリセット
    $text['pwdchg_password1'] = 'New Password';
    $text['pwdchg_password2'] = 'Confirm New Password';
    $text['pwdchg_button'] = 'Update Password';
    
    // その他必要なテキスト変更をここに追加
    
    return $text;
}

//テキストを直接オーバーライド
add_filter('gettext', 'my_custom_wpmem_translations', 20, 3);
function my_custom_wpmem_translations($translated_text, $text, $domain) {
    // WP-Membersのドメインに限定
    if ($domain === 'wp-members') {
      // 日本語訳をオーバーライド（既に翻訳されている場合）
      switch ($translated_text) {
        case 'メールアドレス':
        case 'メール':
          return 'Email Address';
        case 'パスワード':
          return 'Password';
        case '登録':
          return 'Sign up';
      }
    }
    return $translated_text;
}

//ログインエラーのメッセージ
add_filter( 'wpmem_login_failed', 'my_login_failed_msg' );
function my_login_failed_msg( $str )
{
  $str = 'Error : user not find';
  return $str;
}


/************************************************************************************
 * メーカとバイヤーのプロフィールページ（管理画面）の特定の項目を非表示にする
 ***********************************************************************************/
function hide_profile_fields_for_specific_roles() {
  $user = wp_get_current_user();
  if (in_array('maker', (array) $user->roles) || in_array('buyer', (array) $user->roles)) {
    // JavaScriptを使って項目を非表示にする
    ?>
    <style type="text/css">
      /* 個人設定グループの項目 */
      .user-admin-color-wrap,       /* 管理画面の配色 */
      .user-admin-bar-front-wrap,   /* ツールバー */
      .user-language-wrap,          /* 言語 */
      
      /* 名前グループの項目 */
      .user-first-name-wrap,        /* 名 */
      .user-last-name-wrap,         /* 姓 */
      .user-nickname-wrap,          /* ニックネーム */
      .user-display-name-wrap,      /* ブログ上の表示名 */
      
      /* 連絡先情報グループの項目 */
      .user-url-wrap,               /* サイト */
      
      /* あなたについてグループの項目 */
      .user-description-wrap,       /* プロフィール情報 */
      .user-profile-picture,        /* プロフィール写真 */
      
      /* アカウント管理グループの項目 */
      .sessions-list,               /* セッション */
      
      /* アプリケーションパスワードグループ */
      .application-passwords-section /* アプリケーションパスワード */ {
          display: none !important;
      }
      .application-passwords {
          display: none !important;
      }
      .user-sessions-wrap {
          display: none !important;
      }
      /* 全ての見出しを非表示 */
      #profile-page h2,h3 {
          display: none !important;
      }
    </style>
    <?php
  }
}
add_action('admin_head-profile.php', 'hide_profile_fields_for_specific_roles');
add_action('admin_head-user-edit.php', 'hide_profile_fields_for_specific_roles');


/************************************************************************************
 * メーカとバイヤーの管理画面サイドバーを編集
 ***********************************************************************************/
function remove_dashboard_for_specific_roles() {
    // 現在のユーザーを取得
    $user = wp_get_current_user();
    // 対象となる権限グループかチェック
    if (in_array('maker', (array) $user->roles) || in_array('buyer', (array) $user->roles)) {
        // ダッシュボードメニューを削除
        remove_menu_page('index.php');
    }
}
add_action('admin_menu', 'remove_dashboard_for_specific_roles', 999);


/************************************************************************************
 * メーカとバイヤーがプロフィールを更新したら管理者に通知メールを送る
 ***********************************************************************************/
// function send_notification_on_profile_update($user_id) {
//     // 更新されたユーザー情報を取得
//     $user = get_userdata($user_id);
    
//     // 対象の権限グループかチェック（大文字小文字を区別しない）
//     $target_roles = array('maker', 'buyer', 'Maker', 'Buyer');
//     $is_target = false;
    
//     foreach ((array) $user->roles as $role) {
//       if (in_array(strtolower($role), array_map('strtolower', $target_roles))) {
//         $is_target = true;
//         break;
//       }
//     }
//     // 対象ユーザーの場合に処理を実行
//     if ($is_target) {
//         // 通知メールの宛先
//         $to = 'register@g-hill.jp';
        
//         // サイト名を取得
//         $site_name = get_bloginfo('name');
        
//         // メールの件名
//         $subject = 'プロフィールが更新されました';
        
//         // 送信元（メールヘッダーを修正）
//         $headers = array(
//             'From: ' . $site_name . ' <' . get_option('admin_email') . '>'
//         );
        
//         // メール本文
//         $message = $user->display_name . 'のプロフィールが更新されました。' . "\n\n";
//         $message .= '更新ユーザーのメールアドレス: ' . $user->user_email . "\n";
//         $message .= '更新時間: ' . current_time('Y-m-d H:i:s');
        
//         // メール送信
//         $sent = wp_mail($to, $subject, $message, $headers);
//     }
// }
// add_action('profile_update', 'send_notification_on_profile_update', 10, 1);


function send_notification_on_self_profile_update($user_id) {
    // 更新されたユーザー情報を取得
    $user = get_userdata($user_id);
    
    // 現在ログイン中のユーザーを取得
    $current_user = wp_get_current_user();
    
    // 自分自身の更新かどうかをチェック
    $is_self_update = ($current_user->ID == $user_id);
    
    // 自分自身の更新でない場合は処理を中止
    if (!$is_self_update) {
        return;
    }
    
    // 対象の権限グループかチェック（大文字小文字を区別しない）
    $target_roles = array('maker', 'buyer', 'Maker', 'Buyer');
    $is_target = false;
    
    foreach ((array) $user->roles as $role) {
      if (in_array(strtolower($role), array_map('strtolower', $target_roles))) {
        $is_target = true;
        break;
      }
    }
    
    // 対象ユーザーの場合に処理を実行
    if ($is_target) {
        // 通知メールの宛先
        $to = 'register@g-hill.jp';
        
        // サイト名を取得
        $site_name = get_bloginfo('name');
        
        // メールの件名
        $subject = 'プロフィールが更新されました';
        
        // 送信元（メールヘッダーを修正）
        $headers = array(
            'From: ' . $site_name . ' <' . get_option('admin_email') . '>'
        );
        
        // メール本文
        $message = $user->display_name . 'のプロフィールが更新されました。' . "\n\n";
        $message .= '更新ユーザーのメールアドレス: ' . $user->user_email . "\n";
        $message .= '更新時間: ' . current_time('Y-m-d H:i:s');
        
        // メール送信
        $sent = wp_mail($to, $subject, $message, $headers);
    }
}
add_action('profile_update', 'send_notification_on_self_profile_update', 10, 1);