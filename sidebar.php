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
              // カテゴリーのリンク先URL
              $category_link = get_term_link($category);
              // エラーチェック
              if (!is_wp_error($category_link)) {
                echo '<li><a href="' . esc_url($category_link) . '">' . esc_html($category->name) . '</a></li>';
              }
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
          <li><a href="#">Okinawa</a></li>
          <li><a href="#">Kyoto</a></li>
          <li><a href="#">Fukuoka</a></li>
        </ul>
      </nav>
    </div>

    <div class="snav_list maker">
      <p><a href="#">Maker</a></p>
    </div>

    <div class="side-btnArea">
      <div class="btn-wrap side-btn">
        <a class="btn bgc-bl" href="#">Serch Products</a>
      </div>

      <div class="btn-wrap side-btn">
        <a class="btn bgc-bl" href="#">My Page</a>
      </div>
    </div>
  </div>

</aside>