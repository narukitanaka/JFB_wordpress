<?php get_header(); ?>
  <main>

    <div class="contents-wrap" id="login">

      <div class="contents">
        <div class="logo img-box"><img src="<?php echo get_template_directory_uri(); ?>/images/jfh-logo_yoko.png" alt=""></div>
        <?php the_content(); ?>

        <?php if (is_logged_in_user()): ?>
          <div class="btn-wrap login"><a class="btn bgc-bl" href="<?php echo home_url('/wp-admin/profile.php'); ?>">Edit Profile</a></div>
          <div class="btn-wrap login"><a class="btn bgc-re" href="<?php echo home_url('/'); ?>">Enter Site</a></div>
        <?php endif; ?>

        <div class="links">
          <a href="<?php echo home_url('/register'); ?>">If you have not registered</a>
          <a href="<?php echo home_url('/password'); ?>">Forgot your password?</a>
        </div>
      </div><!-- /.contents -->

    </div><!-- /.contents-wrap -->

  </main>
<?php get_footer(); ?>