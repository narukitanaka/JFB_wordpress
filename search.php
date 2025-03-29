<?php get_header(); ?>
<main>

  <div class="contents-wrap">

    <?php get_sidebar(); ?>

    <div class="contents" id="search">
      <div class="inner">

        <div class="breadcrumbs">
          <nav>
            <ul>
              <li><a href="<?php echo home_url('/'); ?>">HOME</a></li>
              <li>Search Result</li>
            </ul>
          </nav>
        </div>

        <h2>Search Result</h2>


        <?php
        $search_query = get_search_query();
        // 検索対象のカスタム投稿タイプ
        $post_types = array('product', 'maker', 'buyer');
        $has_results = false; // 検索結果があるかどうかのフラグ

        // 各カスタム投稿タイプごとに検索
        foreach ($post_types as $post_type) :
          // カスタム投稿タイプごとの検索クエリ
          $args = array(
            'post_type' => $post_type,
            's' => $search_query,
            'posts_per_page' => 10, // 各タイプごとに10件表示
          );
          $type_query = new WP_Query($args);
          
          // 検索結果がある場合のみブロックを表示
          if ($type_query->have_posts()) :
          $has_results = true; // 検索結果ありとフラグを立てる
          // 投稿タイプ名を取得して表示名を設定
          $post_type_obj = get_post_type_object($post_type);
          $type_label = $post_type_obj->labels->name;
        ?>

        <!-- <?php echo $post_type; ?>のブロック -->
        <section class="search-block search-<?php echo $post_type; ?>">
          <h2 class="search-block-title"><?php echo esc_html($type_label); ?></h2>
          
          <div class="search-items <?php echo ($post_type === 'product') ? 'flex-column05' : ''; ?>">
            <?php while ($type_query->have_posts()) : $type_query->the_post(); ?>
              <?php
              // カスタム投稿タイプによって表示方法を変える
              if ($post_type === 'product') {
              ?>
              
              <?php get_template_part('inc/product-card'); ?>

              <?php
              } elseif ($post_type === 'maker') {
              ?>

              <?php get_template_part('inc/maker-card'); ?>

              <?php
              } else {
              ?>

                <?php get_template_part('inc/buyer-card'); ?>

              <?php
              }
              ?>
            <?php endwhile; ?>
          </div>
          
          <?php if ($type_query->found_posts > $args['posts_per_page']) : ?>
            <div class="view-more">
              <a href="<?php echo esc_url(add_query_arg(array('s' => $search_query, 'post_type' => $post_type), home_url('/'))); ?>" class="view-more-button">
                もっと見る
              </a>
            </div>
          <?php endif; ?>
          
          <?php wp_reset_postdata(); ?>
        </section>

        <?php 
          endif; // 検索結果の有無チェック終了
        endforeach; 

        // すべての投稿タイプで検索結果がない場合のメッセージを表示
        if (!$has_results) : 
        ?>
          <div class="no-results-all">
            <p>No content matching the search term"<?php echo esc_html($search_query); ?>"was found.</p>
          </div>
        <?php endif; ?>


      </div><!-- /.inner -->

    </div>

  </div>

</main>

<?php get_footer(); ?>