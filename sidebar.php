<aside <?php echo (is_singular(['maker', 'buyer'])) ? ' class="snav_maker-buyer"' : ''; ?>>

  <div class="sidebar-wrapper">

  <?php if((is_singular(['maker', 'buyer']))): ?>
    
    <div>
      <nav>
        <ul>
          <li>
            <a href="#profile"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-note.svg" alt="">Company Profile</a>
          </li>
          <?php if((is_singular('maker'))): ?>
            <li>
              <a href="#export"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-check.svg" alt="">Export Conditions</a>
            </li>
            <li>
              <a href="#maker-product"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-box.svg" alt="">Product List</a>
            </li>
          <?php elseif((is_singular('buyer'))): ?>
            <li>
              <a href="#wanted"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-box.svg" alt="">Wanted Products</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>

      <?php if((is_singular('maker'))): ?>
        <div class="btn-wrap"><a class="btn bgc-re" href="#sendmail">Contact Maker</a></div>
      <?php endif; ?>

    </div>
  
  <?php else: ?>

    <div class="snav_list home">
      <p><a href="<?php echo home_url('/'); ?>">Home</a></p>
    </div>

    <div class="snav_list food">
      <p>Categories</p>
      <nav>
        <ul>
          <?php get_template_part('inc/cate-parent-link'); ?>
        </ul>
      </nav>
    </div>

    <div class="snav_list region">
      <p>Region</p>
      <nav>
        <ul>
          <?php get_template_part('inc/cate-region-link'); ?>
        </ul>
      </nav>
    </div>

    <div class="snav_list maker">
      <p><a href="<?php echo home_url('/maker'); ?>">Maker</a></p>
    </div>

    <div class="side-btnArea">
      <div class="btn-wrap side-btn">
        <a class="btn bgc-bl" href="<?php echo home_url('/product'); ?>">Serch Products</a>
      </div>

      <div class="btn-wrap side-btn">
        <a class="btn bgc-bl" href="#">My Page</a>
      </div>
    </div>

  <?php endif; ?>

  </div>

</aside>