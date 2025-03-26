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

            <div class="information">
              <h3>Our Strengths</h3>
              <div class="txt">
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

            <div class="box send-message">
              <div id="sendmail">
                <h2>Send message to Buyer</h2>
                <?php echo do_shortcode('[contact-form-7 id="94e417d" title="バイヤーお問い合わせ"]'); ?>
              </div>
            </div>

          </div><!-- /.product-content -->


        </div><!-- /.inner -->

      </div>

    </div>

  </main>
<?php get_footer(); ?>



