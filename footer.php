  <?php if ( !is_page(array('login', 'register','password')) ) : ?>
  <footer>
    <div class="footer-inner">

      <div class="logo img-box u-sp"><img src="<?php echo get_template_directory_uri(); ?>/images/jfh-logo_yoko.png" alt=""></div>


      <div class="footerNav-wrap">
        <div class="fnav_list">
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

        <div class="fnav_list">
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

        <div class="fnav_list">
          <p><a href="<?php echo home_url('/maker'); ?>">Maker</a></p>
        </div>

        <div class="fnav_list">
          <p>Get to know us</p>
          <nav class="nav-accordion">
            <ul>
              <li><a href="<?php echo home_url('/contact'); ?>">Inquiries about J-FOOD HUB</a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Operating company</a></li>
            </ul>
          </nav>
        </div>

      </div>

      <div class="logo img-box u-pc"><img src="<?php echo get_template_directory_uri(); ?>/images/jfh-logo_yoko.png" alt=""></div>

    </div>
    <p><small>@2025 J-FOOD HUB</small></p>
  </footer>
  <?php endif; ?>

  <!-- js -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/js/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/filter-script.js"></script>
  <script>
    //商品詳細スライダー
    const swiperitem = new Swiper(".single-productSwiper", {
      loop: true,
    }); 
    function thumbnail(index) {
      swiperitem.slideTo(index);
    }
  </script>
  <?php wp_footer(); ?>
</body>

</html>