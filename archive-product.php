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
              <li>Product Lists</li>
            </ul>
          </nav>
        </div>

        <h2>Product Lists</h2>

        <?php
        // 現在選択されているカテゴリーとリージョンを取得
        $selected_categories = isset($_GET['category']) ? (array) $_GET['category'] : array();
        $selected_regions = isset($_GET['region']) ? (array) $_GET['region'] : array();
        $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        ?>

        <div class="filter_wrap">
          <form action="<?php echo esc_url(get_post_type_archive_link('product')); ?>" method="get">
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
                    <h3>Region</h3>
                    <button class="clear" type="button" id="clear-regions">Clear all filters</button>
                  </div>
                </div>

                <div class="sp_accordion-hide">
                  <div class="category-group area">
                    <ul class="flex-column05">
                      <?php
                      // リージョンを取得
                      $regions = get_terms(array(
                        'taxonomy' => 'region',
                        'hide_empty' => false,
                      ));

                      if (!empty($regions) && !is_wp_error($regions)) {
                        foreach ($regions as $region) {
                      ?>
                          <li>
                            <label>
                              <input type="checkbox" name="region[]" value="<?php echo $region->slug; ?>" <?php checked(in_array($region->slug, $selected_regions)); ?>> <?php echo $region->name; ?>
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
                    <input type="text" name="keyword" placeholder="What are you looking for ?" value="<?php echo esc_attr($search_query); ?>">
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
        // GETパラメータからカスタムページ番号を取得
        $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

        // GET パラメータから検索条件を取得
        $search_query = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
        $selected_categories = isset($_GET['category']) ? (array)$_GET['category'] : array();
        $selected_regions = isset($_GET['region']) ? (array)$_GET['region'] : array();

        // 検索条件に基づいてクエリを構築
        $args = array(
          'post_type' => 'product',
          'posts_per_page' => 20, // ここで表示件数設定
          'paged' => $current_page, // ここで正しいページ番号を設定
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
        // リージョンフィルター
        if (!empty($selected_regions)) {
          $tax_query[] = array(
            'taxonomy' => 'region',
            'field' => 'slug',
            'terms' => $selected_regions,
            'operator' => 'IN',
          );
        }
        // タクソノミークエリを追加
        if (!empty($tax_query)) {
          if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
          }
          $args['tax_query'] = $tax_query;
        }
        // キーワード検索
        if (!empty($search_query)) {
          $args['s'] = $search_query;
        }

        // クエリを実行
        $query = new WP_Query($args);
        ?>

        <div id="product-list" class="products_wrap">
          <div class="flex-column05">
            <?php if ($query->have_posts()) : ?>
              <?php while ($query->have_posts()) : $query->the_post(); ?>
                <!-- ID確認用 -->
                <!-- Post ID: <?php echo get_the_ID(); ?> -->
                <?php get_template_part('inc/product-card'); ?>
              <?php endwhile; ?>
            </div>
            
            <div class="pagination">
              <?php
                $total_pages = $query->max_num_pages;

                if ($total_pages > 1) {
                  echo '<div class="nav-links">';
                  // ベースURLとパラメータの準備
                  $base_url = get_post_type_archive_link('product');
                  $url_params = array();
                  if (!empty($search_query)) {
                    $url_params['keyword'] = $search_query;
                  }
                  if (!empty($selected_categories)) {
                    foreach ($selected_categories as $cat) {
                      $url_params['category'][] = $cat;
                    }
                  }
                  if (!empty($selected_regions)) {
                    foreach ($selected_regions as $region) {
                      $url_params['region'][] = $region;
                    }
                  }

                  // 前のページへのリンク
                  if ($current_page > 1) {
                    $prev_params = $url_params;
                    $prev_params['page'] = $current_page - 1;
                    echo '<a class="prev page-numbers" href="' . esc_url(add_query_arg($prev_params, $base_url)) . '#product-list">＜</a>';
                  }
                  // ページ番号リンク
                  for ($i = 1; $i <= $total_pages; $i++) {
                    $page_params = $url_params;
                    if ($i > 1) { // 1ページ目はパラメータ不要
                      $page_params['page'] = $i;
                    } else {
                      unset($page_params['page']);
                    }
                    if ($i == $current_page) {
                      echo '<span aria-current="page" class="page-numbers current">' . $i . '</span>';
                    } else {
                      echo '<a class="page-numbers" href="' . esc_url(add_query_arg($page_params, $base_url)) . '#product-list">' . $i . '</a>';
                    }
                  }
                  // 次のページへのリンク
                  if ($current_page < $total_pages) {
                    $next_params = $url_params;
                    $next_params['page'] = $current_page + 1;
                    echo '<a class="next page-numbers" href="' . esc_url(add_query_arg($next_params, $base_url)) . '#product-list">＞</a>';
                  }
                  echo '</div>';
                }
              ?>
            </div>
            
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
  if (isset($_GET['category']) || isset($_GET['region']) || isset($_GET['s'])) {
    $base = add_query_arg(array(
      'paged' => $big,
      'category' => isset($_GET['category']) ? (array) $_GET['category'] : '',
      'region' => isset($_GET['region']) ? (array) $_GET['region'] : '',
      's' => isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '',
    ), get_post_type_archive_link($post_type));
  } else {
    $base = get_pagenum_link($big);
  }
  return $base;
}
?>

<?php get_footer(); ?>