  <?php if ( !is_page(array('login', 'register','password')) ) : ?>
  <footer>
    <div class="footer-inner">

      <div class="logo img-box u-sp"><img src="<?php echo get_template_directory_uri(); ?>/images/jfh-logo_yoko.png" alt=""></div>


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
          <p><a href="<?php echo home_url('/maker'); ?>">Maker</a></p>
        </div>

        <div class="fnav_list">
          <p>Get to know us</p>
          <nav class="nav-accordion">
            <ul>
              <li><a href="<?php echo home_url('/contact'); ?>">Inquiries about J-FOOD HUB</a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Operating company</a></li>
            </ul>
          </nav>
        </div>

      </div>

      <div class="logo img-box u-pc"><img src="<?php echo get_template_directory_uri(); ?>/images/jfh-logo_yoko.png" alt=""></div>

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
  <?php wp_footer(); ?>
</body>

</html>