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
                      <input type="text" name="s" placeholder="What are you looking for?   ex) sweets, okinawa" value="<?php echo esc_attr($search_query); ?>">
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
            'post_type' => 'buyer',
            'posts_per_page' => 8,
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
          // 検索キーワード
          if (!empty($search_query)) {
            $args['s'] = $search_query;
          }
          $query = new WP_Query($args);
        ?>


          <div id="buyer-list" class="buyer-list_wrap">

            <?php if ($query->have_posts()) : ?>
            <ul>
              <?php while ($query->have_posts()) : $query->the_post(); 
                // 各投稿のカテゴリー情報を取得
                $product_cats = get_the_terms(get_the_ID(), 'product-cat');
                
                // カントリー情報を取得
                $countries = get_the_terms(get_the_ID(), 'country');
                $country_name = '';
                if (!empty($countries) && !is_wp_error($countries)) {
                  $country_name = $countries[0]->name;
                }
              ?>

                <li class="companyCard02">
                  <div class="btn-wrap">
                    <a class="btn bgc-re" href="<?php the_permalink(); ?>/#sendmail">
                      Offer
                      <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1_4821)">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.8122 0.187712C17.9004 0.275765 17.961 0.387534 17.9868 0.509424C18.0126 0.631315 18.0024 0.758073 17.9575 0.874284L11.529 17.5886C11.4835 17.7068 11.404 17.809 11.3006 17.8823C11.1972 17.9555 11.0745 17.9966 10.9478 18.0003C10.8211 18.004 10.6962 17.9702 10.5887 17.9031C10.4812 17.836 10.3959 17.7387 10.3435 17.6233L7.6551 11.709L11.61 7.75286C11.7803 7.57006 11.873 7.32828 11.8686 7.07847C11.8642 6.82865 11.763 6.5903 11.5863 6.41363C11.4097 6.23695 11.1713 6.13575 10.9215 6.13134C10.6717 6.12694 10.4299 6.21967 10.2471 6.39L6.29095 10.3449L0.376668 7.65771C0.260911 7.60543 0.163235 7.52003 0.0959682 7.41229C0.028701 7.30455 -0.00514256 7.1793 -0.00129122 7.05234C0.00256012 6.92539 0.0439338 6.80242 0.117608 6.69895C0.191282 6.59549 0.293955 6.51616 0.412668 6.471L17.127 0.0424266C17.243 -0.00222041 17.3695 -0.0122587 17.4911 0.0135279C17.6128 0.0393145 17.7243 0.0998193 17.8122 0.187712Z" fill="white"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_1_4821">
                        <rect width="18" height="18" fill="white"/>
                        </clipPath>
                        </defs>
                      </svg>
                    </a>
                  </div>
                  <a href="<?php the_permalink(); ?>">
                    <div>
                      <?php if (has_post_thumbnail()) : ?>
                        <div class="left">
                          <div class="img-box obj-fit">
                            <?php the_post_thumbnail(); ?>
                          </div>
                        </div>
                      <?php endif; ?>
                      <div class="right">
                        <div class="cate">
                          Wanted Products
                          <div>
                            <?php
                              $parent_cats = array();
                              $child_cats = array();
                              if (!empty($product_cats) && !is_wp_error($product_cats)) {
                                foreach ($product_cats as $cat) {
                                  if ($cat->parent == 0) {
                                    $parent_cats[] = $cat;
                                  } else {
                                    $child_cats[] = $cat;
                                  }
                                }
                                // 親カテゴリーを表示
                                foreach ($parent_cats as $parent) {
                                  $color = get_field('cate_color', 'product-cat_' . $parent->term_id);  // カテゴリの色を取得
                                  echo '<span class="parent" style="background-color: ' . esc_attr($color) . '; border-color: ' . esc_attr($color) . ';">' . esc_html($parent->name) . '</span>';
                                }
                                // 子カテゴリーを表示
                                foreach ($child_cats as $child) {
                                  $parent_id = $child->parent;  // 親カテゴリーのIDを取得
                                  $color = get_field('cate_color', 'product-cat_' . $parent_id);   // 親カテゴリーの色を取得
                                  echo '<span class="child" style="border-color: ' . esc_attr($color) . '; color: ' . esc_attr($color) . ';">' . esc_html($child->name) . '</span>';
                                }
                              }
                            ?>
                          </div>
                        </div>
                        <div class="name"><?php the_title(); ?></div>
                        <div class="txt">
                          <?php the_content(); ?>
                        </div>
                        <div class="region">
                          <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="">
                          <span><?php echo esc_html($country_name); ?></span>
                        </div>
                      </div>
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