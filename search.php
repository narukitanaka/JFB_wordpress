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
              <li>Search Rresult</li>
            </ul>
          </nav>
        </div>

        <h2>Search Rresult</h2>

        <?php
        $search_query = get_search_query();
        
        // 検索対象のカスタム投稿タイプ
        $post_types = array('product', 'maker', 'buyer');
        
        // 各カスタム投稿タイプごとに検索
        foreach ($post_types as $post_type) :
          // カスタム投稿タイプごとの検索クエリ
          $args = array(
            'post_type' => $post_type,
            's' => $search_query,
            'posts_per_page' => 6, // 各タイプごとに6件表示
          );
          
          $type_query = new WP_Query($args);
          
          // 投稿タイプ名を取得して表示名を設定
          $post_type_obj = get_post_type_object($post_type);
          $type_label = $post_type_obj->labels->name;
        ?>


        <!-- <?php echo $post_type; ?>のブロック -->
        <section class="search-block search-<?php echo $post_type; ?>">
          <h2 class="search-block-title"><?php echo esc_html($type_label); ?></h2>
          
          <?php if ($type_query->have_posts()) : ?>
            <div class="search-items">
              <?php while ($type_query->have_posts()) : $type_query->the_post(); ?>
                <?php
                // カスタム投稿タイプによって表示方法を変える
                if ($post_type === 'product') {
                  // 地域を取得
                  $regions = get_the_terms(get_the_ID(), 'region');
                  // メーカー関連投稿を取得
                  $maker_post = get_field('item_maker');
                  // メーカー名を取得
                  $maker_name = '';
                  if ($maker_post) {
                    if (is_array($maker_post) && isset($maker_post[0])) {
                      $first_maker = $maker_post[0];
                      $maker_name = is_object($first_maker) ? $first_maker->post_title : 
                      (isset($first_maker['post_title']) ? $first_maker['post_title'] : 
                      get_the_title($first_maker));
                    }
                  }
                  
                  // product用のカードテンプレートを表示
                ?>
                  <div class="product-item">
                    <?php get_template_part('inc/product-card', null, [
                      'regions' => $regions,
                      'maker_name' => $maker_name
                    ]); ?>
                  </div>
                <?php
                } elseif ($post_type === 'maker') {
                  // maker用の表示
                ?>
                  <div class="maker-item">
                    <a href="<?php the_permalink(); ?>">
                      <div class="item-img">
                        <?php if (has_post_thumbnail()) : ?>
                          <?php the_post_thumbnail('thumbnail'); ?>
                        <?php else : ?>
                          <img src="<?php echo get_template_directory_uri(); ?>/images/noimage.jpg" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                      </div>
                      <div class="item-content">
                        <h3 class="item-title"><?php the_title(); ?></h3>
                        <div class="item-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></div>
                      </div>
                    </a>
                  </div>
                <?php
                } else {
                  // buyer用の表示
                ?>
                  <div class="buyer-item">
                    <a href="<?php the_permalink(); ?>">
                      <div class="item-img">
                        <?php if (has_post_thumbnail()) : ?>
                          <?php the_post_thumbnail('thumbnail'); ?>
                        <?php else : ?>
                          <img src="<?php echo get_template_directory_uri(); ?>/images/noimage.jpg" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                      </div>
                      <div class="item-content">
                        <h3 class="item-title"><?php the_title(); ?></h3>
                        <div class="item-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></div>
                      </div>
                    </a>
                  </div>
                <?php
                }
                ?>
              <?php endwhile; ?>
            </div>
            
            <?php if ($type_query->found_posts > 6) : ?>
              <div class="view-more">
                <a href="<?php echo esc_url(add_query_arg(array('s' => $search_query, 'post_type' => $post_type), home_url('/'))); ?>" class="view-more-button">
                  もっと見る（<?php echo $type_query->found_posts; ?>件）
                </a>
              </div>
            <?php endif; ?>
            
          <?php else : ?>
            <p class="no-results">「<?php echo esc_html($search_query); ?>」に一致する<?php echo esc_html($type_label); ?>はありませんでした。</p>
          <?php endif; ?>
          
          <?php wp_reset_postdata(); ?>
        </section>
        
        <?php endforeach; ?>
        
        <?php 
        // すべての投稿タイプで検索結果がない場合
        $has_results = false;
        foreach ($post_types as $type) {
          $temp_query = new WP_Query(array(
            'post_type' => $type,
            's' => $search_query,
            'posts_per_page' => 1
          ));
          if ($temp_query->have_posts()) {
            $has_results = true;
            break;
          }
        }
        
        if (!$has_results) : 
        ?>
          <div class="no-results-all">
            <p>検索キーワード「<?php echo esc_html($search_query); ?>」に一致するコンテンツが見つかりませんでした。</p>
            <ul class="search-suggestions">
              <li>別のキーワードを試してみてください</li>
              <li>キーワードに誤字・脱字がないか確認してください</li>
              <li>より一般的なキーワードを使用してみてください</li>
            </ul>
          </div>
        <?php endif; ?>


      </div><!-- /.inner -->

    </div>

  </div>

</main>

<?php get_footer(); ?>