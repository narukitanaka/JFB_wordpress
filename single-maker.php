<?php get_header(); ?>
  <main>

    <div class="single-head-content">
      <div class="inner">

        <div class="breadcrumbs">
          <nav>
            <ul>
              <li><a href="<?php echo home_url('/'); ?>">HOME</a></li>
              <li><a href="<?php echo home_url('/maker'); ?>">Maker Lists</a></li>
              <li><?php the_title(); ?></li>
            </ul>
          </nav>
        </div>

        <div class="head-content">
          <div class="company">
            <?php 
            $maker_logo = get_field('maker_logo');
            if ($maker_logo) : ?>
            <div class="logo img-box obj-fit">
              <img src="<?php echo esc_url($maker_logo); ?>" alt="">
            </div>
            <?php endif; ?>

            <div class="ttl">
              <div class="cate">
                <?php 
                $categories = get_the_terms(get_the_ID(), 'product-cat');
                if ($categories && !is_wp_error($categories)) {
                  // 親カテゴリーと子カテゴリーを分ける
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
                    $color = get_field('cate_color', 'product-cat_' . $parent->term_id); // カテゴリの色を取得
                    echo '<span class="parent" style="background-color: ' . esc_attr($color) . '; border-color: ' . esc_attr($color) . ';">' . esc_html($parent->name) . '</span>';
                  }
                  // 子カテゴリーを表示
                  foreach ($child_cats as $child) {
                    $parent_id = $child->parent; // 親カテゴリーのIDを取得
                    $color = get_field('cate_color', 'product-cat_' . $parent_id); // 親カテゴリーの色を取得
                    echo '<span class="child" style="border-color: ' . esc_attr($color) . '; color: ' . esc_attr($color) . ';">' . esc_html($child->name) . '</span>';
                  }
                }
                ?>
              </div>
              <div class="name"><?php the_title(); ?></div>
              <div class="region">
                <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="">
                <span>
                  <?php 
                  $regions = get_the_terms(get_the_ID(), 'region');
                  if (!empty($regions) && !is_wp_error($regions)) {
                    echo esc_html($regions[0]->name);
                  }
                  ?>
              </span>
              </div>
            </div>
          </div>

          <div class="btn-area">
            <div class="btn-wrap"><a class="btn bgc-re" href="#sendmail">Contact Maker</a></div>
            <?php 
            // PDFファイルのURL取得
            $pdf_company_info = get_field('pdf_company-info');
            ?>
            <div class="btn-wrap">
              <a class="btn bgc-bl" href="<?php echo esc_url($pdf_company_info); ?>" download>
                Company Info
                <svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.30034 16.515C9.09334 16.515 8.95384 16.5352 8.88184 16.5555V17.8807C8.96734 17.901 9.07421 17.9066 9.22159 17.9066C9.76046 17.9066 10.0923 17.6344 10.0923 17.1742C10.0923 16.7625 9.80659 16.515 9.30034 16.515ZM13.2232 16.5285C12.9982 16.5285 12.852 16.5487 12.7653 16.569V19.5052C12.852 19.5255 12.9915 19.5255 13.1175 19.5255C14.0366 19.5323 14.6351 19.026 14.6351 17.955C14.6418 17.0212 14.0962 16.5285 13.2232 16.5285Z" fill="white"/>
                <path d="M15.75 2.25H6.75C6.15326 2.25 5.58097 2.48705 5.15901 2.90901C4.73705 3.33097 4.5 3.90326 4.5 4.5V22.5C4.5 23.0967 4.73705 23.669 5.15901 24.091C5.58097 24.5129 6.15326 24.75 6.75 24.75H20.25C20.8467 24.75 21.419 24.5129 21.841 24.091C22.2629 23.669 22.5 23.0967 22.5 22.5V9L15.75 2.25ZM10.6853 18.2138C10.3376 18.54 9.82462 18.6863 9.22725 18.6863C9.1114 18.6884 8.99556 18.6817 8.88075 18.666V20.2702H7.875V15.8422C8.32891 15.7747 8.78751 15.7439 9.24637 15.75C9.873 15.75 10.3185 15.8693 10.6189 16.1089C10.9046 16.3361 11.0981 16.7085 11.0981 17.1472C11.097 17.5882 10.9508 17.9606 10.6853 18.2138ZM14.9681 19.7381C14.4956 20.1308 13.7768 20.3175 12.8981 20.3175C12.3716 20.3175 11.9992 20.2837 11.7461 20.25V15.8434C12.2002 15.7773 12.6587 15.7461 13.1175 15.75C13.9691 15.75 14.5226 15.903 14.9546 16.2292C15.4215 16.5757 15.714 17.1281 15.714 17.9213C15.714 18.7796 15.4001 19.3725 14.9681 19.7381ZM19.125 16.6163H17.4015V17.6411H19.0125V18.4669H17.4015V20.2714H16.3823V15.7837H19.125V16.6163ZM15.75 10.125H14.625V4.5L20.25 10.125H15.75Z" fill="white"/>
                </svg>
              </a>
            </div>
          </div>

        </div>

      </div>
    </div>

    <div class="contents-wrap content_maker-buyer">

      <?php get_sidebar(); ?>

      <div class="contents" id="singleMaker">
        <div class="inner">
          <div class="maker_buyer-content">

            <div class="img-wrap cplumn-04 flex-column04">
              <?php if (has_post_thumbnail()) : ?>
                <div class="img-box obj-fit">
                  <?php the_post_thumbnail(); ?>
                </div>
              <?php endif; ?>

              <?php 
              $images = get_field('maker-img_repaet'); // 繰り返しフィールドの値を取得
              // 繰り返しフィールドに値がある場合
              if($images): 
                foreach($images as $image): 
                  // 画像フィールドに値がある場合のみ表示
                  if(!empty($image['maker-img'])): ?>
                    <div class="img-box obj-fit">
                      <img src="<?php echo esc_url($image['maker-img']); ?>" alt="">
                    </div>
                  <?php endif; 
                endforeach;
              endif; 
              ?>
            </div>

            <div class="information">
              <h3>Basic Information</h3>
              <div>
                <?php the_content(); ?>
              </div>
            </div>

            <div class="box profile">
              <div id="profile">
                <h2>Company Profile</h2>
                <table>
                  <?php 
                    $company_profile = get_field('group_company-profile'); // グループフィールドを取得
                  ?>
                  <?php if ($company_profile && !empty($company_profile['company-name'])) : ?>
                    <tr>
                      <th>Company Name</th>
                      <td><?php echo esc_html($company_profile['company-name']); ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if ($company_profile && !empty($company_profile['representative'])) : ?>
                    <tr>
                      <th>Representative</th>
                      <td><?php echo esc_html($company_profile['representative']); ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if ($company_profile && !empty($company_profile['company-location'])) : ?>
                    <tr>
                      <th>Comapny Location</th>
                      <td><?php echo esc_html($company_profile['company-location']); ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if ($company_profile && !empty($company_profile['main-products'])) : ?>
                    <tr>
                      <th>Main Products</th>
                      <td><?php echo esc_html($company_profile['main-products']); ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if ($company_profile && !empty($company_profile['web-site'])) : ?>
                    <tr>
                      <th>web site</th>
                      <td><a target="_blank" href="<?php echo esc_url($company_profile['web-site']); ?>"><?php echo esc_html($company_profile['web-site']); ?></a></td>
                    </tr>
                  <?php endif; ?>
                </table>
              </div>
            </div>

            <div class="box expot">
              <div id="export">
                <h2>Export Conditions</h2>
                <table>
                  <?php 
                    $company_profile = get_field('group_export-conditions'); // グループフィールドを取得
                  ?>
                  <?php if ($company_profile && !empty($company_profile['available-areas'])) : ?>
                    <tr>
                      <th>Available Areas</th>
                      <td><?php echo esc_html($company_profile['available-areas']); ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if ($company_profile && !empty($company_profile['departure'])) : ?>
                    <tr>
                      <th>Lead time to departure</th>
                      <td><?php echo esc_html($company_profile['departure']); ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if ($company_profile && !empty($company_profile['moq'])) : ?>
                    <tr>
                      <th>MOQ</th>
                      <td><?php echo esc_html($company_profile['moq']); ?></td>
                    </tr>
                  <?php endif; ?>
                </table>
              </div>
            </div>

            <div class="box product">
              <div id="maker-product">
                <h2>Product List</h2>

                <?php
                $current_maker_id = get_the_ID(); // 現在表示中のmakerのIDを取得

                $args = array(
                  'post_type' => 'product',
                  'posts_per_page' => -1,
                  'meta_query' => array(
                    'relation' => 'OR',
                    array(
                      'key' => 'item_maker',
                      'value' => '"' . $current_maker_id . '"',
                      'compare' => 'LIKE'
                    )
                  )
                );
                $related_products = new WP_Query($args);


                if ($related_products->have_posts()) : ?>
                  <ul class="flex-column04">
                    <?php while ($related_products->have_posts()) : $related_products->the_post(); 
                      // サムネイル画像を取得
                      $thumbnail = get_the_post_thumbnail_url(get_the_ID(), ''); 
                      // リージョン情報を取得
                      $regions = get_the_terms(get_the_ID(), 'region');
                      // メーカー名を取得
                      $maker_name = get_the_title($current_maker_id);
                    ?>
                      <li class="itemCard">
                        <a href="<?php the_permalink(); ?>">
                          <div class="img-box">
                            <img src="<?php echo esc_url($thumbnail); ?>" alt="">
                          </div>
                          <div class="cate">
                            <?php 
                            $categories = get_the_terms(get_the_ID(), 'product-cat');
                            if ($categories && !is_wp_error($categories)) {
                              // 親カテゴリーと子カテゴリーを分ける
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
                                $color = get_field('cate_color', 'product-cat_' . $parent->term_id); // カテゴリの色を取得
                                echo '<span class="parent" style="background-color: ' . esc_attr($color) . '; border-color: ' . esc_attr($color) . ';">' . esc_html($parent->name) . '</span>';
                              }
                              // 子カテゴリーを表示
                              foreach ($child_cats as $child) {
                                $parent_id = $child->parent; // 親カテゴリーのIDを取得
                                $color = get_field('cate_color', 'product-cat_' . $parent_id); // 親カテゴリーの色を取得
                                echo '<span class="child" style="border-color: ' . esc_attr($color) . '; color: ' . esc_attr($color) . ';">' . esc_html($child->name) . '</span>';
                              }
                            }
                            ?>
                          </div>
                          <div class="name"><?php the_title(); ?></div>
                          <div class="maker"><?php echo esc_html($maker_name); ?></div>
                          <div class="region">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="">
                            <?php 
                              if ($regions && !is_wp_error($regions) && !empty($regions)) {
                                $region = $regions[0];
                                echo '<span>' . esc_html($region->name) . '</span>';
                              }
                            ?>
                          </div>
                        </a>
                      </li>
                    <?php endwhile; ?>
                  </ul>
                  <?php 
                  // 元のクエリをリセット
                  wp_reset_postdata();
                  ?>
                <?php else : ?>
                  <p class="no-related">No products found for this maker.</p>
                <?php endif; ?>
              </div>
            </div>


            <div class="box send-message">
              <div id="sendmail">
                <h2>Send message to Maker</h2>
                <?php echo do_shortcode('[contact-form-7 id="47f850b" title="メーカーお問い合わせ"]'); ?>
              </div>
            </div>

          </div><!-- /.product-content -->

        </div><!-- /.inner -->

      </div>

    </div>

  </main>
<?php get_footer(); ?>