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

          <h2>Thank you for your inquiry.</h2>

          <div class="contact_wrap thanks">
            <p>
              Thank you very much for your inquiry to J-FOOD HUB. We will contact you shortly after confirming the contents of your inquiry. We will also send an auto-reply confirmation e-mail to the e-mail address you entered.
            </p>
            <p>
              If you do not receive an e-mail after a while, it is possible that the e-mail address you entered is incorrect or that it has been sorted into your spam folder. Please check the above information and contact us again using the form.
            </p>
            <div class="btn-wrap">
              <a class="btn bgc-re" href="<?php echo home_url('/'); ?>">HOME</a>
            </div>
          </div>

        </div><!-- /.inner -->

      </div>

    </div>

  </main>
<?php get_footer(); ?>