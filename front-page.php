<?php get_header(); ?>
  <main>

    <div class="contents-wrap">

      <?php get_sidebar(); ?>

      <div class="contents" id="topPage">

        <div class="kv">
          <div class="top-bannerSwiper swiper">
            <div class="swiper-wrapper">

              <?php 
              // ACFの繰り返しフィールドを取得
              if (have_rows('top-banner_repeat')) :
                while (have_rows('top-banner_repeat')) : the_row();
                  // グループフィールドの値を取得
                  $group = get_sub_field('top-banner_group');
                  if ($group) {
                    $banner_pc = $group['banner-img_pc'];
                    $banner_sp = $group['banner-img_sp'];
                    $banner_url = $group['banner_url'];
                    // URLが設定されているかチェック
                    $has_link = !empty($banner_url);
                    $link_attributes = $has_link ? 'href="' . esc_url($banner_url) . '"' : 'style="pointer-events: none;" href="#"';
              ?>

              <div class="swiper-slide">
                <a <?php echo $link_attributes; ?>>
                  <div class="img-box">
                    <picture>
                      <?php if (!empty($banner_sp)) : ?>
                        <source srcset="<?php echo esc_url($banner_sp); ?>" media="(max-width: 768px)">
                      <?php endif; ?>
                      <?php if (!empty($banner_pc)) : ?>
                        <img src="<?php echo esc_url($banner_pc); ?>" alt="">
                      <?php endif; ?>
                    </picture>
                  </div>
                </a>
              </div>

              <?php } endwhile; endif; ?>

            </div>
            <!-- pagenation -->
            <div class="swiper-pagination"></div>
            <!-- prev/next_btn -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>
        </div>

        <section class="mostPopular">
          <div class="inner">

            <h2>Most Popular</h2>

            <div class="btn-wrap">
              <a class="btn bgc-bl" href="#">All Products</a>
            </div>

            <div class="PopularSwiper itemCard swiper">
              <div class="swiper-wrapper">

                <?php
                // PV数順に商品を取得
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 10,  // 最大10件
                    'meta_key' => 'post_views_count',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                );
                $products = new WP_Query($args);
                if ($products->have_posts()) :
                  while ($products->have_posts()) : $products->the_post();
                    // アイキャッチ画像
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    if (!$thumbnail) {
                        $thumbnail = get_template_directory_uri() . '/images/noimage.png';
                    }
                    // 商品カテゴリーを取得
                    $categories = get_the_terms(get_the_ID(), 'product-cat');
                    // 地域を取得
                    $regions = get_the_terms(get_the_ID(), 'region');
                    // メーカー関連投稿を取得
                    $maker_post = get_field('item_maker');
                    // メーカー名を取得
                    $maker_name = '';
                    if ($maker_post) {
                      if (is_object($maker_post) && isset($maker_post->post_title)) {
                          $maker_name = $maker_post->post_title;
                      } elseif (is_array($maker_post) && isset($maker_post['post_title'])) {
                          $maker_name = $maker_post['post_title'];
                      } elseif (is_numeric($maker_post)) {
                          $maker_name = get_the_title($maker_post);
                      } elseif (is_array($maker_post) && isset($maker_post[0])) {
                        // 複数選択可能な場合は最初の一つを使用
                        if (is_object($maker_post[0])) {
                            $maker_name = $maker_post[0]->post_title;
                        } elseif (isset($maker_post[0]['post_title'])) {
                            $maker_name = $maker_post[0]['post_title'];
                        } else {
                          $maker_name = get_the_title($maker_post[0]);
                        }
                      }
                    }
                ?>

                <div class="swiper-slide">
                  <a href="<?php the_permalink(); ?>">
                    <div class="img-box">
                      <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>">
                    </div>
                    <div class="cate">
                      <?php 
                      if ($categories && !is_wp_error($categories)) {
                        // 親カテゴリーとサブカテゴリーを分ける
                        $parent_cats = array();
                        $child_cats = array();
                        foreach ($categories as $category) {
                          if ($category->parent == 0) {
                            $parent_cats[] = $category;
                          } else {
                            $child_cats[] = $category;
                          }
                        }
                        // 親カテゴリーを表示
                        foreach ($parent_cats as $parent) {
                          // カテゴリの色を取得
                          $color = get_field('cate_color', 'product-cat_' . $parent->term_id);
                          echo '<span class="parent" style="background-color: ' . esc_attr($color) . '; border-color: ' . esc_attr($color) . ';">' . esc_html($parent->name) . '</span>';
                        }
                        // 子カテゴリーを表示
                        foreach ($child_cats as $child) {
                          // 親カテゴリーのIDを取得
                          $parent_id = $child->parent;
                          // 親カテゴリーの色を取得
                          $color = get_field('cate_color', 'product-cat_' . $parent_id);
                          echo '<span class="child" style="border-color: ' . esc_attr($color) . '; color: ' . esc_attr($color) . ';">' . esc_html($child->name) . '</span>';
                        }
                      }
                      ?>
                    </div>
                    <div class="name"><?php the_title(); ?></div>
                    <div class="maker"><?php echo esc_html($maker_name); ?></div>
                    <div class="region">
                      <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="region">
                      <?php 
                      if ($regions && !is_wp_error($regions) && !empty($regions)) {
                        $region = $regions[0];
                        echo '<span>' . esc_html($region->name) . '</span>';
                      }
                      ?>
                    </div>
                  </a>
                </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
              </div>
            </div>

            <!-- prev/next_btn -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

          </div>
        </section>

        <section class="categories">
          <div class="inner">
            <h2>Categories</h2>
            <ul>
              <li>
                <a href="#">
                  <div class="img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/cate_fresh-products.png" alt=""></div>
                  <p>Fresh Products</p>
                </a>
              </li>
              <li>
                <a href="#">
                  <div class="img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/cate_processed-foods.png" alt=""></div>
                  <p>Processed Foods</p>
                </a>
              </li>
              <li>
                <a href="#">
                  <div class="img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/cate_seasonings.png" alt=""></div>
                  <p>Seasonings</p>
                </a>
              </li>
              <li>
                <a href="#">
                  <div class="img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/cate_sweets-snacks.png" alt=""></div>
                  <p>Sweet & Snacks</p>
                </a>
              </li>
              <li>
                <a href="#">
                  <div class="img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/cate_beverages.png" alt=""></div>
                  <p>Bevarages</p>
                </a>
              </li>
              <li>
                <a href="#">
                  <div class="img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/cate_all.png" alt=""></div>
                  <p>All</p>
                </a>
              </li>
            </ul>
          </div>
        </section>

        <section class="maker">
          <div class="inner">
            <h2>Maker</h2>

            <div class="itemCard">

            <?php
              // makerの投稿を取得
              $args = array(
                  'post_type' => 'maker',
                  'posts_per_page' => -1, // すべて表示（または必要な数に制限）
                  'orderby' => 'title',
                  'order' => 'ASC',
              );

              $makers = new WP_Query($args);

              if ($makers->have_posts()) :
                  while ($makers->have_posts()) : $makers->the_post();
                      // アイキャッチ画像の取得
                      $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                      
                      // product-catカテゴリーを取得
                      $categories = get_the_terms(get_the_ID(), 'product-cat');
                      
                      // 地域を取得
                      $regions = get_the_terms(get_the_ID(), 'region');
              ?>
              <div class="box">
                <?php if ($thumbnail) : ?>
                <div class="img-box"><img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>"></div>
                <?php endif; ?>
                
                <?php if ($categories && !is_wp_error($categories)) : ?>
                <div class="cate">
                  <?php 
                  // 親カテゴリーとサブカテゴリーを分ける
                  $parent_cats = array();
                  $child_cats = array();

                  foreach ($categories as $category) {
                      if ($category->parent == 0) {
                          $parent_cats[] = $category;
                      } else {
                          $child_cats[] = $category;
                      }
                  }
                  
                  // 親カテゴリーを表示
                  foreach ($parent_cats as $parent) {
                      echo '<span class="parent">' . esc_html($parent->name) . '</span>';
                  }
                  
                  // 子カテゴリーを表示
                  foreach ($child_cats as $child) {
                      echo '<span class="child">' . esc_html($child->name) . '</span>';
                  }
                  ?>
                </div>
                <?php endif; ?>
                
                <div class="name"><?php the_title(); ?></div>
                
                <?php if ($regions && !is_wp_error($regions) && !empty($regions)) : ?>
                <div class="region">
                  <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="region">
                  <span><?php echo esc_html($regions[0]->name); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="link-area">
                  <a class="btn bgc-wh" href="<?php the_permalink(); ?>#profile">Company Profile</a>
                  <a class="btn bgc-wh" href="<?php the_permalink(); ?>#export">Export Conditions</a>
                  <a class="btn bgc-wh" href="<?php the_permalink(); ?>#product">Product List</a>
                </div>
              </div>
              <?php endwhile; wp_reset_postdata(); endif; ?>

            </div>
          </div>
        </section>

        <section class="about">
          <div class="inner">
            <div class="about-wrap">
              <div class="text-wrap">
                <h2>
                  <span>About J-FOOD HUB</span><br>
                  FROM JAPAN, <br>
                  VIA OKINAWA,<br>
                  TO ASIA
                </h2>
                <p>
                  Okinawa-based “J-FOOD HUB” is a <br>trading service that delivers selected food products from all over Japan to Asian countries. We connect Japanese food manufacturers with overseas buyers and provide complete support for smooth exporting.
                </p>
              </div>
            </div>
          </div>
        </section>



      </div><!-- /.contents -->

    </div><!-- /.contents-wrap -->

  </main>
<?php get_footer(); ?>



