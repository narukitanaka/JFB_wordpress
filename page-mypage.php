<?php get_header(); ?>
  <main>

    <div class="contents-wrap">

      <?php get_sidebar(); ?>

      <div class="contents" id="pageMypage">
        <div class="inner">

          <h2>My Pege</h2>

          <div class="mypage_wrap">
            <h3>
              <img src="<?php echo get_template_directory_uri(); ?>/images/icon-favo_bla.svg" alt="">
              My Lists
            </h3>

            <!-- buyerに出力される内容 -->
            <?php if (is_user_buyer()): ?>
              <?php
              $favorites = get_user_favorites();
              if (!empty($favorites)) {
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                  'post__in' => $favorites,
                  'post_type' => 'product',
                  'posts_per_page' => 20,
                  'paged' => $paged
                );
                $the_query = new WP_Query($args);
              ?>

              <div class="mylist-block flex-column05">
                <?php if($the_query->have_posts()): ?>
                  <?php while($the_query->have_posts()): $the_query->the_post(); ?>
                    <?php get_template_part('inc/product-card'); ?>
                  <?php endwhile; ?>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
              </div>

              <div class="pagination">
                <?php 
                echo paginate_links(array(
                  'base' => get_pagenum_link(1) . '%_%',
                  'format' => 'page/%#%',
                  'current' => $paged,
                  'total' => $the_query->max_num_pages,
                  'prev_text' => '＜',
                  'next_text' => '＞',
                ));
                ?>
              </div>

              <?php } else { ?>
                <div class="no-favorites-message">
                  <p>There are no items on my list</p>
                </div>
              <?php } ?>
            <?php endif; ?>


            <!-- makerに出力される内容 -->
            <?php if (is_user_maker()): ?>
              <?php
              $favorites = get_user_favorites();
              if (!empty($favorites)) {
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                  'post__in' => $favorites,
                  'post_type' => 'buyer',
                  'posts_per_page' => 8,
                  'paged' => $paged
                );
                $the_query = new WP_Query($args);
              ?>

              <div class="mylist-block">
                <?php if($the_query->have_posts()): ?>
                  <?php while($the_query->have_posts()): $the_query->the_post(); ?>
                    <?php get_template_part('inc/buyer-card'); ?>
                  <?php endwhile; ?>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
              </div>

              <div class="pagination">
                <?php 
                echo paginate_links(array(
                  'base' => get_pagenum_link(1) . '%_%',
                  'format' => 'page/%#%',
                  'current' => $paged,
                  'total' => $the_query->max_num_pages,
                  'prev_text' => '＜',
                  'next_text' => '＞',
                ));
                ?>
              </div>

              <?php } else { ?>
                <div class="no-favorites-message">
                  <p>There are no items on my list</p>
                </div>
              <?php } ?>
            <?php endif; ?>
            
          </div>

        </div><!-- /.inner -->

      </div>

    </div>

  </main>
<?php get_footer(); ?>