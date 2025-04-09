<?php

/************************************************************************************
 * アイキャッチ画像を有効化
 ***********************************************************************************/
add_theme_support('post-thumbnails');


/************************************************************************************
 * お知らせ
 ***********************************************************************************/
function post_has_archive( $args, $post_type ) {
  if ( 'post' == $post_type ) {
    $args['rewrite'] = true;
    $args['has_archive'] = 'news'; //URLとして使いたい文字列
  }
  return $args;
}
add_filter( 'register_post_type_args', 'post_has_archive', 10, 2 );


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
 * カスタム投稿タイプの登録
 ***********************************************************************************/
function create_custom_post_types() {
  // 「商品」用のカスタム投稿タイプを作成
  register_post_type('product', array(
    'labels' => array(
      'name' => 'Product',
      'singular_name' => 'product',
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
      'name' => 'Maker',
      'singular_name' => 'Maker',
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
      'name' => 'Buyer',
      'singular_name' => 'Buyer',
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


/************************************************************************************
 * カスタム投稿タイプの管理画面にカテゴリフィルターを追加
 ***********************************************************************************/
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
  // 管理画面やフィード、プレビューではカウントしない
  if (is_admin() || is_feed() || is_preview()) {
    return;
  }
  // 商品詳細ページのみでカウント
  if (is_singular('product')) {
    global $post;
    $post_id = $post->ID;
    // 現在の閲覧数を取得
    $count = get_post_meta($post_id, 'post_views_count', true);
    // カウントが空の場合は0を設定
    if ($count === '') {
        $count = 0;
    }
    // カウントを増加して更新
    $count = intval($count) + 1;
    update_post_meta($post_id, 'post_views_count', $count);
  }
}
add_action('template_redirect', 'count_post_views');


/************************************************************************************
 * 管理画面の商品一覧に閲覧数カラムを追加
 ***********************************************************************************/
function add_post_views_column($columns) {
    // 閲覧数カラムを追加
    $columns['post_views'] = '閲覧数';
    return $columns;
}
add_filter('manage_product_posts_columns', 'add_post_views_column');

function display_post_views_column($column, $post_id) {
    if ($column === 'post_views') {
        $views = get_post_meta($post_id, 'post_views_count', true);
        echo $views ? number_format($views) : '0';
    }
}
add_action('manage_product_posts_custom_column', 'display_post_views_column', 10, 2);


/************************************************************************************
 * 閲覧数カラムでソート可能にする
 ***********************************************************************************/
function make_post_views_column_sortable($columns) {
  $columns['post_views'] = 'post_views_count';
  return $columns;
}
add_filter('manage_edit-product_sortable_columns', 'make_post_views_column_sortable');

//閲覧数でのソートを有効にする
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
 * wp-membersのテキスト変更
 ***********************************************************************************/
add_filter( 'wpmem_default_text', 'sv_wpmem_default_text' );
function sv_wpmem_default_text( $text ) {
  //ログイン画面
  $text['login_heading'] = 'J-FOOD HUB';
  $text['login_username'] = 'Email Address or User Name';
  $text['login_password'] = 'Password';
  $text['login_button']   = 'Sign in';
  $text['remember_me']    = 'Save your login information';
  //ログインしてる時
  $text['login_welcome']    = 'Hello, Mr. %s';
  $text['login_logout']    = 'Click to Sign out';

  //登録画面
  $text['register_status'] = 'Hello, Mr. %s';
  $text['register_logout'] = 'Click to Sign out';
  $text['register_continue'] = 'Enter Site';
  $text['register_password'] = 'Password';

  //パスワードリセット
  $text['pwdchg_password1'] = 'New Password';
  $text['pwdchg_password2'] = 'Confirm New Password';
  $text['pwdchg_button'] = 'Update Password';
  
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
        case 'password':
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
  $str = '<div class="text-error">ERROR : user not find</div>';
  return $str;
}


/************************************************************************************
 * メーカとバイヤーのプロフィールページ（管理画面）の特定の項目を非表示にする
 ***********************************************************************************/
function hide_profile_fields_for_specific_roles() {
  $user = wp_get_current_user();
  if (in_array('maker', (array) $user->roles) || in_array('buyer', (array) $user->roles) || in_array('norole', (array) $user->roles)) {
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
      /* Select Your Roleを非表示 */
      .form-table:has(select[name="your_role"]) {
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
    if (in_array('maker', (array) $user->roles) || in_array('buyer', (array) $user->roles) || in_array('norole', (array) $user->roles)) {
        // ダッシュボードメニューを削除
        remove_menu_page('index.php');
    }
}
add_action('admin_menu', 'remove_dashboard_for_specific_roles', 999);



/************************************************************************************
 * メーカとバイヤーがプロフィールを更新した時の通知メール
 ***********************************************************************************/
//更新前のユーザーメタデータを保存する
function store_user_meta_before_update($user_id) {
  $user = get_userdata($user_id);
  if (!$user) return;
  // 対象の権限グループかチェック
  $target_roles = array('maker', 'buyer', 'Maker', 'Buyer');
  $is_target = false;
  foreach ((array) $user->roles as $role) {
    if (in_array(strtolower($role), array_map('strtolower', $target_roles))) {
      $is_target = true;
      break;
    }
  }
  if ($is_target) {
    // 現在のユーザーメタデータ全体を保存
    $user_meta = get_user_meta($user_id);
    update_option('_temp_user_meta_' . $user_id, $user_meta);
    
    // WordPressのデフォルトフィールド用にユーザーオブジェクトのデータも保存
    $wp_user_data = array(
      'user_email' => $user->user_email,
      // パスワードは保存されないが、変更があったことを検出するためのフラグとして使用
      'has_password_change' => false
    );
    update_option('_temp_wp_user_data_' . $user_id, $wp_user_data);
    
    // パスワード変更を検出するために、POSTデータの有無をチェック
    if (isset($_POST['pass1']) && !empty($_POST['pass1'])) {
      update_option('_temp_has_password_change_' . $user_id, true);
    } else {
      update_option('_temp_has_password_change_' . $user_id, false);
    }
  }
}
add_action('personal_options_update', 'store_user_meta_before_update', 1);
add_action('edit_user_profile_update', 'store_user_meta_before_update', 1);

 // メーカーとバイヤーがプロフィールを更新したら管理者と本人に通知メールを送る
function send_notification_on_profile_update($user_id) {
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
    // 変更されたフィールドのラベルを集めるための配列
    $changed_field_keys = array(); // 一時的にキーを保存
    $changed_fields = array(); // 最終的なラベルを保存
    
    // =====================================================================
    // WordPressデフォルトフィールドの変更を検出
    // =====================================================================
    // 以前のユーザーデータを取得
    $old_wp_data = get_option('_temp_wp_user_data_' . $user_id, array());
    
    // Eメールアドレスの変更を検出
    $email_changed = false;
    if (isset($old_wp_data['user_email']) && $old_wp_data['user_email'] != $user->user_email) {
      $email_changed = true;
    }
    
    // パスワード変更を検出
    $password_changed = false;
    $has_password_change = get_option('_temp_has_password_change_' . $user_id, false);
    if ($has_password_change) {
      $password_changed = true;
    }
    
    // =====================================================================
    // ACFフィールドの変更を検出する
    // =====================================================================
    // 更新前のメタデータを取得
    $old_meta = get_option('_temp_user_meta_' . $user_id, array());
    // 更新後の現在のメタデータを取得
    $current_meta = get_user_meta($user_id);
    // ACFのシステムフィールドを除外するためのパターン
    $exclude_patterns = array(
      '^_', // アンダースコアで始まるもの
      '^wp_', // wp_で始まるもの
      '^acf$', // acfだけのもの
      '^_acf', // _acfで始まるもの
    );
    
    // 繰り返しフィールドを検出するためのパターン
    $repeater_patterns = array(
      'buyer-img_repaet_[0-9]+_buyer-img',
      'maker-img_repaet_[0-9]+_maker-img'
    );
    
    // 繰り返しフィールドの追跡用
    $repeater_fields = array(
      'buyer-img_repaet' => false,
      'maker-img_repaet' => false
    );
    
    // 変更を検出するための処理
    // 現在のメタデータのすべてのキーを調査
    foreach ($current_meta as $key => $value) {
      // 除外パターンに一致するフィールドはスキップ
      $should_skip = false;
      foreach ($exclude_patterns as $pattern) {
        if (preg_match('/' . $pattern . '/', $key)) {
          $should_skip = true;
          break;
        }
      }
      if ($should_skip) continue;
      
      // 繰り返しフィールドのサブフィールドかチェック
      $is_repeater_subfield = false;
      $repeater_parent = '';
      foreach ($repeater_patterns as $pattern) {
        if (preg_match('/' . $pattern . '/', $key)) {
          $is_repeater_subfield = true;
          // 親フィールドを特定
          if (strpos($key, 'buyer-img_repaet') === 0) {
            $repeater_parent = 'buyer-img_repaet';
          } elseif (strpos($key, 'maker-img_repaet') === 0) {
            $repeater_parent = 'maker-img_repaet';
          }
          break;
        }
      }
      
      // 現在の値（配列の場合は最初の要素を取得）
      $current_value = is_array($value) && !empty($value) ? $value[0] : '';
      
      // 前回の値が存在するかチェック
      if (isset($old_meta[$key]) && is_array($old_meta[$key]) && !empty($old_meta[$key])) {
        $old_value = $old_meta[$key][0];
        
        // 値が変更された場合
        if ($current_value != $old_value) {
          if ($is_repeater_subfield && !empty($repeater_parent)) {
            // 繰り返しフィールドの親を記録
            $repeater_fields[$repeater_parent] = true;
          } else {
            // 通常のフィールド - 変更されたキーを追加
            $changed_field_keys[] = $key;
          }
        }
      } 
      // 新しく追加されたフィールド
      elseif (!isset($old_meta[$key]) && !empty($current_value)) {
        if ($is_repeater_subfield && !empty($repeater_parent)) {
          // 繰り返しフィールドの親を記録
          $repeater_fields[$repeater_parent] = true;
        } else {
          // 通常のフィールド - 変更されたキーを追加
          $changed_field_keys[] = $key;
        }
      }
    }
    
    // 削除されたフィールドも検出
    foreach ($old_meta as $key => $value) {
      // 除外パターンに一致するフィールドはスキップ
      $should_skip = false;
      foreach ($exclude_patterns as $pattern) {
        if (preg_match('/' . $pattern . '/', $key)) {
          $should_skip = true;
          break;
        }
      }
      if ($should_skip) continue;
      
      // 繰り返しフィールドのサブフィールドかチェック
      $is_repeater_subfield = false;
      $repeater_parent = '';
      foreach ($repeater_patterns as $pattern) {
        if (preg_match('/' . $pattern . '/', $key)) {
          $is_repeater_subfield = true;
          // 親フィールドを特定
          if (strpos($key, 'buyer-img_repaet') === 0) {
            $repeater_parent = 'buyer-img_repaet';
          } elseif (strpos($key, 'maker-img_repaet') === 0) {
            $repeater_parent = 'maker-img_repaet';
          }
          break;
        }
      }
      $old_value = is_array($value) && !empty($value) ? $value[0] : '';
      
      // フィールドが削除された場合
      if (!isset($current_meta[$key]) && !empty($old_value)) {
        if ($is_repeater_subfield && !empty($repeater_parent)) {
          // 繰り返しフィールドの親を記録
          $repeater_fields[$repeater_parent] = true;
        } else {
          // 通常のフィールド - 変更されたキーを追加
          $changed_field_keys[] = $key . '_deleted';
        }
      }
    }
    // 変更のあった繰り返しフィールドの親を追加
    foreach ($repeater_fields as $field => $changed) {
      if ($changed) {
        $changed_field_keys[] = $field;
      }
    }
    
    // =====================================================================
    // フィールドキーからラベルへの変換
    // =====================================================================
    // ACFフィールドのラベルマッピング（完全な形での直接マッピング）
    $field_labels = array(
      // ベースフィールド（Buyer & Maker共通）
      'featured_image' => 'Featured Image',
      'mail-address' => 'Form Email address',
      
      // Buyerのフィールド
      'our_strengths' => 'Our Strengths',
      'wanted_products' => 'Wanted Products',
      'buyer-img_repaet' => 'Images',
      'buyer-img' => 'Image',
      'group_company-profile' => 'Company profile',
      
      // Makerのフィールド
      'basic_information' => 'Basic Information',
      'products_categories' => 'Products Categories',
      'maker_logo' => 'Logo Image',
      'maker-img_repaet' => 'Images',
      'maker-img' => 'Image',
      'pdf_company-info' => 'Company info PDF',
      'group_export-conditions' => 'Export Conditions',
      
      // 通常のサブフィールド
      'company_name' => 'Company Name',
      'representative' => 'Representative',
      'company_location' => 'Company location',
      'business' => 'Business',
      'web-site' => 'Web site',
      'company-name' => 'Company Name',
      'company-location' => 'Company location',
      'main-products' => 'Main Products',
      'available-areas' => 'Available Areas',
      'departure' => 'Lead time to departure',
      'moq' => 'MOQ',
      
      // グループフィールドのサブフィールド（完全なキー名）
      'group_company-profile_company_name' => 'Company Name',
      'group_company-profile_representative' => 'Representative',
      'group_company-profile_company_location' => 'Company location',
      'group_company-profile_business' => 'Business',
      'group_company-profile_web-site' => 'Web site',
      
      'group_company-profile_company-name' => 'Company Name',
      'group_company-profile_company-location' => 'Company location',
      'group_company-profile_main-products' => 'Main Products',
      'group_company-profile_web-site' => 'Web site',
      
      'group_export-conditions_available-areas' => 'Available Areas',
      'group_export-conditions_departure' => 'Lead time to departure',
      'group_export-conditions_moq' => 'MOQ'
    );
    
    // 検出されたキーをラベルに変換
    foreach ($changed_field_keys as $key) {
      $is_deleted = false;
      // 削除フラグがあるかチェック
      if (substr($key, -8) === '_deleted') {
        $is_deleted = true;
        $key = substr($key, 0, -8); // _deletedを削除
      }
      // ラベルを取得
      $label = '';
      // 直接マッピングにあるかチェック
      if (isset($field_labels[$key])) {
        $label = $field_labels[$key];
      }
      // グループフィールドのパターンをチェック
      else if (preg_match('/^group_[^_]+_(.+)$/', $key, $matches)) {
        $field_name = $matches[1];
        if (isset($field_labels[$field_name])) {
          $label = $field_labels[$field_name];
        } else {
          // フィールド名から推測（アンダースコアとハイフンを空白に、先頭大文字）
          $label = ucwords(str_replace(array('_', '-'), ' ', $field_name));
        }
      }
      // 繰り返しフィールドのパターンをチェック - これは通常ここには到達しないはず
      else if (preg_match('/^(buyer|maker)-img_repaet_[0-9]+_/', $key)) {
        // 繰り返しフィールドのサブフィールドは親フィールドで表現するのでスキップ
        continue;
      }
      // それ以外のフィールド
      else {
        // ラベルが見つからない場合は、フィールド名からラベルを生成
        $label = ucwords(str_replace(array('_', '-'), ' ', $key));
      }
      // 削除された場合は印を付ける
      if ($is_deleted) {
        $label .= ' [削除]';
      }
      // 有効なラベルがあれば追加
      if (!empty($label)) {
        $changed_fields[] = $label;
      }
    }
    $changed_fields = array_unique($changed_fields);
    
    // =====================================================================
    // メール送信処理
    // =====================================================================
    
    // 変更チェック: EmailとNew Passwordの変更のみの場合は通知しない
    $only_wp_default_changes = false;
    if (empty($changed_fields) && ($email_changed || $password_changed)) {
      // WordPressデフォルトフィールドの変更のみ
      $only_wp_default_changes = true;
    }
    
    // メール送信処理: EmailとNew Passwordの変更のみの場合は送信しない
    if (!$only_wp_default_changes) {
      // サイト名を取得
      $site_name = get_bloginfo('name');
      // 送信元（メールヘッダーを修正）
      $headers = array(
        'From: ' . $site_name . ' <' . get_option('admin_email') . '>'
      );
      
      // 1. 管理者への通知メール
      $admin_to = 'test@ghdemo.xsrv.jp';
      $admin_subject = $site_name . '｜' . $user->display_name . 'のプロフィールが更新されました。';
      $admin_message = $user->display_name . 'のプロフィールが更新されました。' . "\n\n";
      $admin_message .= '更新ユーザーのメールアドレス: ' . $user->user_email . "\n";
      $admin_message .= '更新時間: ' . current_time('Y-m-d H:i:s') . "\n\n";
      
      // 変更されたフィールドの情報を追加（WordPressデフォルトフィールドは除外）
      if (!empty($changed_fields)) {
        $admin_message .= "変更された項目：\n";
        $admin_message .= "- " . implode("\n- ", $changed_fields) . "\n";
      } else {
        $admin_message .= "※変更されたフィールドは検出されませんでした。\n";
      }
      $admin_sent = wp_mail($admin_to, $admin_subject, $admin_message, $headers);
      
      // 2. ユーザー自身への通知メール
      $user_to = $user->user_email;
      $user_subject = $site_name . '｜I have sent you a request to correct your profile.';
      $user_message = "This email is an auto-reply.\n";
      $user_message .= "I have sent you a request to correct your profile.\n\n";
      $user_message .= "Please wait for a while until it is reflected on the site.";
      $user_sent = wp_mail($user_to, $user_subject, $user_message, $headers);
    }
    // 一時データを削除
    delete_option('_temp_user_meta_' . $user_id);
    delete_option('_temp_wp_user_data_' . $user_id);
    delete_option('_temp_has_password_change_' . $user_id);
  }
}
add_action('profile_update', 'send_notification_on_profile_update', 10, 1);



/************************************************************************************
 * 検索結果のView More
 ***********************************************************************************/
function load_more_search_results() {
  // セキュリティチェック
  check_ajax_referer('load_more_search_results', 'security');
  
  $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
  $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
  $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
  
  // 検索クエリ
  $args = array(
    'post_type' => $post_type,
    's' => $search,
    'posts_per_page' => 5,
    'paged' => $paged,
  );
  
  $query = new WP_Query($args);
  
  if ($query->have_posts()) {
    ob_start();
    while ($query->have_posts()) {
      $query->the_post();
      
      if ($post_type === 'product') {
        get_template_part('inc/product-card');
      } elseif ($post_type === 'maker') {
        get_template_part('inc/maker-card');
      } else { // buyer
        get_template_part('inc/buyer-card');
      }
    }
    $html = ob_get_clean();
    wp_reset_postdata();
    
    echo $html;
  }
  
  wp_die();
}
add_action('wp_ajax_load_more_search_results', 'load_more_search_results');
add_action('wp_ajax_nopriv_load_more_search_results', 'load_more_search_results');



/************************************************************************************
 * マイプロフィールへ遷移
 ***********************************************************************************/
function get_mypage_url() {
  $current_user = wp_get_current_user();
  $username = $current_user->user_login;
  
  // ユーザーの役割を取得
  $user_roles = $current_user->roles;
  // 検索するカスタム投稿タイプを決定
  $post_type = '';
  
  // makerロールを持っている場合
  if (in_array('maker', $user_roles)) {
      $post_type = 'maker';
  } 
  // buyerロールを持っている場合
  else if (in_array('buyer', $user_roles)) {
      $post_type = 'buyer';
  }

  // 投稿タイプが決定されなかった場合はプロフィールページに遷移
  if (empty($post_type)) {
      return admin_url('profile.php');
  }

  // マッチする投稿を探す
  $args = array(
    'post_type' => $post_type,
    'posts_per_page' => 1,
    'meta_query' => array(
      array(
        'key' => 'user-id', // ACFフィールド名
        'value' => $username,
        'compare' => '='
      )
    )
  );
  
  $matching_posts = get_posts($args);

  // 一致する投稿が見つかった場合はその詳細ページURLを返す
  if (!empty($matching_posts)) {
    $post_id = $matching_posts[0]->ID;
    return get_permalink($post_id);
  }
  // 一致する投稿が見つからない場合はプロフィール画面URLを返す
  return admin_url('profile.php');
}



/************************************************************************************
 * ユーザーログイン状態を取得
 ***********************************************************************************/
function is_logged_in_user() {
  return is_user_logged_in();
}

/************************************************************************************
 * ユーザー権限を取得
 ***********************************************************************************/
function is_user_maker() {
  $current_user = wp_get_current_user();
  $user_roles = $current_user->roles;
  return in_array('maker', $user_roles);
}
function is_user_buyer() {
  $current_user = wp_get_current_user();
  $user_roles = $current_user->roles;
  return in_array('buyer', $user_roles);
}
function is_user_norole() {
  $current_user = wp_get_current_user();
  $user_roles = $current_user->roles;
  return in_array('norole', $user_roles);
}



/************************************************************************************
 * ユーザーのアクセス制御
 ***********************************************************************************/
function restrict_template_access() {
  // 現在のユーザー情報を取得
  $current_user = wp_get_current_user();
  $username = $current_user->user_login;
  // バイヤー一覧・詳細ページのアクセス制限
  if ((is_post_type_archive('buyer') || is_singular('buyer')) && (is_user_buyer() || is_user_norole())) {
    if (is_singular('buyer')) {
      global $post;
      $post_user_id = get_post_meta($post->ID, 'user-id', true);
      // 自分自身の詳細ページならアクセスを許可
      if ($post_user_id === $username) {
        return;
      }
    }
    wp_redirect(home_url());
    exit;
  }
  // メーカー一覧・詳細ページのアクセス制限
  if ((is_post_type_archive('maker') || is_singular('maker')) && (is_user_maker() || is_user_norole())) {
    if (is_singular('maker')) {
      global $post;
      $post_user_id = get_post_meta($post->ID, 'user-id', true);
      // 自分自身の詳細ページならアクセスを許可
      if ($post_user_id === $username) {
        return;
      }
    }
    wp_redirect(home_url());
    exit;
  }
}
add_action('template_redirect', 'restrict_template_access');

/************************************************************************************
 * wp-login.phpへのアクセスを阻止する
 ***********************************************************************************/
// ログアウト後のリダイレクト先を変更する
function custom_logout_redirect() {
  wp_redirect(home_url('/login'));
  exit();
}
add_action('wp_logout', 'custom_logout_redirect');