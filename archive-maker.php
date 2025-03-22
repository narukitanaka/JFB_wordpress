<?php get_header(); ?>
  <main>

    <div class="contents-wrap">

      <?php get_sidebar(); ?>

      <div class="contents" id="archiveMaker">
        <div class="inner">

          <div class="breadcrumbs">
            <nav>
              <ul>
                <li><a href="<?php echo home_url('/'); ?>">HOME</a></li>
                <li>Maker Lists</li>
              </ul>
            </nav>
          </div>

          <h2>Maker Lists</h2>

          <div class="maker-list_wrap">

            <ul>
              <?php
                // makerの投稿を取得
                $args = array(
                    'post_type' => 'maker',
                    'posts_per_page' => 5, // 表示数
                    'orderby' => 'title',
                    'order' => 'ASC',
                );
                $makers = new WP_Query($args);
                if ($makers->have_posts()) :
                  while ($makers->have_posts()) : $makers->the_post();
                  // アイキャッチ画像の取得
                  $thumbnail = get_the_post_thumbnail_url(get_the_ID(), '');
                  // product-catカテゴリーを取得
                  $categories = get_the_terms(get_the_ID(), 'product-cat');
                  // 地域を取得
                  $regions = get_the_terms(get_the_ID(), 'region');
              ?>

                <li class="companyCard01">
                  <div>
                    <div class="left">
                      <?php if ($thumbnail) : ?>
                        <div class="img-box obj-fit"><img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>"></div>
                      <?php endif; ?>
                    </div>
                    <div class="right">
                      <div class="info">
                        <a href="<?php the_permalink(); ?>">
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
                          <div class="name">
                            <?php if (get_field('maker_logo')) : ?>
                              <?php $maker_logo = get_field('maker_logo'); ?>
                              <span class="logo img-box"><img src="<?php echo esc_url($maker_logo); ?>" alt=""></span>
                            <?php endif; ?>
                            <?php the_title(); ?>
                          </div>
                          <div class="txt">
                            <?php the_content(); ?>
                          </div>
                          <?php if ($regions && !is_wp_error($regions) && !empty($regions)) : ?>
                            <div class="region">
                              <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="region">
                              <span><?php echo esc_html($regions[0]->name); ?></span>
                            </div>
                          <?php endif; ?>
                        </a>
                      </div>
                      <div class="links">
                        <div class="btn-wrap"><a class="btn bgc-wh" href="<?php the_permalink(); ?>#profile">Company Profile</a></div>
                        <div class="btn-wrap"><a class="btn bgc-wh" href="<?php the_permalink(); ?>#export">Export Conditions</a></div>
                        <div class="btn-wrap"><a class="btn bgc-wh" href="<?php the_permalink(); ?>#product">Product List</a></div>
                      </div>
                    </div>
                  </div>
                </li>

              <?php endwhile; wp_reset_postdata(); endif; ?>

            </ul>
          </div>

        </div><!-- /.inner -->

      </div>

    </div>

  </main>
<?php get_footer(); ?>