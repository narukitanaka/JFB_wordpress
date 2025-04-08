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
            <div class="swiper-button-prev kv-prev"></div>
            <div class="swiper-button-next kv-next"></div>
          </div>
        </div>

        <section class="mostPopular">
          <div class="inner">

            <h2>Most Popular</h2>

            <div class="btn-wrap">
              <a class="btn bgc-bl" href="<?php echo home_url('/product'); ?>">All Products</a>
            </div>

            <div class="PopularSwiper swiper">
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
                ?>

                <div class="swiper-slide">
                  <?php get_template_part('inc/product-card'); ?>
                </div>

                <?php endwhile; wp_reset_postdata(); endif; ?>
              </div>
            </div>

            <!-- prev/next_btn -->
            <div class="swiper-button-prev popular-prev"></div>
            <div class="swiper-button-next popular-next"></div>

          </div>
        </section>

        <section class="categories">
          <div class="inner">
            <h2>Categories</h2>
            <ul>
              <?php
              // product-catの親カテゴリー（parent=0）を取得
              $parent_categories = get_terms(array(
                'taxonomy' => 'product-cat',
                'parent' => 0,
                'hide_empty' => false, // 投稿のないカテゴリーも表示
                'meta_key' => 'category_order', // ACFで設定したフィールド名（ソート用）
                'orderby' => 'meta_value_num', // 数値としてソート
                'order' => 'ASC', // 昇順（小さい数字が先）
              ));
              // カテゴリーが存在するか確認
              if (!empty($parent_categories) && !is_wp_error($parent_categories)) {
                foreach ($parent_categories as $category) {
                  // フィルター適用済みのproduct一覧ページへのリンクを作成
                  $filtered_link = home_url('/product/?category%5B%5D=' . $category->slug . '&keyword=');
                  // ACFから画像を取得
                  $image_url = get_field('cate_img', 'product-cat_' . $category->term_id);
                  // 画像がない場合のデフォルト画像
                  if (!$image_url) {
                    $image_url = get_template_directory_uri() . '/images/noimage01.png';
                  }
                  echo '<li>';
                  echo '<a href="' . esc_url($filtered_link) . '">';
                  echo '<div class="img-box"><img src="' . esc_url($image_url) . '" alt="' . esc_attr($category->name) . '"></div>';
                  echo '<p>' . esc_html($category->name) . '</p>';
                  echo '</a>';
                  echo '</li>';
                }
              }
              ?>
              <li>
                <a href="<?php echo home_url('/product'); ?>">
                  <div class="img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/cate_all.png" alt=""></div>
                  <p>All</p>
                </a>
              </li>
            </ul>
          </div>
        </section>


        <?php if (is_user_buyer()): ?>
        <section class="maker">
          <div class="inner">
            <h2>Maker</h2>

            <div class="btn-wrap">
              <a class="btn bgc-bl" href="<?php echo home_url('/maker'); ?>">Maker Lists</a>
            </div>

            <div class="itemCard">
            <?php
              // makerの投稿を取得
              $args = array(
                  'post_type' => 'maker',
                  'posts_per_page' => 10,
                  'orderby' => 'title',
                  'order' => 'ASC',
              );
              $makers = new WP_Query($args);
              if ($makers->have_posts()) :
                while ($makers->have_posts()) : $makers->the_post();
                // 地域を取得
                $regions = get_the_terms(get_the_ID(), 'region');
            ?>
              <div class="box">
                <a href="<?php the_permalink(); ?>">
                  <div class="img-box obj-fit">
                    <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail(); ?>
                    <?php else : ?>
                      <img src="<?php echo get_template_directory_uri(); ?>/images/noimage01.png" alt="">
                    <?php endif; ?>
                  </div>
  
                  <div class="cate">
                    <?php get_template_part('inc/snipets-cate'); ?>
                  </div>
  
                  <div class="name"><?php the_title(); ?></div>
                  
                  <?php if ($regions && !is_wp_error($regions) && !empty($regions)) : ?>
                  <div class="region">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="region">
                    <span><?php echo esc_html($regions[0]->name); ?></span>
                  </div>
                  <?php endif; ?>
                </a>
                <div class="link-area">
                  <a class="btn bgc-wh" href="<?php the_permalink(); ?>#profile">Company Profile</a>
                  <a class="btn bgc-wh" href="<?php the_permalink(); ?>#export">Export Conditions</a>
                  <a class="btn bgc-wh" href="<?php the_permalink(); ?>#maker-product">Product List</a>
                </div>
              </div>
            <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
          </div>
        </section>
        <?php endif; ?>


        <?php if (is_user_maker()): ?>
        <section class="buyer">
          <div class="inner">
            <h2>Buyer</h2>

            <div class="btn-wrap">
              <a class="btn bgc-bl" href="<?php echo home_url('/buyer'); ?>">Buyer Lists</a>
            </div>

            <div class="itemCard">
            <?php
              // makerの投稿を取得
              $args = array(
                  'post_type' => 'buyer',
                  'posts_per_page' => 10,
                  'orderby' => 'title',
                  'order' => 'ASC',
              );
              $makers = new WP_Query($args);
              if ($makers->have_posts()) :
                while ($makers->have_posts()) : $makers->the_post();
                // 国を取得
                $regions = get_the_terms(get_the_ID(), 'country');
            ?>
              <div class="box">
                <a href=" <?php the_permalink(); ?>">
                  <div class="img-box obj-fit">
                    <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail(); ?>
                    <?php else : ?>
                      <img src="<?php echo get_template_directory_uri(); ?>/images/noimage01.png" alt="">
                    <?php endif; ?>
                  </div>
  
                  <div class="cate">
                    <?php get_template_part('inc/snipets-cate'); ?>
                  </div>
  
                  <div class="name"><?php the_title(); ?></div>
                  
                  <?php if ($regions && !is_wp_error($regions) && !empty($regions)) : ?>
                  <div class="region">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="region">
                    <span><?php echo esc_html($regions[0]->name); ?></span>
                  </div>
                  <?php endif; ?>
                </a>
                <div class="link-area">
                  <a class="btn bgc-wh" href="<?php the_permalink(); ?>#profile">Company Profile</a>
                  <a class="btn bgc-wh" href="<?php the_permalink(); ?>#wanted">Wanted Products</a>
                </div>
              </div>
            <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
          </div>
        </section>
        <?php endif; ?>


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






