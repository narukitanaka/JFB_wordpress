<?php get_header(); ?>
  <main>

    <div class="contents-wrap">

      <?php get_sidebar(); ?>

      <div class="contents" id="archiveProducts">
        <div class="inner">

          <div class="breadcrumbs">
            <nav>
              <ul>
                <li><a href="<?php echo home_url('/'); ?>">HOME</a></li>
                <li>Buyer Lists</li>
              </ul>
            </nav>
          </div>

          <h2>Buyer Lists</h2>

          
          <?php
          // 現在選択されているカテゴリーとカントリーを取得
          $selected_categories = isset($_GET['category']) ? (array) $_GET['category'] : array();
          $selected_countries = isset($_GET['country']) ? (array) $_GET['country'] : array();
          $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
          ?>

          <div class="filter_wrap">
            <form action="<?php echo esc_url(get_post_type_archive_link('buyer')); ?>" method="get">
              <div>

                <fieldset>
                  <div class="sp_accordion-trigger">
                    <div class="ttl">
                      <h3>Categories</h3>
                      <button class="clear" type="button" id="clear-filters">Clear all filters</button>
                    </div>
                  </div>

                  <div class="sp_accordion-hide">
                    <div class="categories flex-column05">
                      <?php
                      // 親カテゴリーを取得
                      $parent_categories = get_terms(array(
                        'taxonomy' => 'product-cat',
                        'parent' => 0,
                        'hide_empty' => false,
                      ));

                      // ACFの順序フィールドでカテゴリーをソート
                      if (!empty($parent_categories) && !is_wp_error($parent_categories)) {
                        // カテゴリーと順序値の配列を作成
                        $sortable_categories = array();
                        foreach ($parent_categories as $cat) {
                          // カスタムフィールドから順序を取得（存在しない場合は999などの大きな値を設定）
                          $order = get_field('category_order', 'product-cat_' . $cat->term_id);
                          $order = $order !== null && $order !== '' ? intval($order) : 999;
                          
                          // カテゴリーオブジェクトと順序を配列に格納
                          $sortable_categories[] = array(
                            'category' => $cat,
                            'order' => $order
                          );
                        }
                        
                        // 順序値でソート
                        usort($sortable_categories, function($a, $b) {
                          return $a['order'] - $b['order'];
                        });
                        
                        // ソートされたカテゴリーを処理
                        foreach ($sortable_categories as $sorted_cat) {
                          $parent_cat = $sorted_cat['category'];
                          
                          // 子カテゴリーを取得
                          $child_categories = get_terms(array(
                            'taxonomy' => 'product-cat',
                            'parent' => $parent_cat->term_id,
                            'hide_empty' => false,
                          ));
                        ?>
                          <div class="category-group">
                            <label>
                              <input type="checkbox" name="category[]" value="<?php echo $parent_cat->slug; ?>" <?php checked(in_array($parent_cat->slug, $selected_categories)); ?>> <?php echo $parent_cat->name; ?>
                            </label>
                            <?php if (!empty($child_categories) && !is_wp_error($child_categories)) : ?>
                              <ul>
                                <?php foreach ($child_categories as $child_cat) : ?>
                                  <li>
                                    <label>
                                      <input type="checkbox" name="category[]" value="<?php echo $child_cat->slug; ?>" <?php checked(in_array($child_cat->slug, $selected_categories)); ?>> <?php echo $child_cat->name; ?>
                                    </label>
                                  </li>
                                <?php endforeach; ?>
                              </ul>
                            <?php endif; ?>
                          </div>
                      <?php }} ?>
                    </div>
                  </div>
                </fieldset>

                <fieldset>
                  <div class="sp_accordion-trigger">
                    <div class="ttl">
                      <h3>Country</h3>
                      <button class="clear" type="button" id="clear-countries">Clear all filters</button>
                    </div>
                  </div>

                  <div class="sp_accordion-hide">
                    <div class="category-group area">
                      <ul class="flex-column05">
                        <?php
                        // カントリーを取得
                        $countries = get_terms(array(
                          'taxonomy' => 'country',
                          'hide_empty' => false,
                        ));

                        if (!empty($countries) && !is_wp_error($countries)) {
                          foreach ($countries as $country) {
                        ?>
                            <li>
                              <label>
                                <input type="checkbox" name="country[]" value="<?php echo $country->slug; ?>" <?php checked(in_array($country->slug, $selected_countries)); ?>> <?php echo $country->name; ?>
                              </label>
                            </li>
                        <?php }} ?>
                      </ul>
                    </div>
                  </div>
                </fieldset>

                <fieldset>
                  <div class="search-form">
                    <div class="search-input-wrapper">
                      <input type="text" name="keyword" placeholder="What are you looking for?   ex) sweets, okinawa" value="<?php echo esc_attr($search_query); ?>">
                      <button type="submit" class="search-icon-button">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icon-search.svg" alt="">
                      </button>
                    </div>
                  </div>
                </fieldset>

              </div>
              <button class="btn bgc-re" type="submit">Search</button>
            </form>
          </div><!-- /.filter_wrap -->


          <?php
          // GET パラメータから検索条件を取得
          $search_query = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
          $selected_categories = isset($_GET['category']) ? (array)$_GET['category'] : array();
          $selected_countries = isset($_GET['country']) ? (array)$_GET['country'] : array();

          // 検索条件に基づいてクエリを構築
          $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
          $args = array(
            'post_type' => 'buyer',
            'posts_per_page' => 2,
            'paged' => $paged,
          );

          // タクソノミークエリの配列を準備
          $tax_query = array();

          // カテゴリーフィルター
          if (!empty($selected_categories)) {
            $tax_query[] = array(
              'taxonomy' => 'product-cat',
              'field' => 'slug',
              'terms' => $selected_categories,
              'operator' => 'IN',
            );
          }

          // カントリーフィルター
          if (!empty($selected_countries)) {
            $tax_query[] = array(
              'taxonomy' => 'country',
              'field' => 'slug',
              'terms' => $selected_countries,
              'operator' => 'IN',
            );
          }

          // タクソノミークエリを追加
          if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
          }

          // キーワード検索
          if (!empty($search_query)) {
            // カスタム検索を実装
            add_filter('posts_where', function($where) use ($search_query) {
              global $wpdb;
              $search_term = '%' . $wpdb->esc_like($search_query) . '%';
              $where .= $wpdb->prepare(
                " AND (
                  {$wpdb->posts}.post_title LIKE %s 
                  OR {$wpdb->posts}.post_content LIKE %s
                )",
                $search_term,
                $search_term
              );
              return $where;
            });
          }

          $query = new WP_Query($args);
          ?>


          <div id="buyer-list" class="buyer-list_wrap">

            <?php if ($query->have_posts()) : ?>
            <div>
              <?php while ($query->have_posts()) : $query->the_post(); ?>

                <?php get_template_part('inc/buyer-card', null, ['countries' => $countries]); ?>

              <?php endwhile; ?>
            </div>

            <?php
            // ページネーション
            $big = 999999999;
            echo '<div class="pagination">';
            echo paginate_links(array(
              'base' => str_replace($big, '%#%', esc_url(get_pagenum_var('buyer', $big))),
              'format' => '?paged=%#%',
              'current' => max(1, get_query_var('paged')),
              'total' => $query->max_num_pages,
              'prev_text' => '&laquo;',
              'next_text' => '&raquo;',
            ));
            echo '</div>';
            ?>

            <?php else : ?>
              <div class="no-results">
                <p class="no-matching">No products found matching your criteria.</p>
              </div>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

          </div>

        </div><!-- /.inner -->

      </div>

    </div>

  </main>

  <?php
  // カスタム関数：ページネーションURLの生成
  function get_pagenum_var($post_type, $big) {
    global $wp_rewrite;
    if (isset($_GET['category']) || isset($_GET['country']) || isset($_GET['s'])) {
      $base = add_query_arg(array(
        'paged' => $big,
        'category' => isset($_GET['category']) ? (array) $_GET['category'] : '',
        'country' => isset($_GET['country']) ? (array) $_GET['country'] : '',
        's' => isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '',
      ), get_post_type_archive_link($post_type));
    } else {
      $base = get_pagenum_link($big);
    }
    return $base;
  }
  ?>

<?php get_footer(); ?>