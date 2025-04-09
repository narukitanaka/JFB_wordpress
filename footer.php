  <?php if ( !is_page(array('login', 'register','password')) ) : ?>
  <footer>
    <div class="footer-inner">

      <div class="logo-column u-sp">
        <div class="logo img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/jfh-logo_yoko.png" alt=""></div>
        <a href="<?php echo home_url('/contact'); ?>">
          <img src="<?php echo get_template_directory_uri(); ?>/images/icon-mail.svg" alt="">
          Inquiries about J-FOOD HUB
        </a>
      </div>


      <div class="footerNav-wrap">
        <div class="fnav_list">
          <p>Categories</p>
          <nav>
            <ul>
              <?php get_template_part('inc/cate-parent-link'); ?>
            </ul>
          </nav>
        </div>

        <div class="fnav_list">
          <p>Region</p>
          <nav>
            <ul>
              <?php get_template_part('inc/cate-region-link'); ?>
            </ul>
          </nav>
        </div>

        <div class="fnav_list">
          <?php if (is_user_buyer()): ?>
            <p><a href="<?php echo home_url('/maker'); ?>">Maker Lists</a></p>
          <?php endif; ?>
          <?php if (is_user_maker()): ?>
            <p><a href="<?php echo home_url('/buyer'); ?>">Buyer Lists</a></p>
          <?php endif; ?>
        </div>

        <div class="fnav_list">
          <p>Get to know us</p>
          <nav class="nav-accordion">
            <ul>
              <li><a href="<?php echo home_url('/contact'); ?>">Inquiries about J-FOOD HUB</a></li>
              <li><a href="<?php echo home_url('/policy'); ?>">Privacy Policy</a></li>
              <!-- <li><a href="#">Operating company</a></li> -->
            </ul>
          </nav>
        </div>

      </div>

      <div class="logo-column u-pc">
        <div class="logo img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/jfh-logo_yoko.png" alt=""></div>
        <a href="<?php echo home_url('/contact'); ?>">
          <img src="<?php echo get_template_directory_uri(); ?>/images/icon-mail.svg" alt="">
          Inquiries about J-FOOD HUB
        </a>
      </div>

    </div>
    <p><small>@2025 J-FOOD HUB</small></p>
  </footer>
  <?php endif; ?>

  <!-- js -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/js/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/filter-script.js"></script>
  <script>
    //商品詳細スライダー
    const swiperitem = new Swiper(".single-productSwiper", {
      loop: true,
    }); 
    function thumbnail(index) {
      swiperitem.slideTo(index);
    }
  </script>
  <script>
    jQuery(document).ready(function($) {
      // 「View More」ボタンのクリックイベント
      $('.load-more-button').on('click', function() {
        var button = $(this);
        var postType = button.data('post-type');
        var searchQuery = button.data('search');
        var paged = button.data('paged');
        var maxPages = button.data('max-pages');
        // 次のページを読み込む
        paged++;
        button.data('paged', paged);
        $.ajax({
          url: '<?php echo admin_url('admin-ajax.php'); ?>',
          type: 'POST',
          data: {
            action: 'load_more_search_results',
            post_type: postType,
            search: searchQuery,
            paged: paged,
            security: '<?php echo wp_create_nonce('load_more_search_results'); ?>'
          },
          success: function(response) {
            if (response) {
              // 結果をコンテナに追加
              $('#' + postType + '-items').append(response);
              
              // 最後のページの場合はボタンを非表示
              if (paged >= maxPages) {
                button.hide();
              }
            } else {
              button.hide();
            }
          }
        });
      });
    });
  </script>

  <?php wp_footer(); ?>
</body>

</html>