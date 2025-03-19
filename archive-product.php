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
                    <input type="text" name="s" placeholder="What are you looking for ? ex) sweets,okinawa" value="<?php echo esc_attr($search_query); ?>">
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
          // 検索条件に基づいてクエリを構築
          $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
          $args = array(
            'post_type' => 'product',
            'posts_per_page' => 12,
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
            $args['tax_query'] = $tax_query;
          }
          // 検索キーワード
          if (!empty($search_query)) {
            $args['s'] = $search_query;
          }
          $query = new WP_Query($args);
        ?>

        <div id="product-list" class="products_wrap">
          <?php if ($query->have_posts()) : ?>
            <ul class="flex-column05">

              <?php while ($query->have_posts()) : $query->the_post(); 
                // 各投稿のカテゴリー情報を取得
                $product_cats = get_the_terms(get_the_ID(), 'product-cat');
                $parent_cat = '';
                $child_cat = '';
                if (!empty($product_cats) && !is_wp_error($product_cats)) {
                  foreach ($product_cats as $cat) {
                    if ($cat->parent == 0) {
                      $parent_cat = $cat->name;
                    } else {
                      $child_cat = $cat->name;
                    }
                  }
                }
                // リージョン情報を取得
                $regions = get_the_terms(get_the_ID(), 'region');
                $region_name = '';
                if (!empty($regions) && !is_wp_error($regions)) {
                  $region_name = $regions[0]->name;
                }
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

                <li class="itemCard">
                  <a href="<?php the_permalink(); ?>">
                    <div class="img-box obj-fit">
                      <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail(); ?>
                      <?php else : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/noimage.jpg" alt="<?php the_title(); ?>">
                      <?php endif; ?>
                    </div>
                    <div class="cate">
                      <span class="parent"><?php echo esc_html($parent_cat); ?></span>
                      <span class="child"><?php echo esc_html($child_cat); ?></span>
                    </div>
                    <div class="name"><?php the_title(); ?></div>
                    <div class="maker"><?php echo esc_html($maker_name); ?></div>
                    <div class="region">
                      <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="">
                      <span><?php echo esc_html($region_name); ?></span>
                    </div>
                  </a>
                </li>

              <?php endwhile; ?>
            </ul>
            
            <?php
            // ページネーション
            $big = 999999999;
            echo '<div class="pagination">';
            echo paginate_links(array(
              'base' => str_replace($big, '%#%', esc_url(get_pagenum_var('product', $big))),
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