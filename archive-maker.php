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

            <div>
              <?php
                // makerの投稿を取得
                $args = array(
                    'post_type' => 'maker',
                    'posts_per_page' => 8, // 表示数
                    'orderby' => 'title',
                    'order' => 'ASC',
                );
                $makers = new WP_Query($args);
                if ($makers->have_posts()) :
                  while ($makers->have_posts()) : $makers->the_post();
              ?>

              <?php get_template_part('inc/maker-card'); ?>

              <?php endwhile; wp_reset_postdata(); endif; ?>

            </div>
          </div>

        </div><!-- /.inner -->

      </div>

    </div>

  </main>
<?php get_footer(); ?>