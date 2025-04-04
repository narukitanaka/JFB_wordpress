<?php get_header(); ?>
  <main>

    <div class="contents-wrap">

      <?php get_sidebar(); ?>

      <div class="contents" id="pageMypage">
        <div class="inner">

          <h2>My pege</h2>

          <div class="mypage_wrap">
            <h3>
              <img src="<?php echo get_template_directory_uri(); ?>/images/icon-favo_bla.svg" alt="">
              My Lists
            </h3>

            <?php
            $favorites = get_user_favorites();
            if (!empty($favorites)) {
              $args = array(
                'post__in' => $favorites,
                'post_type' => 'product',
                'posts_per_page' => 100
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

            <?php } else { ?>
              <div class="no-favorites-message">
                <p>There are no items on my list</p>
              </div>
            <?php } ?>
            
          </div>

        </div><!-- /.inner -->

      </div>

    </div>

  </main>
<?php get_footer(); ?>