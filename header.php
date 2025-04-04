<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <!-- favicon -->
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/images/fav.png" />
  <!-- <meta name="robots" content="index, follow"> -->
  <meta name="format-detection" content="telephone=no">
  <!-- css -->
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/reset.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/yakuhanjp@4.0.1/dist/css/yakuhanjp.css">
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/style.css">

  <!-- Googleフォント -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

  <title>J-FOOD HUB</title>
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <?php wp_head(); ?>
</head>

<body>
  <?php wp_body_open(); ?>

  <?php
    $is_logged_in = is_user_logged_in();
  ?>

  <header>
    <div class="header-inner">

      <div class="logo img-box">
        <a href="<?php echo home_url('/'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/jfh-logo_y_wh.png" alt=""></a>
      </div>

      <?php if ( !is_page(array('login', 'register', 'password')) ) : ?>

        <div class="serch header-serch">
          <form class="search-form" action="<?php echo esc_url(home_url('/')); ?>" method="get">
            <div class="search-input-wrapper">
              <input type="text" class="search-input" name="s" value="<?php echo get_search_query(); ?>" placeholder="What are you looking fore? ex) sweets, okinawa">
              <button type="submit" class="search-icon-button">
                <img src="<?php echo get_template_directory_uri(); ?>/images/icon-search.svg" alt="">
              </button>
            </div>
            <input type="hidden" name="post_type" value="any">
          </form>
        </div>

        <div class="header-acount">
          <div class="acount-wrapper">
            <!-- <div class="mylist">
              <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-love.svg" alt="">My List</a>
            </div> -->
            <div class="user">
              <div class="u-name u-pc"><?php echo esc_html(wp_get_current_user()->display_name); ?></div>
              <button class="acount-link">
                <img class="account" src="<?php echo get_template_directory_uri(); ?>/images/icon-account.svg" alt="">
                <img class="arrow" src="<?php echo get_template_directory_uri(); ?>/images/icon-arrow.svg" alt="">
              </button>
              <div class="acc-menu">
                <ul>
                  <?php if (is_logged_in_user()): ?>
                    <li><a href="<?php echo esc_url(get_mypage_url()); ?>">My Profile</a></li>
                  <?php endif; ?>
                  <li><?php echo do_shortcode('[wpmem_loginout login_text="Sign in" logout_text="Sign out"]'); ?></li>
                </ul>
              </div>
            </div>
          </div>

          <div class="u-sp">
            <div class="hamberger-wrap">
              <div class="ham-inner">
                <div class="hambager-content">
                  <button type="button" class="hambager">
                    <span class="c-line"></span>
                    <span class="c-line"></span>
                    <span class="c-line"></span>
                  </button>
                </div><!-- /.hambager-content -->
              </div>
            </div><!-- hamberger-wrap -->
          </div>
        </div>

      <?php endif; ?>

    </div>

    </div>
  </header>

  <div class="drawer-menu">
    <div class="drawer-menu_inner">

      <div class="serch header-serch">
        <form class="search-form" action="<?php echo esc_url(home_url('/')); ?>" method="get">
          <div class="search-input-wrapper">
            <input type="text" class="search-input" name="s" value="<?php echo get_search_query(); ?>" placeholder="What are you looking fore? ex) sweets, okinawa">
            <button type="submit" class="search-icon-button">
              <img src="<?php echo get_template_directory_uri(); ?>/images/icon-search.svg" alt="">
            </button>
          </div>
          <input type="hidden" name="post_type" value="any">
        </form>
      </div>

      <div class="drawer-menu_wrap">
        <div class="nav_list home">
          <p><a href="<?php echo home_url('/'); ?>">Home</a></p>
        </div>
  
        <div class="nav_list food">
          <p>Categories</p>
          <nav>
            <ul>
              <?php get_template_part('inc/cate-parent-link'); ?>
            </ul>
          </nav>
        </div>
  
        <div class="nav_list region">
          <p>Region</p>
          <nav>
            <ul>
              <?php get_template_part('inc/cate-region-link'); ?>
            </ul>
          </nav>
        </div>
  
        <?php if (is_user_buyer()): ?>
          <div class="nav_list maker">
            <p><a href="<?php echo home_url('/maker'); ?>">Maker</a></p>
          </div>
        <?php endif; ?>
  
        <?php if (is_user_maker()): ?>
          <div class="nav_list buyer">
            <p><a href="<?php echo home_url('/buyer'); ?>">Buyer</a></p>
          </div>
        <?php endif; ?>
  
  
        <div class="btnArea">
          <div class="btn-wrap side-btn">
            <a class="btn bgc-bl" href="<?php echo home_url('/product'); ?>">Serch Products</a>
          </div>
  
          <?php if (is_logged_in_user()): ?>
            <div class="btn-wrap side-btn">
              <a class="btn bgc-bl" href="<?php echo esc_url(get_mypage_url()); ?>">My Page</a>
            </div>
          <?php endif; ?>
  
        </div>
      </div>

    </div><!-- /.drawer-menu_inner -->
  </div><!-- /.drawer-menu -->