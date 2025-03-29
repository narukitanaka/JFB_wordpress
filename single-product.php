<?php get_header(); ?>
  <main>

    <div class="contents-wrap">

    <?php get_sidebar(); ?>

      <div class="contents" id="singleProduct">
        <div class="inner">

          <div class="breadcrumbs">
            <nav>
              <ul>
                <li><a href="<?php echo home_url('/'); ?>">HOME</a></li>
                <?php
                  // 商品に紐づくproduct-catタクソノミーの用語を取得
                  $product_cats = get_the_terms(get_the_ID(), 'product-cat');
                  if (!empty($product_cats) && !is_wp_error($product_cats)) {
                    // 親カテゴリと子カテゴリを分類
                    $parent_cats = array();
                    $child_cats = array();
                    foreach ($product_cats as $cat) {
                      if ($cat->parent == 0) {
                        $parent_cats[] = $cat;
                      } else {
                        $child_cats[] = $cat;
                      }
                    }
                    // 親カテゴリのリンクを表示
                    if (!empty($parent_cats)) {
                      echo '<li>';
                      $parent_links = array();
                      foreach ($parent_cats as $parent) {
                        $archive_link = add_query_arg('category[]', $parent->slug, get_post_type_archive_link('product'));
                        $parent_links[] = '<a href="' . esc_url($archive_link) . '">' . esc_html($parent->name) . '</a>';
                      }
                      echo implode(' / ', $parent_links);
                      echo '</li>';
                    }
                    // 子カテゴリのリンクを表示
                    if (!empty($child_cats)) {
                      echo '<li>';
                      $child_links = array();
                      foreach ($child_cats as $child) {
                        $archive_link = add_query_arg('category[]', $child->slug, get_post_type_archive_link('product'));
                        $child_links[] = '<a href="' . esc_url($archive_link) . '">' . esc_html($child->name) . '</a>';
                      }
                      echo implode(' / ', $child_links);
                      echo '</li>';
                    }
                  }
                ?>
                <li><?php the_title(); ?></li>
              </ul>
            </nav>
          </div>


          <div class="product-content">

            <div class="img-content">
              <div class="img-wrapper">

                <div class="swiper-item single-productSwiper">
                  <div class="swiper-wrapper">

                    <div class="swiper-slide">
                      <div class="img-box obj-fit">
                        <?php if (has_post_thumbnail()) : ?>
                          <?php the_post_thumbnail(); ?>
                        <?php else : ?>
                          <img src="<?php echo get_template_directory_uri(); ?>/images/noimage.jpg" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                      </div>
                    </div>

                    <?php 
                    // 繰り返しフィールドを取得
                    $item_images = get_field('item_img-repeat');
                    if($item_images): ?>
                      <?php foreach($item_images as $image): ?>
                        <div class="swiper-slide">
                          <div class="img-box obj-fit">
                            <?php if($image['item_images']): ?>
                              <img src="<?php echo esc_url($image['item_images']); ?>" alt="">
                            <?php endif; ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php endif; ?>

                  </div><!-- /.swiper-wrapper -->
                </div><!-- /.swiper-item -->

                <div class="thumb-list">
                  <div class="thumb-item">
                    <a class="thumb-link" href="javascript:void(0);" onclick="thumbnail(1)">
                      <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail(); ?>
                      <?php else : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/noimage.jpg" alt="<?php the_title(); ?>">
                      <?php endif; ?>
                    </a>
                  </div>

                  <?php 
                  // サムネイル用に繰り返しフィールドを再度ループ
                  if($item_images): 
                    $index = 2; // サムネイルのインデックス（アイキャッチ画像が1）
                    foreach($item_images as $image): 
                      if($image['item_images']): ?>
                        <div class="thumb-item">
                          <a class="thumb-link" href="javascript:void(0);" onclick="thumbnail(<?php echo $index; ?>)">
                            <img src="<?php echo esc_url($image['item_images']); ?>" alt="">
                          </a>
                        </div>
                      <?php 
                      $index++; // インデックスを増やす
                      endif;
                    endforeach; 
                  endif; ?>

                </div><!-- /.thumb-list -->
              </div><!-- /.img-wrapper -->
            </div><!-- /.img-content -->

            <div class="detail-content">

              <?php if (get_field('item_num')) : ?>
              <p class="number">Item No. <span><?php echo esc_html(get_field('item_num')); ?></span></p>
              <?php endif; ?>
              <p class="name"><?php the_title(); ?></p>

              <div class="download">
                <?php 
                // PDFファイルのURL取得
                $pdf_quotation = get_field('pdf-quotation');
                $pdf_standards_document = get_field('pdf-standards_document');
                $pdf_company_info = get_field('pdf-company_info');
                ?>
                <?php if($pdf_quotation) : ?>
                <div class="btn-wrap">
                  <a class="btn bgc-bl" href="<?php echo esc_url($pdf_quotation); ?>" download>
                    QUOTATION
                    <svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9.30034 16.515C9.09334 16.515 8.95384 16.5352 8.88184 16.5555V17.8807C8.96734 17.901 9.07421 17.9066 9.22159 17.9066C9.76046 17.9066 10.0923 17.6344 10.0923 17.1742C10.0923 16.7625 9.80659 16.515 9.30034 16.515ZM13.2232 16.5285C12.9982 16.5285 12.852 16.5487 12.7653 16.569V19.5052C12.852 19.5255 12.9915 19.5255 13.1175 19.5255C14.0366 19.5323 14.6351 19.026 14.6351 17.955C14.6418 17.0212 14.0962 16.5285 13.2232 16.5285Z" fill="white"/>
                      <path d="M15.75 2.25H6.75C6.15326 2.25 5.58097 2.48705 5.15901 2.90901C4.73705 3.33097 4.5 3.90326 4.5 4.5V22.5C4.5 23.0967 4.73705 23.669 5.15901 24.091C5.58097 24.5129 6.15326 24.75 6.75 24.75H20.25C20.8467 24.75 21.419 24.5129 21.841 24.091C22.2629 23.669 22.5 23.0967 22.5 22.5V9L15.75 2.25ZM10.6853 18.2138C10.3376 18.54 9.82462 18.6863 9.22725 18.6863C9.1114 18.6884 8.99556 18.6817 8.88075 18.666V20.2702H7.875V15.8422C8.32891 15.7747 8.78751 15.7439 9.24637 15.75C9.873 15.75 10.3185 15.8693 10.6189 16.1089C10.9046 16.3361 11.0981 16.7085 11.0981 17.1472C11.097 17.5882 10.9508 17.9606 10.6853 18.2138ZM14.9681 19.7381C14.4956 20.1308 13.7768 20.3175 12.8981 20.3175C12.3716 20.3175 11.9992 20.2837 11.7461 20.25V15.8434C12.2002 15.7773 12.6587 15.7461 13.1175 15.75C13.9691 15.75 14.5226 15.903 14.9546 16.2292C15.4215 16.5757 15.714 17.1281 15.714 17.9213C15.714 18.7796 15.4001 19.3725 14.9681 19.7381ZM19.125 16.6163H17.4015V17.6411H19.0125V18.4669H17.4015V20.2714H16.3823V15.7837H19.125V16.6163ZM15.75 10.125H14.625V4.5L20.25 10.125H15.75Z" fill="white"/>
                    </svg>
                  </a>
                </div>
                <?php endif; ?>
                <?php if($pdf_standards_document) : ?>
                <div class="btn-wrap">
                  <a class="btn bgc-bl" href="<?php echo esc_url($pdf_standards_document); ?>" download>
                    Standards Document
                    <svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9.30034 16.515C9.09334 16.515 8.95384 16.5352 8.88184 16.5555V17.8807C8.96734 17.901 9.07421 17.9066 9.22159 17.9066C9.76046 17.9066 10.0923 17.6344 10.0923 17.1742C10.0923 16.7625 9.80659 16.515 9.30034 16.515ZM13.2232 16.5285C12.9982 16.5285 12.852 16.5487 12.7653 16.569V19.5052C12.852 19.5255 12.9915 19.5255 13.1175 19.5255C14.0366 19.5323 14.6351 19.026 14.6351 17.955C14.6418 17.0212 14.0962 16.5285 13.2232 16.5285Z" fill="white"/>
                      <path d="M15.75 2.25H6.75C6.15326 2.25 5.58097 2.48705 5.15901 2.90901C4.73705 3.33097 4.5 3.90326 4.5 4.5V22.5C4.5 23.0967 4.73705 23.669 5.15901 24.091C5.58097 24.5129 6.15326 24.75 6.75 24.75H20.25C20.8467 24.75 21.419 24.5129 21.841 24.091C22.2629 23.669 22.5 23.0967 22.5 22.5V9L15.75 2.25ZM10.6853 18.2138C10.3376 18.54 9.82462 18.6863 9.22725 18.6863C9.1114 18.6884 8.99556 18.6817 8.88075 18.666V20.2702H7.875V15.8422C8.32891 15.7747 8.78751 15.7439 9.24637 15.75C9.873 15.75 10.3185 15.8693 10.6189 16.1089C10.9046 16.3361 11.0981 16.7085 11.0981 17.1472C11.097 17.5882 10.9508 17.9606 10.6853 18.2138ZM14.9681 19.7381C14.4956 20.1308 13.7768 20.3175 12.8981 20.3175C12.3716 20.3175 11.9992 20.2837 11.7461 20.25V15.8434C12.2002 15.7773 12.6587 15.7461 13.1175 15.75C13.9691 15.75 14.5226 15.903 14.9546 16.2292C15.4215 16.5757 15.714 17.1281 15.714 17.9213C15.714 18.7796 15.4001 19.3725 14.9681 19.7381ZM19.125 16.6163H17.4015V17.6411H19.0125V18.4669H17.4015V20.2714H16.3823V15.7837H19.125V16.6163ZM15.75 10.125H14.625V4.5L20.25 10.125H15.75Z" fill="white"/>
                    </svg>
                  </a>
                </div>
                <?php endif; ?>
                <?php if($pdf_company_info) : ?>
                <div class="btn-wrap">
                  <a class="btn bgc-bl" href="<?php echo esc_url($pdf_company_info); ?>" download>
                    Company info
                    <svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9.30034 16.515C9.09334 16.515 8.95384 16.5352 8.88184 16.5555V17.8807C8.96734 17.901 9.07421 17.9066 9.22159 17.9066C9.76046 17.9066 10.0923 17.6344 10.0923 17.1742C10.0923 16.7625 9.80659 16.515 9.30034 16.515ZM13.2232 16.5285C12.9982 16.5285 12.852 16.5487 12.7653 16.569V19.5052C12.852 19.5255 12.9915 19.5255 13.1175 19.5255C14.0366 19.5323 14.6351 19.026 14.6351 17.955C14.6418 17.0212 14.0962 16.5285 13.2232 16.5285Z" fill="white"/>
                      <path d="M15.75 2.25H6.75C6.15326 2.25 5.58097 2.48705 5.15901 2.90901C4.73705 3.33097 4.5 3.90326 4.5 4.5V22.5C4.5 23.0967 4.73705 23.669 5.15901 24.091C5.58097 24.5129 6.15326 24.75 6.75 24.75H20.25C20.8467 24.75 21.419 24.5129 21.841 24.091C22.2629 23.669 22.5 23.0967 22.5 22.5V9L15.75 2.25ZM10.6853 18.2138C10.3376 18.54 9.82462 18.6863 9.22725 18.6863C9.1114 18.6884 8.99556 18.6817 8.88075 18.666V20.2702H7.875V15.8422C8.32891 15.7747 8.78751 15.7439 9.24637 15.75C9.873 15.75 10.3185 15.8693 10.6189 16.1089C10.9046 16.3361 11.0981 16.7085 11.0981 17.1472C11.097 17.5882 10.9508 17.9606 10.6853 18.2138ZM14.9681 19.7381C14.4956 20.1308 13.7768 20.3175 12.8981 20.3175C12.3716 20.3175 11.9992 20.2837 11.7461 20.25V15.8434C12.2002 15.7773 12.6587 15.7461 13.1175 15.75C13.9691 15.75 14.5226 15.903 14.9546 16.2292C15.4215 16.5757 15.714 17.1281 15.714 17.9213C15.714 18.7796 15.4001 19.3725 14.9681 19.7381ZM19.125 16.6163H17.4015V17.6411H19.0125V18.4669H17.4015V20.2714H16.3823V15.7837H19.125V16.6163ZM15.75 10.125H14.625V4.5L20.25 10.125H15.75Z" fill="white"/>
                    </svg>
                  </a>
                </div>
                <?php endif; ?>
                <div class="btn-wrap">
                  <a class="btn bgc-re" href="#">
                    Contact the manufacturer
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
              </div>

              <div class="txt">
                <?php the_content(); ?>
              </div>

              <div class="custom-fields">
                <table>
                  <?php if (get_field('item_num')) : ?>
                  <tr>
                    <th>Item No.</th>
                    <td><?php echo esc_html(get_field('item_num')); ?></td>
                  </tr>
                  <?php endif; ?>
                  
                  <?php if (get_field('item_size')) : ?>
                  <tr>
                    <th>Size(Qty)/<br>Dimensions</th>
                    <td><?php echo esc_html(get_field('item_size')); ?></td>
                  </tr>
                  <?php endif; ?>
                  
                  <?php if (get_field('item_moq')) : ?>
                  <tr>
                    <th>MOQ</th>
                    <td><?php echo esc_html(get_field('item_moq')); ?></td>
                  </tr>
                  <?php endif; ?>
                  
                  <?php if (get_field('item_case-size')) : ?>
                  <tr>
                    <th>Case size/<br>Pakking size(cm)</th>
                    <td><?php echo esc_html(get_field('item_case-size')); ?></td>
                  </tr>
                  <?php endif; ?>
                  
                  <?php if (get_field('item_material')) : ?>
                  <tr>
                    <th>Raw meterial name</th>
                    <td>
                      <?php echo nl2br(esc_html(get_field('item_material'))); ?>
                    </td>
                  </tr>
                  <?php endif; ?>
                  
                  <?php if (get_field('item_allergy')) : ?>
                  <tr>
                    <th>Allergy</th>
                    <td><?php echo esc_html(get_field('item_allergy')); ?></td>
                  </tr>
                  <?php endif; ?>
                  
                  <?php if (get_field('item_best-before')) : ?>
                  <tr>
                    <th>Best-before date</th>
                    <td><?php echo esc_html(get_field('item_best-before')); ?></td>
                  </tr>
                  <?php endif; ?>
                  
                  <?php if (get_field('item_preservation')) : ?>
                  <tr>
                    <th>Preservation methods</th>
                    <td><?php echo esc_html(get_field('item_preservation')); ?></td>
                  </tr>
                  <?php endif; ?>
                </table>
              </div>

            </div><!-- /.detail-content -->

          </div><!-- /.product-content -->

          <div class="products_wrap">
            <h2>More to love</h2>

            <?php
            // 現在の商品のカテゴリを取得
            $categories = get_the_terms(get_the_ID(), 'product-cat');
            // カテゴリが存在するか確認
            if ($categories && !is_wp_error($categories)) {
              // 親カテゴリーとサブカテゴリーを分ける
              $parent_cats = array();
              $child_cats = array();
              foreach ($categories as $category) {
                if ($category->parent == 0) {
                  $parent_cats[] = $category->term_id;
                } else {
                  $child_cats[] = $category->term_id;
                }
              }
              $current_post_id = get_the_ID();
              // 1. 子カテゴリに一致する商品をまず取得
              $child_args = array();
              if (!empty($child_cats)) {
                $child_args = array(
                  'post_type' => 'product',
                  'posts_per_page' => -1, // 一時的にすべて取得
                  'post__not_in' => array($current_post_id),
                  'tax_query' => array(
                    array(
                    'taxonomy' => 'product-cat',
                    'field' => 'term_id',
                    'terms' => $child_cats,
                    'operator' => 'IN',
                    ),
                  ),
                  'fields' => 'ids', // IDのみ取得
                );
              }
              // 2. 親カテゴリに一致する商品を取得
              $parent_args = array();
              if (!empty($parent_cats)) {
                $parent_args = array(
                  'post_type' => 'product',
                  'posts_per_page' => -1, // 一時的にすべて取得
                  'post__not_in' => array($current_post_id),
                  'tax_query' => array(
                    array(
                    'taxonomy' => 'product-cat',
                    'field' => 'term_id',
                    'terms' => $parent_cats,
                    'operator' => 'IN',
                    ),
                  ),
                  'fields' => 'ids', // IDのみ取得
                );
              }
              // 子カテゴリと親カテゴリに一致する商品を取得
              $child_products = !empty($child_args) ? get_posts($child_args) : array();
              $parent_products = !empty($parent_args) ? get_posts($parent_args) : array();
              // 両方の結果を統合し、子カテゴリの商品を優先
              $related_ids = array_merge($child_products, $parent_products);
              $related_ids = array_unique($related_ids); // 重複を削除
              // 表示件数を10件に制限
              $related_ids = array_slice($related_ids, 0, 10);
              // 関連商品があるか確認
              if (!empty($related_ids)) {
                // 最終的な関連商品のクエリ
                $args = array(
                  'post_type' => 'product',
                  'posts_per_page' => 10,
                  'post__in' => $related_ids,
                  'orderby' => 'post__in', // 子カテゴリの商品が優先的に表示される順序を維持
                );
                $related_products = new WP_Query($args);
                if ($related_products->have_posts()) :
            ?>
            
            <div class="flex-column05">
              <?php while ($related_products->have_posts()) : $related_products->the_post(); ?>

              <?php get_template_part('inc/product-card'); ?>

              <?php endwhile; ?>
            </div>
            
            <?php 
                wp_reset_postdata(); // 投稿データをリセット
                else: 
            ?>
              <p class="no-related">No related products found.</p>
            <?php 
                endif;
              } else {
            ?>
              <p class="no-related">No related products found.</p>
            <?php
              }
            }
            ?>
          </div>


        </div><!-- /.inner -->

      </div>

    </div>

  </main>
<?php get_footer(); ?>



