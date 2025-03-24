<?php get_header(); ?>
  <main>

    <div class="contents-wrap">

      <?php get_sidebar(); ?>

      <div class="contents" id="pageContact">
        <div class="inner">

          <div class="breadcrumbs">
            <nav>
              <ul>
                <li><a href="<?php echo home_url('/'); ?>">HOME</a></li>
                <li>Contact</li>
              </ul>
            </nav>
          </div>

          <h2>Contact</h2>

          <div class="contact_wrap">
            <?php echo do_shortcode('[contact-form-7 id="fbfc89d" title="お問い合わせフォーム"]'); ?>
          </div>

        </div><!-- /.inner -->

      </div>

    </div>

  </main>
<?php get_footer(); ?>