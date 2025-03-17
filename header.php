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

  <title>J-FOOD HUB</title>
  <meta name="description" content="" />
  <meta name="keywords" content="" />
</head>

<body>
  <?php wp_body_open(); ?>

  <header>
    <div class="header-inner">

      <div class="logo img-box">
        <a href="<?php echo home_url('/'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/jfh-logo_y_wh.png" alt=""></a>
      </div>

      <div class="serch header-serch">
        <form class="search-form">
          <div class="search-input-wrapper">
            <input type="text" class="search-input" placeholder="What are you looking fore? ex) sweets, okinawa">
            <button type="submit" class="search-icon-button">
              <img src="<?php echo get_template_directory_uri(); ?>/images/noimage01.png" alt="">
            </button>
          </div>
        </form>
      </div>

      <div class="header-acount">

        <div class="acount-wrapper">
          <div class="mylist">
            <a href="#"><img src="#" alt="">My List</a>
          </div>
          <div class="user">
            <div class="u-name">AAA BBB Inc.</div>
            <a href="#" class="acount-link">
              <img src="#" alt="">
            </a>
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

    </div>

    </div>
  </header>

  <div class="drawer-menu">
    <div class="drawer-menu_inner">

      <div class="nav_list">
        <p><a href="#">Home</a></p>
      </div>

      <div class="nav_list">
        <p>Categories</p>
        <nav>
          <ul>
            <li><a href="#">cate-menue01</a></li>
            <li><a href="#">cate-menue02</a></li>
            <li><a href="#">cate-menue03</a></li>
            <li><a href="#">cate-menue04</a></li>
            <li><a href="#">cate-menue05</a></li>
            <li><a href="#">cate-menue06</a></li>
          </ul>
        </nav>
      </div>

      <div class="nav_list">
        <p>Region</p>
        <nav>
          <ul>
            <li><a href="#">Okinawa</a></li>
            <li><a href="#">Kyoto</a></li>
            <li><a href="#">Fukuoka</a></li>
          </ul>
        </nav>
      </div>

      <div class="btnArea">
        <div class="btn-wrap side-btn">
          <a class="btn bgc-bl" href="#">Serch Products</a>
        </div>

        <div class="btn-wrap side-btn">
          <a class="btn bgc-bl" href="#">My Page</a>
        </div>
      </div>

    </div><!-- /.drawer-menu_inner -->
  </div><!-- /.drawer-menu -->