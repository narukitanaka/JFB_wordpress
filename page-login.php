<?php get_header(); ?>
  <main>

    <div class="contents-wrap" id="login">

      <div class="contents">
        <div class="logo img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/jfh-logo_yoko.png" alt=""></div>
        <?php the_content(); ?>
        <div class="links">
          <a href="#">If you have not registered</a>
          <a href="#">Forgot your password?</a>
        </div>
      </div><!-- /.contents -->

    </div><!-- /.contents-wrap -->

  </main>
<?php get_footer(); ?>