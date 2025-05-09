<?php get_header(); ?>
  <main>
    <div class="single-head-content">
      <div class="inner">

        <div class="breadcrumbs">
          <nav>
            <ul>
              <li><a href="<?php echo home_url('/'); ?>">HOME</a></li>
              <li><a href="<?php echo home_url('/buyer'); ?>">Buyer Lists</a></li>
              <li><?php the_title(); ?></li>
            </ul>
          </nav>
        </div>

        <div class="head-content">

          <div class="company">

            <div class="ttl">
              <div class="name"><?php the_title(); ?></div>
              <div class="region">
                <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="region">
                <?php 
                $regions = get_the_terms(get_the_ID(), 'country');
                if (!empty($regions) && !is_wp_error($regions)) {
                  echo esc_html($regions[0]->name);
                }
                ?>
              </div>
            </div>
          </div>

          <div class="btn-area">

            <?php if (is_user_maker()): ?>

              <div class="favorite-button-container">
                <?php echo do_shortcode('[favorite_button]'); ?>
              </div>

              <?php if (get_field('mail-address')) : ?>
                <div class="btn-wrap">
                  <a class="btn bgc-re" href="#sendmail">
                    Offer to Buyer
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
              <?php endif; ?>
            <?php endif; ?>

            <?php if (is_user_buyer()): ?>
              <div class="btn-wrap long">
                <a class="btn bgc-re" href="<?php echo home_url('/wp-admin/profile.php'); ?>">
                  Request a correction
                  <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.4165 6.41667H5.49984C5.01361 6.41667 4.54729 6.60983 4.20347 6.95364C3.85966 7.29746 3.6665 7.76377 3.6665 8.25001V16.5C3.6665 16.9862 3.85966 17.4526 4.20347 17.7964C4.54729 18.1402 5.01361 18.3333 5.49984 18.3333H13.7498C14.2361 18.3333 14.7024 18.1402 15.0462 17.7964C15.39 17.4526 15.5832 16.9862 15.5832 16.5V15.5833" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14.6667 4.58334L17.4167 7.33334M18.6863 6.03626C19.0473 5.67523 19.2501 5.18557 19.2501 4.67501C19.2501 4.16444 19.0473 3.67478 18.6863 3.31376C18.3252 2.95273 17.8356 2.74991 17.325 2.74991C16.8144 2.74991 16.3248 2.95273 15.9638 3.31376L8.25 11V13.75H11L18.6863 6.03626Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </a>
              </div>
            <?php endif; ?>

          </div>

        </div>

      </div>
    </div>

    <div class="contents-wrap content_maker-buyer">

      <?php get_sidebar(); ?>

      <div class="contents" id="singleBuyer">
        <div class="inner">

          <div class="maker_buyer-content">

            <div class="img-wrap cplumn-04 flex-column04">
              <?php if (has_post_thumbnail()) : ?>
                <div class="img-box obj-fit">
                  <?php the_post_thumbnail(); ?>
                </div>
              <?php endif; ?>
              <?php 
              $images = get_field('buyer-img_repaet'); // 繰り返しフィールドの値を取得
              // 繰り返しフィールドに値がある場合
              if($images): 
                foreach($images as $image): 
                  // 画像フィールドに値がある場合のみ表示
                  if(!empty($image['buyer-img'])): ?>
                    <div class="img-box obj-fit">
                      <img src="<?php echo esc_url($image['buyer-img']); ?>" alt="">
                    </div>
                  <?php endif; 
                endforeach;
              endif; 
              ?>
            </div>

            <?php if (get_the_content()): ?>
              <div class="information">
                <h3>Our Strengths</h3>
                <div class="txt">
                  <?php the_content(); ?>
                </div>
              </div>
            <?php endif; ?>

            <div class="box profile">
              <div id="profile">
                <h2>Company Profile</h2>
                <table>
                  <?php 
                    $company_profile = get_field('group_company-profile'); // グループフィールドを取得
                  ?>
                  <?php if ($company_profile && !empty($company_profile['company_name'])) : ?>
                    <tr>
                      <th>Company Name</th>
                      <td><?php echo esc_html($company_profile['company_name']); ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if ($company_profile && !empty($company_profile['representative'])) : ?>
                    <tr>
                      <th>Representative</th>
                      <td><?php echo esc_html($company_profile['representative']); ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if ($company_profile && !empty($company_profile['company_location'])) : ?>
                    <tr>
                      <th>Comapny Location</th>
                      <td><?php echo esc_html($company_profile['company_location']); ?></td>
                    </tr>
                  <?php endif; ?>
                  <?php if ($company_profile && !empty($company_profile['business'])) : ?>
                    <tr>
                      <th>Business</th>
                      <td><?php echo esc_html($company_profile['business']); ?></td>
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

            <div class="box wanted">

              <div id="wanted">
                <h2>Wanted Products</h2>
                <div class="cate">
                  <?php get_template_part('inc/snipets-cate'); ?>
                </div>
              </div>
            </div>


            <?php if (is_user_maker()): ?>
              <?php if (get_field('mail-address')) : ?>
                <div class="box send-message">
                  <div id="sendmail">
                    <h2>Send message to Buyer</h2>
                    <?php echo do_shortcode('[contact-form-7 id="94e417d" title="バイヤーお問い合わせ"]'); ?>
                  </div>
                </div>
              <?php endif; ?>
            <?php endif; ?>

          </div><!-- /.product-content -->


        </div><!-- /.inner -->

      </div>

    </div>

  </main>
<?php get_footer(); ?>



