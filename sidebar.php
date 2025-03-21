<aside>

  <div class="sidebar-wrapper">
    
    <div class="snav_list home">
      <p><a href="<?php echo home_url('/'); ?>">Home</a></p>
    </div>

    <div class="snav_list food">
      <p>Categories</p>
      <nav>
        <ul>
          <?php
          // product-catの親カテゴリー（parent=0）を取得
          $parent_categories = get_terms(array(
            'taxonomy' => 'product-cat',
            'parent' => 0,
            'hide_empty' => false, // 投稿のないカテゴリーも表示
            'meta_key' => 'category_order', // ACFで設定したフィールド名
            'orderby' => 'meta_value_num', // 数値としてソート
            'order' => 'ASC', // 昇順（小さい数字が先）
          ));
          // カテゴリーが存在するか確認
          if (!empty($parent_categories) && !is_wp_error($parent_categories)) {
            foreach ($parent_categories as $category) {
              // フィルター適用済みのproduct一覧ページへのリンクを作成
              $filtered_link = home_url('/product/?category%5B%5D=' . $category->slug . '&s=');
              echo '<li><a href="' . esc_url($filtered_link) . '">' . esc_html($category->name) . '</a></li>';
            }
          }
          ?>
        </ul>
      </nav>
    </div>

    <div class="snav_list region">
      <p>Region</p>
      <nav>
        <ul>
          <?php
          // regionタクソノミーの項目を取得
          $regions = get_terms(array(
            'taxonomy' => 'region',
            'hide_empty' => false, // 投稿のないタクソノミーも表示
          ));
          // タクソノミーが存在するか確認
          if (!empty($regions) && !is_wp_error($regions)) {
            foreach ($regions as $region) {
              // フィルター適用済みのproduct一覧ページへのリンクを作成
              $filtered_link = home_url('/product/?region%5B%5D=' . $region->slug . '&s=');
              echo '<li><a href="' . esc_url($filtered_link) . '">' . esc_html($region->name) . '</a></li>';
            }
          }
          ?>
        </ul>
      </nav>
    </div>

    <div class="snav_list maker">
      <p><a href="<?php echo home_url('/maker'); ?>">Maker</a></p>
    </div>

    <div class="side-btnArea">
      <div class="btn-wrap side-btn">
        <a class="btn bgc-bl" href="<?php echo home_url('/product'); ?>">Serch Products</a>
      </div>

      <div class="btn-wrap side-btn">
        <a class="btn bgc-bl" href="#">My Page</a>
      </div>
    </div>
  </div>

</aside>